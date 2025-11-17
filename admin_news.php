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

// Get all news posts
$stmt = $pdo->query("
    SELECT n.*, u.username as author_name
    FROM news_posts n
    JOIN users u ON n.author_id = u.id
    ORDER BY n.created_at DESC
");
$news_posts = $stmt->fetchAll();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>News Management - <?php echo SITE_NAME; ?></title>
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

        .posts-table-container {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            overflow: hidden;
        }

        .posts-table {
            width: 100%;
            border-collapse: collapse;
        }

        .posts-table th {
            background: var(--bg-tertiary);
            padding: var(--spacing-md);
            text-align: left;
            font-weight: 600;
            color: var(--text-primary);
            border-bottom: 1px solid var(--border-primary);
        }

        .posts-table td {
            padding: var(--spacing-md);
            border-bottom: 1px solid var(--border-primary);
            color: var(--text-secondary);
        }

        .posts-table tr:last-child td {
            border-bottom: none;
        }

        .posts-table tr:hover {
            background: var(--bg-hover);
        }

        .post-title {
            font-weight: 600;
            color: var(--text-primary);
        }

        .post-status {
            display: inline-block;
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-full);
            font-size: 0.85rem;
            font-weight: 600;
        }

        .post-status.published {
            background: rgba(16, 185, 129, 0.1);
            color: var(--success);
        }

        .post-status.draft {
            background: rgba(245, 158, 11, 0.1);
            color: var(--warning);
        }

        .post-actions {
            display: flex;
            gap: var(--spacing-sm);
        }

        .action-btn {
            padding: 0.25rem 0.75rem;
            border-radius: var(--radius-sm);
            border: 1px solid var(--border-primary);
            background: var(--bg-tertiary);
            color: var(--text-primary);
            text-decoration: none;
            font-size: 0.85rem;
            cursor: pointer;
            transition: all var(--transition-base);
        }

        .action-btn:hover {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        .action-btn.danger:hover {
            background: var(--error);
            border-color: var(--error);
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="admin-container">
        <div class="admin-header">
            <h1>📰 News Management</h1>

            <nav class="admin-nav">
                <a href="admin.php">Dashboard</a>
                <a href="admin_news.php" class="active">News Posts</a>
                <a href="admin_users.php">Users</a>
                <a href="admin_decks.php">Decks</a>
                <a href="admin_cards.php">Cards</a>
            </nav>
        </div>

        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-lg);">
            <h2 style="margin: 0;">All Posts</h2>
            <a href="admin_news_edit.php" class="btn btn-primary">+ Create New Post</a>
        </div>

        <div class="posts-table-container">
            <table class="posts-table">
                <thead>
                    <tr>
                        <th style="width: 50%;">Title</th>
                        <th>Author</th>
                        <th>Status</th>
                        <th>Views</th>
                        <th>Date</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    <?php if (empty($news_posts)): ?>
                        <tr>
                            <td colspan="6" style="text-align: center; padding: var(--spacing-2xl); color: var(--text-muted);">
                                No posts yet. Create your first post!
                            </td>
                        </tr>
                    <?php else: ?>
                        <?php foreach ($news_posts as $post): ?>
                            <tr>
                                <td>
                                    <div class="post-title"><?php echo htmlspecialchars($post['title']); ?></div>
                                    <?php if ($post['excerpt']): ?>
                                        <div style="font-size: 0.85rem; color: var(--text-muted); margin-top: 0.25rem;">
                                            <?php echo htmlspecialchars(substr($post['excerpt'], 0, 80)) . '...'; ?>
                                        </div>
                                    <?php endif; ?>
                                </td>
                                <td><?php echo htmlspecialchars($post['author_name']); ?></td>
                                <td>
                                    <span class="post-status <?php echo $post['is_published'] ? 'published' : 'draft'; ?>">
                                        <?php echo $post['is_published'] ? 'Published' : 'Draft'; ?>
                                    </span>
                                </td>
                                <td><?php echo number_format($post['view_count']); ?></td>
                                <td><?php echo date('M j, Y', strtotime($post['created_at'])); ?></td>
                                <td>
                                    <div class="post-actions">
                                        <?php if ($post['is_published']): ?>
                                            <a href="news.php?slug=<?php echo htmlspecialchars($post['slug']); ?>"
                                               class="action-btn" target="_blank">View</a>
                                        <?php endif; ?>
                                        <a href="admin_news_edit.php?id=<?php echo $post['id']; ?>"
                                           class="action-btn">Edit</a>
                                        <button onclick="deletePost(<?php echo $post['id']; ?>)"
                                                class="action-btn danger">Delete</button>
                                    </div>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    <?php endif; ?>
                </tbody>
            </table>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        async function deletePost(postId) {
            if (!confirm('Are you sure you want to delete this post? This cannot be undone.')) {
                return;
            }

            try {
                const formData = new FormData();
                formData.append('action', 'delete');
                formData.append('post_id', postId);

                const response = await fetch('api/admin_news.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    alert('Post deleted successfully');
                    location.reload();
                } else {
                    alert('Error: ' + data.message);
                }
            } catch (error) {
                console.error('Error:', error);
                alert('Failed to delete post');
            }
        }
    </script>
</body>
</html>
