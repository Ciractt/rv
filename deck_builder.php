<?php
require_once 'config.php';
requireLogin();

$pdo = getDB();
$user = getCurrentUser();

// ---------------------------------------------------------------------
// 1. Load all cards
// ---------------------------------------------------------------------
$all_cards = $pdo->query("
    SELECT id, name, energy, might, power, rarity, card_type AS type,
           color, set_name AS `set`, region, card_art_url, card_code,
           description, flavor_text, alt_art_url
    FROM cards
    ORDER BY energy, name
")->fetchAll(PDO::FETCH_ASSOC);

// ---------------------------------------------------------------------
// 2. Load user collection
// ---------------------------------------------------------------------
$stmt = $pdo->prepare("SELECT card_id, quantity FROM user_collections WHERE user_id = ?");
$stmt->execute([$user['id']]);
$collection = [];
while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
    $collection[$row['card_id']] = (int)$row['quantity'];
}

// ---------------------------------------------------------------------
// 3. Load user decks
// ---------------------------------------------------------------------
$stmt = $pdo->prepare("SELECT * FROM decks WHERE user_id = ? ORDER BY updated_at DESC");
$stmt->execute([$user['id']]);
$user_decks = $stmt->fetchAll(PDO::FETCH_ASSOC);

// ---------------------------------------------------------------------
// 4. Load current deck if editing
// ---------------------------------------------------------------------
$current_deck = null;
$deck_cards   = [];
if (!empty($_GET['deck_id'])) {
    $stmt = $pdo->prepare("SELECT * FROM decks WHERE id = ? AND user_id = ?");
    $stmt->execute([$_GET['deck_id'], $user['id']]);
    $current_deck = $stmt->fetch(PDO::FETCH_ASSOC);

    if ($current_deck) {
        $stmt = $pdo->prepare("
            SELECT c.*, dc.quantity
            FROM deck_cards dc
            JOIN cards c ON dc.card_id = c.id
            WHERE dc.deck_id = ?
        ");
        $stmt->execute([$current_deck['id']]);
        $deck_cards = $stmt->fetchAll(PDO::FETCH_ASSOC);
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Deck Builder - <?php echo SITE_NAME; ?></title>

    <!-- Styles -->
    <link rel="stylesheet" href="css/style.css">
    <link rel="stylesheet" href="css/deck_builder_riftmana.css">

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body class="riftmana-deck-builder">
<?php include 'includes/header.php'; ?>

<main class="riftmana-container">
    <!-- ====================== TOP BAR ====================== -->
    <div class="riftmana-top-bar">
    <div class="search-wrapper">
        <input type="text" id="globalSearch" placeholder="Search by name, ID, tags, or keywords" class="riftmana-search">
    </div>

    <div class="top-controls">
        <label class="toggle">
            <input type="checkbox" id="showAltArt">
            <span>Show Alt Art</span>
        </label>
        <label class="toggle">
            <input type="checkbox" id="showCollection">
            <span>Show Collection</span>
        </label>
        <select id="sortBy" class="riftmana-select">
            <option>Sort by...</option>
            <option value="cost">Cost</option>
            <option value="name">Name</option>
            <option value="rarity">Rarity</option>
        </select>
        <select id="setFilter" class="riftmana-select">
            <option>All Sets</option>
            <!-- sets -->
        </select>
    </div>
</div>

    <!-- ====================== FILTER ROWS ====================== -->
    <div class="riftmana-filters">
        <!-- Type Filters -->
        <div class="filter-row">
            <button class="filter-btn active" data-type="">All</button>
            <button class="filter-btn" data-type="Champion">Champion</button>
            <button class="filter-btn" data-type="Unit">Unit</button>
            <button class="filter-btn" data-type="Spell">Spell</button>
            <button class="filter-btn" data-type="Gear">Gear</button>
            <button class="filter-btn" data-type="Signature">Signature</button>
            <button class="filter-btn" data-type="Seal">Seal</button>
            <button class="filter-btn" data-type="Rune">Rune</button>
        </div>

        <!-- Cost Row -->
        <div class="cost-row">
            <?php for ($i = 0; $i <= 7; $i++): ?>
                <button class="cost-btn" data-cost="<?php echo $i; ?>"><?php echo $i; ?></button>
            <?php endfor; ?>
            <button class="cost-btn" data-cost="8">8+</button>
        </div>

        <!-- Color Filters -->
        <div class="color-row">
            <?php foreach (['Fury','Calm','Mind','Body','Chaos','Order'] as $col): ?>
                <button class="color-btn" data-color="<?php echo $col; ?>" title="<?php echo $col; ?>">
                    <img src="https://cdn.piltoverarchive.com/colors/<?php echo $col; ?>.webp" alt="">
                </button>
            <?php endforeach; ?>
        </div>
    </div>

    <!-- ====================== MAIN LAYOUT ====================== -->
    <div class="riftmana-layout">
        <!-- LEFT: CARD GRID -->
        <div class="riftmana-card-grid" id="cardGrid">
            <?php foreach ($all_cards as $card): ?>
                <div class="riftmana-card"
                     data-id="<?php echo $card['id']; ?>"
                     data-name="<?php echo strtolower($card['name']); ?>"
                     data-cost="<?php echo $card['energy']; ?>"
                     data-type="<?php echo strtolower($card['type']); ?>"
                     data-color="<?php echo strtolower($card['color']); ?>"
                     data-set="<?php echo strtolower($card['set'] ?? ''); ?>"
                     data-rarity="<?php echo strtolower($card['rarity']); ?>"
                     onclick="riftAddCard(<?php echo $card['id']; ?>, '<?php echo htmlspecialchars($card['name'], ENT_QUOTES); ?>', <?php echo $card['energy']; ?>)">
                    <img src="<?php echo htmlspecialchars($card['card_art_url']); ?>"
                         alt="<?php echo htmlspecialchars($card['name']); ?>"
                         loading="lazy">
                    <div class="card-counter">
                        <button onclick="event.stopPropagation(); riftDecCard(<?php echo $card['id']; ?>)">-</button>
                        <span data-count="0">0</span>
                        <button onclick="event.stopPropagation(); riftIncCard(<?php echo $card['id']; ?>)">+</button>
                    </div>
                    <div class="collection-badge" style="display:none;"></div>
                </div>
            <?php endforeach; ?>
        </div>

        <!-- RIGHT: DECK PANEL -->
        <div class="riftmana-deck-panel">
            <div class="deck-header">
                <div class="deck-tabs">
                    <button class="tab active" data-tab="main">Main Deck</button>
                    <button class="tab" data-tab="side">Sideboard</button>
                </div>
                <div class="deck-stats">
                    <span>Cards: <strong id="totalCards">0</strong>/56</span>
                </div>
            </div>

            <div class="deck-actions">
                <button id="noRestrictionBtn" class="btn">No Restriction</button>
                <button id="clearDeckBtn" class="btn">Clear</button>
                <button id="newDeckBtn" class="btn">New Deck</button>
                <button id="importBtn" class="btn">Import</button>
                <button id="exportBtn" class="btn">Export</button>
            </div>

            <div class="deck-sections">
                <div class="section" id="legendSection">
                    <h3>LEGEND ( <span>0</span> )</h3>
                    <div class="section-cards"></div>
                </div>
                <div class="section" id="battlefieldSection">
                    <h3>BATTLEFIELD ( <span>0</span> )</h3>
                    <div class="section-cards"></div>
                </div>
                <div class="section" id="runeSection">
                    <h3>RUNE DECK ( <span>0</span> )</h3>
                    <div class="section-cards"></div>
                </div>
                <div class="section" id="mainSection">
                    <h3>MAIN DECK ( <span>0</span> )</h3>
                    <div class="section-cards"></div>
                </div>
            </div>
        </div>
    </div>
</main>

<?php include 'includes/footer.php'; ?>

<!-- ====================== JS DATA ====================== -->
<script>
    const currentDeckCards = <?php echo json_encode($deck_cards); ?>;
    const collectionData   = <?php echo json_encode($collection); ?>;
    const allCards         = <?php echo json_encode($all_cards); ?>;
    const cardDatabase     = {};
    allCards.forEach(c => cardDatabase[c.id] = c);
</script>

<script src="js/deck_builder_riftmana.js"></script>
</body>
</html>
