<?php
require_once 'config.php';

$pdo = getDB();

// Get slug from URL
$slug = $_GET['slug'] ?? '';

if (empty($slug)) {
    // Show news list
    $stmt = $pdo->query("
        SELECT n.*, u.username as author_name
        FROM news_posts n
        JOIN users u ON n.author_id = u.id
        WHERE n.is_published = TRUE
        ORDER BY n.published_at DESC
    ");
    $news_posts = $stmt->fetchAll();
    $viewing_list = true;
} else {
    // Show single post
    $stmt = $pdo->prepare("
        SELECT n.*, u.username as author_name
        FROM news_posts n
        JOIN users u ON n.author_id = u.id
        WHERE n.slug = ? AND n.is_published = TRUE
    ");
    $stmt->execute([$slug]);
    $post = $stmt->fetch();

    if (!$post) {
        header('Location: news.php');
        exit;
    }

    // Increment view count
    $stmt = $pdo->prepare("UPDATE news_posts SET view_count = view_count + 1 WHERE id = ?");
    $stmt->execute([$post['id']]);

    $viewing_list = false;
}

// BBCode parser function
function parseBBCode($text) {
    // Convert special characters
    $text = htmlspecialchars($text, ENT_QUOTES, 'UTF-8');

    // Bold
    $text = preg_replace('/\[b\](.*?)\[\/b\]/is', '<strong>$1</strong>', $text);

    // Italic
    $text = preg_replace('/\[i\](.*?)\[\/i\]/is', '<em>$1</em>', $text);

    // Underline
    $text = preg_replace('/\[u\](.*?)\[\/u\]/is', '<u>$1</u>', $text);

    // Headings
    $text = preg_replace('/\[h2\](.*?)\[\/h2\]/is', '<h2>$1</h2>', $text);

    // Links
    $text = preg_replace('/\[url=(.*?)\](.*?)\[\/url\]/is', '<a href="$1" target="_blank" rel="noopener">$2</a>', $text);

    // Images
    $text = preg_replace('/\[img\](.*?)\[\/img\]/is', '<img src="$1" alt="" style="max-width: 100%; height: auto; border-radius: 8px; margin: 1.5rem 0;">', $text);

    // Quote
    $text = preg_replace('/\[quote\](.*?)\[\/quote\]/is', '<blockquote style="border-left: 4px solid var(--accent-primary); padding-left: var(--spacing-lg); margin: var(--spacing-lg) 0; color: var(--text-muted); font-style: italic;">$1</blockquote>', $text);

    // Code
    $text = preg_replace('/\[code\](.*?)\[\/code\]/is', '<pre style="background: var(--bg-tertiary); padding: var(--spacing-md); border-radius: var(--radius-sm); overflow-x: auto; font-family: \'Courier New\', monospace;"><code>$1</code></pre>', $text);

    // Lists
    $text = preg_replace_callback('/\[list\](.*?)\[\/list\]/is', function($matches) {
        $items = preg_replace('/\[\*\]/i', '</li><li>', $matches[1]);
        $items = '<li>' . $items . '</li>';
        $items = str_replace('<li></li>', '', $items);
        return '<ul style="margin: var(--spacing-lg) 0; padding-left: var(--spacing-xl);">' . $items . '</ul>';
    }, $text);

    // Convert line breaks to <br>
    $text = nl2br($text);

    return $text;
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $viewing_list ? 'News' : htmlspecialchars($post['title']); ?> - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/theme.css">
    <style>
        .news-container {
            max-width: 900px;
            margin: 0 auto;
            padding: var(--spacing-2xl) var(--spacing-xl);
        }

        .news-header {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
            margin-bottom: var(--spacing-2xl);
            text-align: center;
        }

        .news-header h1 {
            margin: 0 0 var(--spacing-md) 0;
            font-size: 2.5rem;
            background: var(--accent-gradient);
            -webkit-background-clip: text;
            -webkit-text-fill-color: transparent;
            background-clip: text;
        }

        .news-list {
            display: grid;
            gap: var(--spacing-xl);
        }

        .news-card {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            overflow: hidden;
            transition: all var(--transition-base);
            text-decoration: none;
            color: inherit;
            display: block;
        }

        .news-card:hover {
            border-color: var(--accent-primary);
            box-shadow: var(--shadow-glow);
            transform: translateY(-4px);
        }

        .news-card-image {
            width: 100%;
            height: 250px;
            object-fit: cover;
        }

        .news-card-content {
            padding: var(--spacing-xl);
        }

        .news-card-title {
            font-size: 1.5rem;
            font-weight: 700;
            color: var(--text-primary);
            margin-bottom: var(--spacing-sm);
        }

        .news-card-meta {
            font-size: 0.85rem;
            color: var(--text-muted);
            margin-bottom: var(--spacing-md);
        }

        .news-card-excerpt {
            color: var(--text-secondary);
            line-height: 1.6;
        }

        .news-content {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-2xl);
        }

        .post-header {
            margin-bottom: var(--spacing-2xl);
            padding-bottom: var(--spacing-xl);
            border-bottom: 2px solid var(--border-primary);
        }

        .post-title {
            font-size: 2.5rem;
            font-weight: 800;
            color: var(--text-primary);
            margin-bottom: var(--spacing-md);
            line-height: 1.2;
        }

        .post-meta {
            display: flex;
            gap: var(--spacing-lg);
            color: var(--text-muted);
            font-size: 0.9rem;
        }

        .post-featured-image {
            width: 100%;
            max-height: 500px;
            object-fit: cover;
            border-radius: var(--radius-lg);
            margin-bottom: var(--spacing-2xl);
        }

        .post-content {
            color: var(--text-secondary);
            line-height: 1.8;
            font-size: 1.05rem;
        }

        .post-content h2 {
            color: var(--text-primary);
            margin: var(--spacing-2xl) 0 var(--spacing-lg) 0;
            font-size: 1.8rem;
        }

        .post-content p {
            margin-bottom: var(--spacing-lg);
        }

        .back-link {
            display: inline-flex;
            align-items: center;
            gap: var(--spacing-sm);
            color: var(--accent-primary);
            text-decoration: none;
            margin-bottom: var(--spacing-xl);
            transition: all var(--transition-base);
        }

        .back-link:hover {
            color: var(--accent-secondary);
            transform: translateX(-4px);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="news-container">
        <?php if ($viewing_list): ?>
            <!-- News List View -->
            <div class="news-header">
                <h1>📰 News</h1>
                <p style="color: var(--text-secondary); margin: 0;">
                    Stay up to date with the latest Riftbound news and updates
                </p>
            </div>

            <div class="news-list">
                <?php if (empty($news_posts)): ?>
                    <div style="text-align: center; padding: var(--spacing-2xl); color: var(--text-muted);">
                        No news posts yet. Check back soon!
                    </div>
                <?php else: ?>
                    <?php foreach ($news_posts as $np): ?>
                        <a href="news.php?slug=<?php echo htmlspecialchars($np['slug']); ?>" class="news-card">
                            <?php if ($np['featured_image']): ?>
                                <img src="<?php echo htmlspecialchars($np['featured_image']); ?>"
                                     alt="<?php echo htmlspecialchars($np['title']); ?>"
                                     class="news-card-image">
                            <?php endif; ?>

                            <div class="news-card-content">
                                <h2 class="news-card-title"><?php echo htmlspecialchars($np['title']); ?></h2>

                                <div class="news-card-meta">
                                    By <?php echo htmlspecialchars($np['author_name']); ?> •
                                    <?php echo date('F j, Y', strtotime($np['published_at'])); ?> •
                                    <?php echo number_format($np['view_count']); ?> views
                                </div>

                                <?php if ($np['excerpt']): ?>
                                    <p class="news-card-excerpt"><?php echo htmlspecialchars($np['excerpt']); ?></p>
                                <?php endif; ?>
                            </div>
                        </a>
                    <?php endforeach; ?>
                <?php endif; ?>
            </div>

        <?php else: ?>
            <!-- Single Post View -->
            <a href="news.php" class="back-link">← Back to News</a>

            <article class="news-content">
                <header class="post-header">
                    <h1 class="post-title"><?php echo htmlspecialchars($post['title']); ?></h1>

                    <div class="post-meta">
                        <span>By <?php echo htmlspecialchars($post['author_name']); ?></span>
                        <span>•</span>
                        <span><?php echo date('F j, Y', strtotime($post['published_at'])); ?></span>
                        <span>•</span>
                        <span><?php echo number_format($post['view_count']); ?> views</span>
                    </div>
                </header>

                <?php if ($post['featured_image']): ?>
                    <img src="<?php echo htmlspecialchars($post['featured_image']); ?>"
                         alt="<?php echo htmlspecialchars($post['title']); ?>"
                         class="post-featured-image">
                <?php endif; ?>

                <div class="post-content">
                    <?php echo parseBBCode($post['content']); ?>
                </div>
            </article>
        <?php endif; ?>
    </main>

    <?php include 'includes/footer.php'; ?>
</body>
</html>
