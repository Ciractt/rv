<?php
/**
 * Card Import Script
 * Fetches all cards from Riftmana API and imports them into the database
 *
 * Usage: php import_cards.php
 */
require_once 'config.php';
set_time_limit(300); // 5 minutes max execution time

$API_BASE_URL = 'https://riftmana.com/wp-json/card-list/v1/filter/';
$TOTAL_PAGES = 6;

echo "=== Riftbound Card Import Script ===\n\n";

$pdo = getDB();

// Step 1: Clear existing cards (optional - comment out to keep existing)
echo "Clearing existing cards...\n";
try {
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 0");
    $pdo->exec("TRUNCATE TABLE deck_cards");
    $pdo->exec("TRUNCATE TABLE user_collections");
    $pdo->exec("TRUNCATE TABLE cards");
    $pdo->exec("SET FOREIGN_KEY_CHECKS = 1");
    echo "✓ Existing cards cleared\n\n";
} catch (Exception $e) {
    echo "Warning: Could not clear tables: " . $e->getMessage() . "\n";
    echo "Continuing anyway (will update existing cards)...\n\n";
}

// Step 2: Fetch and import cards from all pages
$totalImported = 0;
$errors = [];

for ($page = 1; $page <= $TOTAL_PAGES; $page++) {
    echo "Fetching page $page/$TOTAL_PAGES...\n";

    $url = $API_BASE_URL . "?page=$page&search&set&sort=id";

    // Fetch JSON response
    $json = @file_get_contents($url);
    if ($json === false) {
        echo "✗ Failed to fetch page $page\n";
        $errors[] = "Failed to fetch page $page";
        continue;
    }

    // Decode JSON
    $data = json_decode($json, true);
    if (!isset($data['cards']) || !is_string($data['cards'])) {
        echo "✗ No cards HTML in JSON on page $page\n";
        $errors[] = "Invalid JSON structure on page $page";
        continue;
    }

    $html = $data['cards'];
    $cards = parseCardsFromHTML($html);

    echo "Found " . count($cards) . " cards on page $page\n";

    foreach ($cards as $cardData) {
        try {
            importCard($pdo, $cardData);
            $totalImported++;
            echo ".";
        } catch (Exception $e) {
            $errors[] = "Error importing {$cardData['name']}: " . $e->getMessage();
            echo "x";
        }
    }

    echo "\n";
    sleep(1); // Be nice to the server
}

echo "\n=== Import Complete ===\n";
echo "Total cards imported: $totalImported\n";

if (!empty($errors)) {
    echo "\nErrors encountered:\n";
    foreach ($errors as $error) {
        echo "- $error\n";
    }
}

echo "\nDone!\n";

/**
 * Parse card data from HTML (extracted from JSON)
 */
function parseCardsFromHTML($html) {
    $cards = [];

    // Match each <div class="card-item"> block
    preg_match_all('/<div[^>]+class="card-item"[^>]*>.*?(?:<img[^>]*>.*?)?<\/div>/is', $html, $cardBlocks);

    foreach ($cardBlocks[0] as $block) {
        $card = [];

        // Extract data-* attributes
        $card['card_code'] = regexExtract($block, '/data-card-id="([^"]+)"/') ?? '';
        if (empty($card['card_code']) || strlen($card['card_code']) > 50) continue;

        $card['name'] = html_entity_decode(regexExtract($block, '/data-name="([^"]+)"/') ?? '', ENT_QUOTES);
        $card['card_type'] = regexExtract($block, '/data-type="([^"]+)"/') ?? '';
        $card['type_slug'] = regexExtract($block, '/data-type-slug="([^"]+)"/') ?? '';
        $card['color'] = regexExtract($block, '/data-color="([^"]+)"/') ?? '';
        $card['energy'] = regexExtract($block, '/data-cost="([^"]+)"/') ?? null;
        $card['power'] = regexExtract($block, '/data-power="([^"]+)"/') ?? null;
        $card['might'] = regexExtract($block, '/data-might="([^"]+)"/') ?? null;
        $card['description'] = html_entity_decode(regexExtract($block, '/data-effect="([^"]*)"/') ?? '', ENT_QUOTES);
        $card['sub_type'] = regexExtract($block, '/data-sub-type="([^"]*)"/') ?? '';
        $card['set_name'] = regexExtract($block, '/data-set="([^"]+)"/') ?? '';
        $card['rarity'] = regexExtract($block, '/data-rarity="([^"]+)"/') ?? '';
        $card['card_link'] = regexExtract($block, '/data-card-link="([^"]+)"/') ?? '';
        $card['alt_code'] = regexExtract($block, '/data-alt-code="([^"]*)"/') ?: null;

        // Extract image URL from <img src="...webp">
        preg_match('/<img[^>]+src="([^"]+\.webp)"[^>]*>/i', $block, $imgMatch);
        $card['card_art_url'] = $imgMatch[1] ?? null;

        // Extract keywords
        $card['keywords'] = extractKeywords($card['description']);

        // Parse region & champion
        if (!empty($card['sub_type'])) {
            $parts = array_map('trim', explode(',', $card['sub_type']));
            $card['region'] = $parts[0] ?? null;
            $card['champion'] = $parts[1] ?? null;
        } else {
            $card['region'] = null;
            $card['champion'] = null;
        }

        $cards[] = $card;
    }

    return $cards;
}

/**
 * Helper: Extract first match from regex
 */
function regexExtract($haystack, $pattern) {
    if (preg_match($pattern, $haystack, $m)) {
        return $m[1];
    }
    return null;
}

/**
 * Extract keywords from card description
 */
function extractKeywords($description) {
    $keywords = [];
    $knownKeywords = [
        'GANKING', 'ACCELERATE', 'ATTACK', 'CHALLENGER', 'QUICK ATTACK',
        'TOUGH', 'OVERWHELM', 'ELUSIVE', 'LIFESTEAL', 'FEARSOME',
        'BARRIER', 'REGENERATION', 'FROSTBITE', 'STUN', 'RECALL'
    ];

    foreach ($knownKeywords as $keyword) {
        if (stripos($description, $keyword) !== false) {
            $keywords[] = $keyword;
        }
    }

    return implode(',', $keywords);
}

/**
 * Import a single card into the database
 */
function importCard($pdo, $card) {
    $sql = "INSERT INTO cards (
        card_code, alt_code, name, energy, power, might,
        card_type, type_slug, rarity, region, champion, color,
        description, keywords, card_art_url, card_link, set_name
    ) VALUES (
        :card_code, :alt_code, :name, :energy, :power, :might,
        :card_type, :type_slug, :rarity, :region, :champion, :color,
        :description, :keywords, :card_art_url, :card_link, :set_name
    ) ON DUPLICATE KEY UPDATE
        name = VALUES(name),
        energy = VALUES(energy),
        power = VALUES(power),
        might = VALUES(might),
        card_type = VALUES(card_type),
        type_slug = VALUES(type_slug),
        rarity = VALUES(rarity),
        region = VALUES(region),
        champion = VALUES(champion),
        color = VALUES(color),
        description = VALUES(description),
        keywords = VALUES(keywords),
        card_art_url = VALUES(card_art_url),
        card_link = VALUES(card_link),
        set_name = VALUES(set_name),
        updated_at = CURRENT_TIMESTAMP";

    $stmt = $pdo->prepare($sql);
    $stmt->execute([
        ':card_code' => $card['card_code'],
        ':alt_code' => $card['alt_code'],
        ':name' => $card['name'],
        ':energy' => $card['energy'],
        ':power' => $card['power'],
        ':might' => $card['might'],
        ':card_type' => $card['card_type'],
        ':type_slug' => $card['type_slug'],
        ':rarity' => $card['rarity'],
        ':region' => $card['region'],
        ':champion' => $card['champion'],
        ':color' => $card['color'],
        ':description' => $card['description'],
        ':keywords' => $card['keywords'],
        ':card_art_url' => $card['card_art_url'],
        ':card_link' => $card['card_link'],
        ':set_name' => $card['set_name']
    ]);
}
