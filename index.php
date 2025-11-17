<?php
require_once 'config.php';

$pdo = getDB();

// Get featured cards
$stmt = $pdo->query("SELECT * FROM cards WHERE is_featured = TRUE ORDER BY RAND() LIMIT 6");
$featured_cards = $stmt->fetchAll();

$user = getCurrentUser();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo SITE_NAME; ?> - Home</title>

    <!-- Single CSS File -->
    <link rel="stylesheet" href="css/theme.css">
    <style>
        /* News Grid - Horizontal Compact Cards */
        .news-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(320px, 1fr));
            gap: var(--spacing-lg);
            margin-top: var(--spacing-lg);
        }

        /* News Card Specific Styles - Compact Rectangular Cards */
        .news-card {
            position: relative;
            overflow: hidden;
            height: 320px;
            display: flex;
            flex-direction: column;
            max-width: 400px;
        }

        .news-card-image-container {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            height: 160px;
            overflow: hidden;
        }

        .news-card-background {
            width: 100%;
            height: 100%;
            object-fit: cover;
            transition: transform var(--transition-slow);
        }

        .news-card:hover .news-card-background {
            transform: scale(1.1);
        }

        .news-card-overlay {
            position: absolute;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: linear-gradient(to bottom, rgba(0,0,0,0.2), rgba(18, 23, 41, 0.9));
        }

        .news-card-content {
            position: relative;
            margin-top: 160px;
            padding: var(--spacing-sm) var(--spacing-md) var(--spacing-lg);
            background: var(--bg-tertiary);
            z-index: 1;
            flex: 1;
            display: flex;
            flex-direction: column;
            height: 160px;
        }

        .news-card h3 {
            margin-bottom: var(--spacing-xs);
            color: var(--text-primary);
            font-size: 1.1rem;
            line-height: 1.3;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
        }

        .news-card p {
            color: var(--text-secondary);
            line-height: 1.5;
            margin-bottom: var(--spacing-xs);
            font-size: 0.85rem;
            overflow: hidden;
            display: -webkit-box;
            -webkit-line-clamp: 2;
            -webkit-box-orient: vertical;
            flex: 1;
        }

        .news-meta {
            display: flex;
            gap: var(--spacing-sm);
            font-size: 0.75rem;
            color: var(--text-muted);
            margin-bottom: var(--spacing-sm);
        }

        .news-card .btn {
            margin-top: auto;
            padding: 0.5rem 1rem;
            font-size: 0.85rem;
            width: 100%;
        }

        /* Fallback for news cards without images */
        .news-card:not(:has(.news-card-image-container)) {
            height: 320px;
        }

        .news-card:not(:has(.news-card-image-container)) .feature-icon {
            font-size: 2.5rem;
            margin-bottom: var(--spacing-md);
            display: block;
            text-align: center;
            padding-top: var(--spacing-lg);
        }

        .news-card:not(:has(.news-card-image-container)) .news-card-content {
            margin-top: 0;
            padding: var(--spacing-md) var(--spacing-md) var(--spacing-lg);
            height: auto;
        }

        @media (max-width: 1024px) {
            .news-grid {
                grid-template-columns: repeat(auto-fit, minmax(280px, 1fr));
            }
        }

        @media (max-width: 768px) {
            .news-grid {
                grid-template-columns: 1fr;
            }

            .news-card {
                max-width: 100%;
            }
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="container">
        <!-- Hero Section -->
        <section class="hero">
            <div class="hero-content">
                <h1>Welcome to <?php echo SITE_NAME; ?></h1>
                <p>Build your collection, craft powerful decks, and master the game.</p>
                <?php if (!$user): ?>
                    <div class="hero-actions">
                        <a href="register.php" class="btn btn-primary btn-large">
                            Get Started Free
                        </a>
                        <a href="cards2.php" class="btn btn-secondary btn-large">
                            Browse Cards
                        </a>
                    </div>
                <?php else: ?>
                    <div class="hero-actions">
                        <a href="deck_builder.php" class="btn btn-primary btn-large">
                            Build a Deck
                        </a>
                        <a href="collection.php" class="btn btn-secondary btn-large">
                            View Collection
                        </a>
                    </div>
                <?php endif; ?>
            </div>
        </section>


        <!-- Featured Cards Section -->
        <?php if (!empty($featured_cards)): ?>
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">Featured Cards</h2>
                <a href="cards2.php" class="btn btn-secondary btn-small">View All →</a>
            </div>
            <div class="card-gallery">
                <?php foreach ($featured_cards as $card): ?>
                    <div class="gallery-card-item"
                         data-rarity="<?php echo strtolower($card['rarity']); ?>"
                         onclick="showCardDetails(<?php echo htmlspecialchars(json_encode($card), ENT_QUOTES, 'UTF-8'); ?>)">
                        <div class="gallery-card-image">
                            <?php if ($card['card_art_url']): ?>
                                <img src="<?php echo htmlspecialchars($card['card_art_url']); ?>"
                                     alt="<?php echo htmlspecialchars($card['name']); ?>"
                                     draggable="false"
                                     loading="lazy">
                            <?php else: ?>
                                <div class="card-placeholder-full">
                                    <div class="placeholder-content">
                                        <span class="placeholder-name"><?php echo htmlspecialchars($card['name']); ?></span>
                                        <span class="placeholder-type"><?php echo $card['card_type']; ?></span>
                                    </div>
                                </div>
                            <?php endif; ?>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>

        <!-- Latest News Section -->
        <?php
        $stmt = $pdo->query("
            SELECT n.*, u.username as author_name
            FROM news_posts n
            JOIN users u ON n.author_id = u.id
            WHERE n.is_published = TRUE
            ORDER BY n.published_at DESC
            LIMIT 3
        ");
        $latest_news = $stmt->fetchAll();
        ?>

        <?php if (!empty($latest_news)): ?>
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">Latest News</h2>
                <a href="news.php" class="btn btn-secondary btn-small">View All News →</a>
            </div>
            <div class="news-grid">
                <?php foreach ($latest_news as $news): ?>
                    <div class="feature-card news-card" onclick="window.location.href='news.php?slug=<?php echo htmlspecialchars($news['slug']); ?>'">
                        <?php if ($news['featured_image']): ?>
                            <div class="news-card-image-container">
                                <img src="<?php echo htmlspecialchars($news['featured_image']); ?>"
                                     alt="<?php echo htmlspecialchars($news['title']); ?>"
                                     class="news-card-background">
                                <div class="news-card-overlay"></div>
                            </div>
                        <?php else: ?>
                            <span class="feature-icon">📰</span>
                        <?php endif; ?>

                        <div class="news-card-content">
                            <h3><?php echo htmlspecialchars($news['title']); ?></h3>

                            <?php if ($news['excerpt']): ?>
                                <p>
                                    <?php
                                    $excerpt = htmlspecialchars($news['excerpt']);
                                    echo strlen($excerpt) > 80 ? substr($excerpt, 0, 80) . '...' : $excerpt;
                                    ?>
                                </p>
                            <?php endif; ?>

                            <div class="news-meta">
                                <span><?php echo date('M j, Y', strtotime($news['published_at'])); ?></span>
                                <span>•</span>
                                <span><?php echo number_format($news['view_count']); ?> views</span>
                            </div>

                            <a href="news.php?slug=<?php echo htmlspecialchars($news['slug']); ?>"
                               class="btn btn-secondary btn-small"
                               onclick="event.stopPropagation();">
                                Read More
                            </a>
                        </div>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php else: ?>
        <!-- Fallback: Show static placeholder if no news posts exist -->
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">Latest News</h2>
            </div>
            <div class="features-grid">
                <div class="feature-card">
                    <span class="feature-icon">📢</span>
                    <h3>Coming Soon</h3>
                    <p>Stay tuned for the latest updates, announcements, and news about Riftbound!</p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">🏆</span>
                    <h3>Community Updates</h3>
                    <p>Join our growing community and be the first to know about new features and events.</p>
                </div>

                <div class="feature-card">
                    <span class="feature-icon">⚡</span>
                    <h3>Game Updates</h3>
                    <p>Check back regularly for balance changes, new cards, and meta discussions.</p>
                </div>
            </div>
        </section>
        <?php endif; ?>

        <!-- Community Decks Preview - 6 Decks (3 per row) -->
        <?php
        $stmt = $pdo->query("
            SELECT d.*, u.username, c.card_art_url as featured_card_image,
                   (SELECT SUM(quantity) FROM deck_cards WHERE deck_id = d.id) as total_cards
            FROM decks d
            JOIN users u ON d.user_id = u.id
            LEFT JOIN cards c ON d.featured_card_id = c.id
            WHERE d.is_published = TRUE
            ORDER BY d.published_at DESC
            LIMIT 6
        ");
        $community_decks = $stmt->fetchAll();
        ?>

        <?php if (!empty($community_decks)): ?>
        <section class="featured-section">
            <div class="section-header">
                <h2 class="section-title">Community Decks</h2>
                <a href="community_decks.php" class="btn btn-secondary btn-small">View All →</a>
            </div>
            <div class="community-decks-grid">
                <?php foreach ($community_decks as $deck): ?>
                    <div class="feature-card deck-card" onclick="window.location.href='view_deck.php?id=<?php echo $deck['id']; ?>'">
                        <?php if ($deck['featured_card_image']): ?>
                            <div style="margin: -1rem -1rem 1rem -1rem; border-radius: var(--radius-lg) var(--radius-lg) 0 0; overflow: hidden; background: linear-gradient(135deg, rgba(102, 126, 234, 0.2) 0%, rgba(118, 75, 162, 0.2) 100%); padding: 1rem;">
                                <img src="<?php echo htmlspecialchars($deck['featured_card_image']); ?>"
                                     alt="Featured card"
                                     style="width: 100%; border-radius: var(--radius-md);">
                            </div>
                        <?php endif; ?>

                        <h3><?php echo htmlspecialchars($deck['deck_name']); ?></h3>

                        <p class="deck-meta" style="color: var(--text-muted); margin-bottom: 0.5rem; font-size: 0.85rem;">
                            by <strong><?php echo htmlspecialchars($deck['username']); ?></strong>
                        </p>

                        <?php if ($deck['description']): ?>
                            <p style="font-size: 0.85rem; line-height: 1.5; color: var(--text-secondary);">
                                <?php
                                $desc = htmlspecialchars($deck['description']);
                                echo strlen($desc) > 80 ? substr($desc, 0, 80) . '...' : $desc;
                                ?>
                            </p>
                        <?php endif; ?>

                        <div style="display: flex; justify-content: space-between; margin: 1rem 0 0.5rem; padding-top: 0.75rem; border-top: 1px solid var(--border-primary); font-size: 0.8rem; color: var(--text-muted);">
                            <span>📊 <?php echo $deck['total_cards']; ?> cards</span>
                            <span>❤️ <?php echo $deck['like_count']; ?> likes</span>
                        </div>

                        <a href="view_deck.php?id=<?php echo $deck['id']; ?>" class="btn btn-secondary btn-small" onclick="event.stopPropagation();">
                            View Deck
                        </a>
                    </div>
                <?php endforeach; ?>
            </div>
        </section>
        <?php endif; ?>
    </main>

    <!-- Card Detail Modal -->
    <?php include 'includes/card_detail_modal.php'; ?>

    <?php include 'includes/footer.php'; ?>

    <script src="js/main.js"></script>
    <script src="js/card_formatter.js"></script>
    <script src="js/cards.js"></script>
</body>
</html>
