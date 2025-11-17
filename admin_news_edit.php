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

// Load existing post if editing
$post = null;
$post_id = intval($_GET['id'] ?? 0);

if ($post_id > 0) {
    $stmt = $pdo->prepare("SELECT * FROM news_posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $post = $stmt->fetch();

    if (!$post) {
        header('Location: admin_news.php');
        exit;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?php echo $post ? 'Edit' : 'Create'; ?> Post - <?php echo SITE_NAME; ?></title>
    <link rel="stylesheet" href="css/theme.css">
    <style>
        .admin-container {
            max-width: 1200px;
            margin: 0 auto;
            padding: var(--spacing-xl);
        }

        .editor-container {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
        }

        .form-group {
            margin-bottom: var(--spacing-lg);
        }

        .form-group label {
            display: block;
            margin-bottom: var(--spacing-sm);
            font-weight: 600;
            color: var(--text-primary);
        }

        .form-group input[type="text"],
        .form-group input[type="url"],
        .form-group textarea {
            width: 100%;
            padding: var(--spacing-md);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-md);
            background: var(--bg-tertiary);
            color: var(--text-primary);
            font-family: inherit;
            font-size: 1rem;
        }

        .form-group textarea {
            resize: vertical;
            font-family: 'Courier New', monospace;
        }

        .form-group small {
            display: block;
            margin-top: var(--spacing-xs);
            color: var(--text-muted);
            font-size: 0.85rem;
        }

        .bbcode-toolbar {
            display: flex;
            gap: var(--spacing-xs);
            flex-wrap: wrap;
            margin-bottom: var(--spacing-sm);
            padding: var(--spacing-sm);
            background: var(--bg-tertiary);
            border-radius: var(--radius-md);
        }

        .bbcode-btn {
            padding: var(--spacing-sm) var(--spacing-md);
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-sm);
            color: var(--text-primary);
            cursor: pointer;
            font-size: 0.85rem;
            transition: all var(--transition-base);
        }

        .bbcode-btn:hover {
            background: var(--accent-primary);
            color: white;
            border-color: var(--accent-primary);
        }

        .editor-actions {
            display: flex;
            gap: var(--spacing-md);
            margin-top: var(--spacing-xl);
        }

        .preview-container {
            background: var(--bg-secondary);
            border: 1px solid var(--border-primary);
            border-radius: var(--radius-lg);
            padding: var(--spacing-xl);
            margin-top: var(--spacing-xl);
        }

        .preview-content {
            color: var(--text-secondary);
            line-height: 1.8;
        }

        .preview-content h2 {
            color: var(--text-primary);
            margin-top: var(--spacing-xl);
        }

        .preview-content img {
            max-width: 100%;
            height: auto;
            border-radius: var(--radius-md);
            margin: var(--spacing-lg) 0;
        }

        .preview-content a {
            color: var(--accent-primary);
        }

        .checkbox-group {
            display: flex;
            align-items: center;
            gap: var(--spacing-sm);
        }

        .checkbox-group input[type="checkbox"] {
            width: 20px;
            height: 20px;
            cursor: pointer;
        }
    </style>
</head>
<body>
    <?php include 'includes/header.php'; ?>

    <main class="admin-container">
        <div style="display: flex; justify-content: space-between; align-items: center; margin-bottom: var(--spacing-xl);">
            <h1><?php echo $post ? 'Edit' : 'Create'; ?> Post</h1>
            <a href="admin_news.php" class="btn btn-secondary">← Back to Posts</a>
        </div>

        <div id="message" class="message hidden"></div>

        <form id="postForm" class="editor-container">
            <input type="hidden" name="post_id" value="<?php echo $post ? $post['id'] : ''; ?>">

            <div class="form-group">
                <label for="title">Title</label>
                <input type="text" id="title" name="title" required
                       value="<?php echo $post ? htmlspecialchars($post['title']) : ''; ?>">
            </div>

            <div class="form-group">
                <label for="slug">URL Slug</label>
                <input type="text" id="slug" name="slug" required pattern="[a-z0-9-]+"
                       value="<?php echo $post ? htmlspecialchars($post['slug']) : ''; ?>">
                <small>Lowercase letters, numbers, and hyphens only. Example: new-set-released</small>
            </div>

            <div class="form-group">
                <label for="excerpt">Excerpt (Short Description)</label>
                <textarea id="excerpt" name="excerpt" rows="3" maxlength="500"><?php echo $post ? htmlspecialchars($post['excerpt']) : ''; ?></textarea>
                <small>Brief summary (max 500 characters). Shown in news previews.</small>
            </div>

            <div class="form-group">
                <label for="featured_image">Featured Image URL</label>
                <input type="url" id="featured_image" name="featured_image"
                       value="<?php echo $post ? htmlspecialchars($post['featured_image']) : ''; ?>">
                <small>Optional: Direct URL to an image (e.g., https://example.com/image.jpg)</small>
            </div>

            <div class="form-group">
                <label for="content">Content (BBCode)</label>

                <div class="bbcode-toolbar">
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('b')">Bold</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('i')">Italic</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('u')">Underline</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('h2')">Heading</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('url')">Link</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('img')">Image</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('quote')">Quote</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('code')">Code</button>
                    <button type="button" class="bbcode-btn" onclick="insertBBCode('list')">List</button>
                </div>

                <textarea id="content" name="content" rows="20" required><?php echo $post ? htmlspecialchars($post['content']) : ''; ?></textarea>

                <small>
                    <strong>BBCode Guide:</strong>
                    [b]bold[/b], [i]italic[/i], [u]underline[/u], [h2]Heading[/h2],
                    [url=https://example.com]link text[/url], [img]image-url[/img],
                    [quote]quoted text[/quote], [code]code snippet[/code],
                    [list][*]item 1[*]item 2[/list]
                </small>
            </div>

            <div class="form-group">
                <div class="checkbox-group">
                    <input type="checkbox" id="is_published" name="is_published" value="1"
                           <?php echo ($post && $post['is_published']) ? 'checked' : ''; ?>>
                    <label for="is_published" style="margin: 0; font-weight: normal;">Publish immediately</label>
                </div>
            </div>

            <div class="editor-actions">
                <button type="submit" class="btn btn-primary">
                    <?php echo $post ? 'Update' : 'Create'; ?> Post
                </button>
                <button type="button" class="btn btn-secondary" onclick="showPreview()">
                    Preview
                </button>
                <a href="admin_news.php" class="btn btn-secondary">Cancel</a>
            </div>
        </form>

        <div id="previewContainer" class="preview-container" style="display: none;">
            <h2>Preview</h2>
            <hr style="border: none; border-top: 1px solid var(--border-primary); margin: var(--spacing-lg) 0;">
            <div id="previewContent" class="preview-content"></div>
        </div>
    </main>

    <?php include 'includes/footer.php'; ?>

    <script>
        // Auto-generate slug from title
        document.getElementById('title').addEventListener('input', function() {
            if (!document.getElementById('slug').value ||
                document.getElementById('slug').dataset.auto !== 'false') {
                const slug = this.value
                    .toLowerCase()
                    .replace(/[^a-z0-9\s-]/g, '')
                    .replace(/\s+/g, '-')
                    .replace(/-+/g, '-')
                    .substring(0, 100);
                document.getElementById('slug').value = slug;
            }
        });

        document.getElementById('slug').addEventListener('input', function() {
            this.dataset.auto = 'false';
        });

        // BBCode insertion
        function insertBBCode(tag) {
            const textarea = document.getElementById('content');
            const start = textarea.selectionStart;
            const end = textarea.selectionEnd;
            const selectedText = textarea.value.substring(start, end);

            let insertion = '';

            switch(tag) {
                case 'url':
                    const url = prompt('Enter URL:');
                    if (url) {
                        insertion = `[url=${url}]${selectedText || 'link text'}[/url]`;
                    }
                    break;
                case 'img':
                    const imgUrl = prompt('Enter image URL:');
                    if (imgUrl) {
                        insertion = `[img]${imgUrl}[/img]`;
                    }
                    break;
                case 'list':
                    insertion = `[list]\n[*]${selectedText || 'item 1'}\n[*]item 2\n[*]item 3\n[/list]`;
                    break;
                default:
                    insertion = `[${tag}]${selectedText || 'text'}[/${tag}]`;
            }

            if (insertion) {
                textarea.value = textarea.value.substring(0, start) + insertion + textarea.value.substring(end);
                textarea.focus();
                textarea.setSelectionRange(start + insertion.length, start + insertion.length);
            }
        }

        // Preview
        function showPreview() {
            const title = document.getElementById('title').value;
            const content = document.getElementById('content').value;
            const previewContainer = document.getElementById('previewContainer');
            const previewContent = document.getElementById('previewContent');

            // Convert BBCode to HTML
            const html = parseBBCode(content);

            previewContent.innerHTML = `<h1>${escapeHtml(title)}</h1>${html}`;
            previewContainer.style.display = 'block';
            previewContainer.scrollIntoView({ behavior: 'smooth' });
        }

        function parseBBCode(text) {
            // Escape HTML first
            text = escapeHtml(text);

            // Convert BBCode to HTML
            text = text.replace(/\[b\](.*?)\[\/b\]/gi, '<strong>$1</strong>');
            text = text.replace(/\[i\](.*?)\[\/i\]/gi, '<em>$1</em>');
            text = text.replace(/\[u\](.*?)\[\/u\]/gi, '<u>$1</u>');
            text = text.replace(/\[h2\](.*?)\[\/h2\]/gi, '<h2>$1</h2>');
            text = text.replace(/\[url=(.*?)\](.*?)\[\/url\]/gi, '<a href="$1" target="_blank" rel="noopener">$2</a>');
            text = text.replace(/\[img\](.*?)\[\/img\]/gi, '<img src="$1" alt="">');
            text = text.replace(/\[quote\](.*?)\[\/quote\]/gi, '<blockquote style="border-left: 4px solid var(--accent-primary); padding-left: var(--spacing-lg); margin: var(--spacing-lg) 0; color: var(--text-muted);">$1</blockquote>');
            text = text.replace(/\[code\](.*?)\[\/code\]/gi, '<pre style="background: var(--bg-tertiary); padding: var(--spacing-md); border-radius: var(--radius-sm); overflow-x: auto;"><code>$1</code></pre>');
            text = text.replace(/\[list\](.*?)\[\/list\]/gi, function(match, p1) {
                const items = p1.replace(/\[\*\]/g, '<li>').replace(/<li>\s*/g, '<li>');
                return '<ul style="margin: var(--spacing-lg) 0; padding-left: var(--spacing-xl);">' + items + '</ul>';
            });

            // Convert line breaks
            text = text.replace(/\n/g, '<br>');

            return text;
        }

        function escapeHtml(text) {
            const div = document.createElement('div');
            div.textContent = text;
            return div.innerHTML;
        }

        // Form submission
        document.getElementById('postForm').addEventListener('submit', async function(e) {
            e.preventDefault();

            const formData = new FormData(this);
            formData.append('action', <?php echo $post ? "'update'" : "'create'"; ?>);

            try {
                const response = await fetch('api/admin_news.php', {
                    method: 'POST',
                    body: formData
                });

                const data = await response.json();

                if (data.success) {
                    showMessage(data.message, 'success');
                    setTimeout(() => {
                        window.location.href = 'admin_news.php';
                    }, 1500);
                } else {
                    showMessage(data.message, 'error');
                }
            } catch (error) {
                console.error('Error:', error);
                showMessage('An error occurred', 'error');
            }
        });

        function showMessage(message, type) {
            const messageDiv = document.getElementById('message');
            messageDiv.textContent = message;
            messageDiv.className = 'message ' + type;
            messageDiv.classList.remove('hidden');
            messageDiv.scrollIntoView({ behavior: 'smooth' });

            setTimeout(() => {
                messageDiv.classList.add('hidden');
            }, 5000);
        }
    </script>
</body>
</html>
