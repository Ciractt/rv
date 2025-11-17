<?php
require_once 'config.php';
requireLogin();

$pdo = getDB();
$user = getCurrentUser();

// Check if user is admin
if (!$user['is_admin'] && !$user['is_moderator']) {
    header('Location: index.php');
    exit;
}

// Get dashboard statistics
try {
    // User stats
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM users");
    $total_users = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $users_this_week = $stmt->fetch()['COUNT(*)'];

    $stmt = $pdo->query("SELECT COUNT(*) FROM users WHERE last_login >= DATE_SUB(NOW(), INTERVAL 1 DAY)");
    $active_today = $stmt->fetch()['COUNT(*)'];

    // Deck stats
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM decks");
    $total_decks = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(*) FROM decks WHERE is_published = TRUE");
    $published_decks = $stmt->fetch()['COUNT(*)'];

    $stmt = $pdo->query("SELECT COUNT(*) FROM decks WHERE created_at >= DATE_SUB(NOW(), INTERVAL 7 DAY)");
    $decks_this_week = $stmt->fetch()['COUNT(*)'];

    // Card stats
    $stmt = $pdo->query("SELECT COUNT(*) as total FROM cards");
    $total_cards = $stmt->fetch()['total'];

    $stmt = $pdo->query("SELECT COUNT(DISTINCT card_id) FROM user_collections");
    $cards_in_collections = $stmt->fetch()['COUNT(DISTINCT card_id)'];

    $stmt = $pdo->query("SELECT SUM(quantity) as total FROM user_collections");
    $total_cards_owned = $stmt->fetch()['total'] ?? 0;

    // News stats
    $stmt = $pdo->query("SELECT COUNT(*) FROM news_posts WHERE is_published = TRUE");
    $published_news = $stmt->fetch()['COUNT(*)'];

} catch (Exception $e) {
    error_log('Admin stats error: ' . $e->getMessage());
    $total_users = $users_this_week = $active_today = 0;
    $total_decks = $published_decks = $decks_this_week = 0;
    $total_cards = $cards_in_collections = $total_cards_owned = 0;
    $published_news = 0;
}

// Get recent activity
$recent_users = $pdo->query("SELECT id, username, created_at FROM users ORDER BY created_at DESC LIMIT 5")->fetchAll();
$recent_decks = $pdo->query("SELECT d.id, d.deck_name, d.created_at, u.username FROM decks d JOIN users u ON d.user_id = u.id ORDER BY d.created_at DESC LIMIT 5")->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Panel - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/theme.css">
    <style>
        .admin-container {
            max-width: 1400px;
            margin: 0 auto;
            padding: var(--spacing-xl);
        }

        .admin-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            margin-bottom: var(--spacing-xl);
        }

        .admin-header h1 {
            margin: 0 0 var(--spacing-sm) 0;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .admin-nav {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-lg);
            flex-wrap: wrap;
        }

        .admin-nav a {
            padding: var(--spacing-sm) var(--spacing-lg);
            background: var(--bg-tertiary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-md);
            text-decoration: none;
            color: var(--text-primary);
            transition: all var(--transition-base);
        }

        .admin-nav a:hover, .admin-nav a.active {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: var(--spacing-lg);
            margin-bottom: var(--spacing-xl);
        }

        .stat-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            transition: all var(--transition-base);
        }

        .stat-card:hover {
            border-color: var(--accent-primary);
            box-shadow: var(--shadow-glow);
        }

        .stat-card h3 {
            font-size: 0.9rem;
            color: var(--text-muted);
            margin: 0 0 var(--spacing-md) 0;
            text-transform: uppercase;
            letter-spacing: 0.5px;
        }

        .stat-value {
            font-size: 2.5rem;
            font-weight: 800;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
            margin-bottom: var(--spacing-xs);
        }

        .stat-change {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        .stat-change.positive {
            color: var(--success);
        }

        .content-grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: var(--spacing-xl);
        }

        .activity-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
        }

        .activity-card h2 {
            margin: 0 0 var(--spacing-lg) 0;
            font-size: 1.3rem;
        }

        .activity-list {
            list-style: none;
            padding: 0;
            margin: 0;
        }

        .activity-item {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--border-primary);
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .activity-item:last-child {
            border-bottom: none;
        }

        .activity-item:hover {
            background: var(--bg-hover);
        }

        .activity-info {
            flex: 1;
        }

        .activity-title {
            font-weight: 600;
            color: var(--text-primary);
            margin-bottom: var(--spacing-xs);
        }

        .activity-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
        }

        .activity-time {
            font-size: 0.85rem;
            color: var(--text-secondary);
        }

        @media (max-width: 768px) {
            .content-grid {
                grid-template-columns: 1fr;
            }

            .stats-grid {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="admin-container">
        <div class="admin-header">
            <h1>🛡️ Admin Panel</h1>
            <p style="color: var(--text-secondary); margin: 0;">Welcome back, <?php echo htmlspecialchars($user['username']); ?>!</p>

            <nav class="admin-nav">
                <a href="admin.php" class="active">Dashboard</a>
                <a href="admin_news.php">News Posts</a>
                <a href="admin_users.php">Users</a>
                <a href="admin_decks.php">Decks</a>
                <a href="admin_cards.php">Cards</a>
            </nav>
        </div>

        <h2 style="margin-bottom: var(--spacing-lg);">📊 Dashboard Statistics</h2>

        <div class="stats-grid">
            <div class="stat-card">
                <h3>Total Users</h3>
                <div class="stat-value"><?php echo number_format($total_users); ?></div>
                <div class="stat-change positive">+<?php echo $users_this_week; ?> this week</div>
            </div>

            <div class="stat-card">
                <h3>Active Today</h3>
                <div class="stat-value"><?php echo number_format($active_today); ?></div>
                <div class="stat-change"><?php echo $total_users > 0 ? round(($active_today / $total_users) * 100) : 0; ?>% of users</div>
            </div>

            <div class="stat-card">
                <h3>Total Decks</h3>
                <div class="stat-value"><?php echo number_format($total_decks); ?></div>
                <div class="stat-change positive">+<?php echo $decks_this_week; ?> this week</div>
            </div>

            <div class="stat-card">
                <h3>Published Decks</h3>
                <div class="stat-value"><?php echo number_format($published_decks); ?></div>
                <div class="stat-change"><?php echo $total_decks > 0 ? round(($published_decks / $total_decks) * 100) : 0; ?>% published</div>
            </div>

            <div class="stat-card">
                <h3>Total Cards</h3>
                <div class="stat-value"><?php echo number_format($total_cards); ?></div>
                <div class="stat-change"><?php echo number_format($cards_in_collections); ?> in collections</div>
            </div>

            <div class="stat-card">
                <h3>Cards Owned</h3>
                <div class="stat-value"><?php echo number_format($total_cards_owned); ?></div>
                <div class="stat-change">Across all users</div>
            </div>

            <div class="stat-card">
                <h3>News Posts</h3>
                <div class="stat-value"><?php echo number_format($published_news); ?></div>
                <div class="stat-change">Published articles</div>
            </div>

            <div class="stat-card">
                <h3>Collection Rate</h3>
                <div class="stat-value"><?php echo $total_cards > 0 ? round(($cards_in_collections / $total_cards) * 100) : 0; ?>%</div>
                <div class="stat-change">Cards collected</div>
            </div>
        </div>

        <h2 style="margin-bottom: var(--spacing-lg);">📈 Recent Activity</h2>

        <div class="content-grid">
            <div class="activity-card">
                <h2>Recent Users</h2>
                <ul class="activity-list">
                    <?php foreach ($recent_users as $ru): ?>
                        <li class="activity-item">
                            <div class="activity-info">
                                <div class="activity-title"><?php echo htmlspecialchars($ru['username']); ?></div>
                                <div class="activity-meta">User ID: <?php echo $ru['id']; ?></div>
                            </div>
                            <div class="activity-time">
                                <?php echo date('M j, Y', strtotime($ru['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>

            <div class="activity-card">
                <h2>Recent Decks</h2>
                <ul class="activity-list">
                    <?php foreach ($recent_decks as $rd): ?>
                        <li class="activity-item">
                            <div class="activity-info">
                                <div class="activity-title">
                                    <a href="view_deck.php?id=<?php echo $rd['id']; ?>" style="color: var(--accent-primary); text-decoration: none;">
                                        <?php echo htmlspecialchars($rd['deck_name']); ?>
                                    </a>
                                </div>
                                <div class="activity-meta">by <?php echo htmlspecialchars($rd['username']); ?></div>
                            </div>
                            <div class="activity-time">
                                <?php echo date('M j, Y', strtotime($rd['created_at'])); ?>
                            </div>
                        </li>
                    <?php endforeach; ?>
                </ul>
            </div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
