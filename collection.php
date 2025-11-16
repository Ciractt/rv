<?php
require_once 'config.php';
requireLogin();

$pdo = getDB();
$user = getCurrentUser();

// Load user's collection
$stmt = $pdo->prepare("SELECT card_id, quantity FROM user_collections WHERE user_id = ?");
$stmt->execute([$user['id']]);
$user_collection = [];
while ($row = $stmt->fetch()) {
    $user_collection[$row['card_id']] = $row['quantity'];
}

// Load user's wishlist
$stmt = $pdo->prepare("SELECT card_id FROM user_wishlist WHERE user_id = ?");
$stmt->execute([$user['id']]);
$user_wishlist = [];
while ($row = $stmt->fetch()) {
    $user_wishlist[] = $row['card_id'];
}

// Load all cards
$all_cards = $pdo->query("
    SELECT * FROM cards
    ORDER BY card_code
")->fetchAll();

// Calculate collection statistics
$total_unique_cards = count($all_cards);
$owned_cards = count($user_collection);
$total_cards_count = array_sum($user_collection);

// Calculate collection value (if you have a 'price' field)
$collection_value = 0;
foreach ($user_collection as $card_id => $quantity) {
    $stmt = $pdo->prepare("SELECT price FROM cards WHERE id = ?");
    $stmt->execute([$card_id]);
    $card = $stmt->fetch();
    if ($card && isset($card['price'])) {
        $collection_value += floatval($card['price']) * $quantity;
    }
}

// Get filter options
$regions = $pdo->query("SELECT DISTINCT region FROM cards WHERE region IS NOT NULL ORDER BY region")->fetchAll(PDO::FETCH_COLUMN);
$rarities = $pdo->query("SELECT DISTINCT rarity FROM cards WHERE rarity IS NOT NULL ORDER BY rarity")->fetchAll(PDO::FETCH_COLUMN);
$card_types = $pdo->query("SELECT DISTINCT card_type FROM cards WHERE card_type NOT IN ('Rune', 'Battlefield') ORDER BY card_type")->fetchAll(PDO::FETCH_COLUMN);
$sets = $pdo->query("SELECT DISTINCT set_name FROM cards WHERE set_name IS NOT NULL ORDER BY set_name")->fetchAll(PDO::FETCH_COLUMN);
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>My Collection - <?php echo SITE_NAME; ?></title>

    <!-- Single CSS File -->
    <link rel="stylesheet" href="css/theme.css">

    <style>
        /* Collection-specific styles that extend theme.css */
        .collection-header {
            background: var(--bg-secondary);
            padding: var(--spacing-xl);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-primary);
        }

        .collection-header h1 {
            margin: 0 0 var(--spacing-lg) 0;
        }

        .collection-stats {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(180px, 1fr));
            gap: var(--spacing-lg);
        }

        .stat-card {
            text-align: center;
            padding: var(--spacing-lg);
            background: var(--bg-tertiary);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-primary);
            transition: all var(--transition-base);
        }

        .stat-card:hover {
            border-color: var(--accent-primary);
            box-shadow: var(--shadow-glow);
        }

        .stat-value {
            font-size: 2rem;
            font-weight: 800;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            display: block;
            margin-bottom: var(--spacing-xs);
        }

        .stat-label {
            font-size: 0.9rem;
            color: var(--text-secondary);
        }

        /* Collection Controls */
        .collection-controls {
            background: var(--bg-secondary);
            padding: var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-lg);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-primary);
        }

        .filter-group {
            display: flex;
            gap: var(--spacing-md);
            align-items: center;
            flex-wrap: wrap;
        }

        /* Collection Status Bar */
        .collection-status-bar {
            background: var(--bg-secondary);
            padding: var(--spacing-md) var(--spacing-lg);
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-xl);
            box-shadow: var(--shadow-sm);
            border: 1px solid var(--border-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: var(--spacing-lg);
        }

        .status-info {
            display: flex;
            align-items: center;
            gap: var(--spacing-lg);
        }

        .status-text {
            font-size: 0.95rem;
            color: var(--text-secondary);
        }

        .status-text strong {
            color: var(--text-primary);
        }

        .status-controls {
            display: flex;
            align-items: center;
            gap: var(--spacing-md);
            flex-wrap: wrap;
        }

        /* View Toggle */
        .view-toggle {
            display: flex;
            gap: 0;
            background: var(--bg-tertiary);
            padding: 4px;
            border-radius: var(--radius-md);
            border: 1px solid var(--border-primary);
        }

        .view-toggle-btn {
            padding: var(--spacing-sm) var(--spacing-lg);
            border: none;
            background: transparent;
            cursor: pointer;
            border-radius: var(--radius-sm);
            font-weight: 600;
            font-size: 0.9rem;
            transition: all var(--transition-base);
            color: var(--text-secondary);
        }

        .view-toggle-btn:hover {
            color: var(--text-primary);
        }

        .view-toggle-btn.active {
            background: var(--accent-gradient);
            color: white;
            box-shadow: var(--shadow-sm);
        }

        /* Wishlist Toggle */
        .wishlist-toggle {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
            cursor: pointer;
            padding: var(--spacing-sm) var(--spacing-lg);
            background: var(--bg-tertiary);
            border-radius: var(--radius-md);
            border: 1px solid var(--border-primary);
            font-size: 0.9rem;
            transition: all var(--transition-base);
        }

        .wishlist-toggle:hover {
            border-color: var(--accent-primary);
        }

        .wishlist-toggle input[type="checkbox"] {
            width: 18px;
            height: 18px;
            cursor: pointer;
        }

        /* Collection Grid */
        .collection-grid {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(180px, 1fr));
            gap: var(--spacing-lg);
            padding: var(--spacing-md) 0;
        }

        .collection-card {
            position: relative;
            border-radius: var(--radius-lg);
            overflow: hidden;
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            box-shadow: var(--shadow-sm);
            transition: all var(--transition-base);
        }

        .collection-card:hover {
            transform: translateY(-4px);
            box-shadow: var(--shadow-lg);
            border-color: var(--accent-primary);
        }

        .collection-card.unowned {
            filter: grayscale(100%);
            opacity: 0.4;
            transition: all var(--transition-base);
        }

        .collection-card.unowned:hover {
            filter: grayscale(0%);
            opacity: 1;
        }

        .card-image-container {
            position: relative;
            cursor: pointer;
            aspect-ratio: 2/3;
        }

        .card-image-container img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        /* Card Controls */
        .card-controls {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: var(--spacing-md);
            background: var(--bg-tertiary);
            border-top: 1px solid var(--border-primary);
        }

        .quantity-controls {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .quantity-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: var(--accent-primary);
            color: white;
            border-radius: var(--radius-md);
            cursor: pointer;
            font-size: 1.2rem;
            display: flex;
            align-items: center;
            justify-content: center;
            transition: all var(--transition-base);
            font-weight: 600;
        }

        .quantity-btn:hover {
            background: var(--accent-secondary);
            transform: scale(1.1);
        }

        .quantity-btn:disabled {
            background: var(--bg-hover);
            color: var(--text-muted);
            cursor: not-allowed;
            transform: none;
        }

        .quantity-display {
            min-width: 40px;
            text-align: center;
            font-weight: 700;
            font-size: 1.1rem;
            padding: var(--spacing-xs) var(--spacing-sm);
            border-radius: var(--radius-sm);
            transition: all var(--transition-base);
            color: var(--text-secondary);
        }

        .collection-card[data-owned="1"] .quantity-display {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .wishlist-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 1.3rem;
            transition: all var(--transition-base);
            border-radius: var(--radius-full);
        }

        .wishlist-btn:hover {
            background: rgba(239, 68, 68, 0.1);
            transform: scale(1.2);
        }

        .wishlist-btn.active {
            color: var(--error);
        }

        .info-btn {
            width: 32px;
            height: 32px;
            border: none;
            background: transparent;
            cursor: pointer;
            font-size: 1rem;
            color: var(--accent-primary);
            transition: all var(--transition-base);
            border-radius: var(--radius-full);
        }

        .info-btn:hover {
            background: rgba(102, 126, 234, 0.1);
            transform: scale(1.1);
        }

        /* Rarity Badge */
        .card-rarity-badge {
            position: absolute;
            bottom: 8px;
            left: 8px;
            padding: 4px 8px;
            border-radius: var(--radius-sm);
            font-size: 0.75rem;
            font-weight: 600;
            text-transform: uppercase;
            box-shadow: var(--shadow-sm);
        }

        .card-rarity-badge.common { background: var(--rarity-common); color: white; }
        .card-rarity-badge.uncommon { background: #3498db; color: white; }
        .card-rarity-badge.rare { background: var(--rarity-rare); color: white; }
        .card-rarity-badge.epic { background: var(--rarity-epic); color: white; }
        .card-rarity-badge.champion { background: var(--rarity-champion); color: white; }
        .card-rarity-badge.legend { background: var(--rarity-legend); color: white; }
        .card-rarity-badge.showcase {
            background: var(--rarity-showcase);
            background-size: 200% 200%;
            animation: shimmer 3s ease infinite;
            color: white;
            font-weight: 700;
            text-shadow: 0 1px 2px rgba(0,0,0,0.3);
        }

        @keyframes shimmer {
            0% { background-position: 0% 50%; }
            50% { background-position: 100% 50%; }
            100% { background-position: 0% 50%; }
        }

        /* Empty State */
        .empty-state {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            text-align: center;
            box-shadow: var(--shadow-sm);
        }

        .empty-state-icon {
            font-size: 4rem;
            margin-bottom: var(--spacing-lg);
            opacity: 0.5;
        }

        /* Responsive */
        @media (max-width: 768px) {
            .collection-controls,
            .collection-status-bar {
                flex-direction: column;
                align-items: stretch;
            }

            .filter-group {
                flex-direction: column;
                width: 100%;
            }

            .filter-select,
            .search-input {
                width: 100%;
            }

            .status-controls {
                flex-direction: column;
                align-items: stretch;
            }

            .view-toggle {
                width: 100%;
            }

            .view-toggle-btn {
                flex: 1;
            }

            .collection-grid {
                grid-template-columns: repeat(auto-fill, minmax(140px, 1fr));
                gap: var(--spacing-md);
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <div class="collection-header">
            <h1>My Collection</h1>

            <div class="collection-stats">
                <div class="stat-card">
                    <span class="stat-value"><?php echo number_format($collection_value, 2); ?>€</span>
                    <span class="stat-label">Collection Value</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value"><?php echo $total_cards_count; ?></span>
                    <span class="stat-label">Total Cards</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value"><?php echo $owned_cards; ?> / <?php echo $total_unique_cards; ?></span>
                    <span class="stat-label">Unique Cards</span>
                </div>
                <div class="stat-card">
                    <span class="stat-value"><?php echo $owned_cards > 0 ? round(($owned_cards / $total_unique_cards) * 100) : 0; ?>%</span>
                    <span class="stat-label">Completion</span>
                </div>
            </div>
        </div>

        <!-- Unified Filter Bar -->
        <div class="collection-controls">
            <div class="filter-group">
                <input type="text" id="searchInput" class="search-input" placeholder="Search cards...">

                <select id="setFilter" class="filter-select">
                    <option value="">All Sets</option>
                    <?php foreach ($sets as $set): ?>
                        <option value="<?php echo htmlspecialchars($set); ?>"><?php echo htmlspecialchars($set); ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="regionFilter" class="filter-select">
                    <option value="">All Regions</option>
                    <?php foreach ($regions as $region): ?>
                        <option value="<?php echo htmlspecialchars($region); ?>"><?php echo htmlspecialchars($region); ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="typeFilter" class="filter-select">
                    <option value="">All Types</option>
                    <?php foreach ($card_types as $type): ?>
                        <option value="<?php echo htmlspecialchars($type); ?>"><?php echo htmlspecialchars($type); ?></option>
                    <?php endforeach; ?>
                </select>

                <select id="rarityFilter" class="filter-select">
                    <option value="">All Rarities</option>
                    <?php foreach ($rarities as $rarity): ?>
                        <option value="<?php echo htmlspecialchars($rarity); ?>"><?php echo htmlspecialchars($rarity); ?></option>
                    <?php endforeach; ?>
                </select>

                <button class="btn btn-secondary btn-small" onclick="resetFilters()">Reset Filters</button>
            </div>
        </div>

        <!-- Collection Status Bar -->
        <div class="collection-status-bar">
            <div class="status-info">
                <span class="status-text">
                    <strong>Cards Owned:</strong> <?php echo $owned_cards; ?> / <?php echo $total_unique_cards; ?>
                    (<?php echo $owned_cards > 0 ? round(($owned_cards / $total_unique_cards) * 100) : 0; ?>%)
                </span>
            </div>

            <div class="status-controls">
                <!-- View Toggle -->
                <div class="view-toggle">
                    <button class="view-toggle-btn" data-view="unowned">Unowned</button>
                    <button class="view-toggle-btn active" data-view="all">All</button>
                    <button class="view-toggle-btn" data-view="owned">Owned</button>
                </div>

                <!-- Wishlist Toggle -->
                <label class="wishlist-toggle">
                    <input type="checkbox" id="wishlistFilter">
                    <span>❤️ Wishlist</span>
                </label>

                <!-- Sort -->
                <select id="sortSelect" class="filter-select">
                    <option value="code-asc">Sort: Card Code (A-Z)</option>
                    <option value="code-desc">Sort: Card Code (Z-A)</option>
                    <option value="name-asc">Sort: Name (A-Z)</option>
                    <option value="name-desc">Sort: Name (Z-A)</option>
                    <option value="energy-asc">Sort: Energy (Low-High)</option>
                    <option value="energy-desc">Sort: Energy (High-Low)</option>
                    <option value="rarity-asc">Sort: Rarity</option>
                </select>
            </div>
        </div>

        <div class="collection-grid" id="collectionGrid">
            <?php foreach ($all_cards as $card): ?>
                <?php
                    $owned_quantity = $user_collection[$card['id']] ?? 0;
                    $is_owned = $owned_quantity > 0;
                    $is_wishlisted = in_array($card['id'], $user_wishlist);
                ?>
                <div class="collection-card <?php echo !$is_owned ? 'unowned' : ''; ?>"
                     data-card-id="<?php echo $card['id']; ?>"
                     data-card-code="<?php echo strtolower($card['card_code'] ?? ''); ?>"
                     data-owned="<?php echo $is_owned ? '1' : '0'; ?>"
                     data-wishlisted="<?php echo $is_wishlisted ? '1' : '0'; ?>"
                     data-set="<?php echo strtolower($card['set_name'] ?? ''); ?>"
                     data-region="<?php echo strtolower($card['region'] ?? ''); ?>"
                     data-rarity="<?php echo strtolower($card['rarity'] ?? ''); ?>"
                     data-type="<?php echo strtolower($card['card_type'] ?? ''); ?>"
                     data-name="<?php echo strtolower($card['name']); ?>"
                     data-energy="<?php echo $card['energy'] ?? 0; ?>">

                    <div class="card-image-container" onclick="showCardDetails(<?php echo htmlspecialchars(json_encode($card)); ?>)">
                        <?php if ($card['card_art_url']): ?>
                            <img src="<?php echo htmlspecialchars($card['card_art_url']); ?>"
                                 alt="<?php echo htmlspecialchars($card['name']); ?>">
                        <?php else: ?>
                            <div style="width: 100%; height: 100%; display: flex; align-items: center; justify-content: center; background: var(--bg-tertiary);">
                                <span><?php echo htmlspecialchars($card['name']); ?></span>
                            </div>
                        <?php endif; ?>

                        <?php if ($card['rarity']): ?>
                            <div class="card-rarity-badge <?php echo strtolower($card['rarity']); ?>">
                                <?php echo htmlspecialchars($card['rarity']); ?>
                            </div>
                        <?php endif; ?>
                    </div>

                    <div class="card-controls">
                        <div class="quantity-controls">
                            <button class="quantity-btn"
                                    onclick="updateQuantity(<?php echo $card['id']; ?>, -1)"
                                    <?php echo $owned_quantity <= 0 ? 'disabled' : ''; ?>>
                                −
                            </button>
                            <span class="quantity-display"><?php echo $owned_quantity; ?></span>
                            <button class="quantity-btn"
                                    onclick="updateQuantity(<?php echo $card['id']; ?>, 1)">
                                +
                            </button>
                        </div>

                        <button class="wishlist-btn <?php echo $is_wishlisted ? 'active' : ''; ?>"
                                onclick="toggleWishlist(<?php echo $card['id']; ?>)">
                            <?php echo $is_wishlisted ? '❤️' : '🤍'; ?>
                        </button>

                        <button class="info-btn" onclick="showCardDetails(<?php echo htmlspecialchars(json_encode($card)); ?>)">
                            ℹ️
                        </button>
                    </div>
                </div>
            <?php endforeach; ?>
        </div>

        <div id="emptyState" class="empty-state" style="display: none;">
            <div class="empty-state-icon">📦</div>
            <h3>No cards found</h3>
            <p>Try adjusting your filters or search query</p>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
    <?php include 'includes/card_detail_modal.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/card_formatter.js"></script>
    <script src="js/cards.js"></script>
    <script src="js/collection.js"></script>
</body>
</html>
