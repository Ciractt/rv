<?php
require_once '../config.php';
requireLogin();

header('Content-Type: application/json');

$pdo = getDB();
$user = getCurrentUser();

// Check if user is admin
if (!$user['is_admin'] && !$user['is_moderator']) {
    echo json_encode(['success' => false, 'message' => 'Unauthorized']);
    exit;
}

$action = $_POST['action'] ?? '';

try {
    switch ($action) {
        case 'create':
            createPost($pdo, $user, $_POST);
            break;

        case 'update':
            updatePost($pdo, $user, $_POST);
            break;

        case 'delete':
            deletePost($pdo, $_POST);
            break;

        default:
            throw new Exception('Invalid action');
    }
} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}

function createPost($pdo, $user, $data) {
    $title = trim($data['title'] ?? '');
    $slug = trim($data['slug'] ?? '');
    $excerpt = trim($data['excerpt'] ?? '');
    $content = trim($data['content'] ?? '');
    $featured_image = trim($data['featured_image'] ?? '');
    $is_published = isset($data['is_published']) ? 1 : 0;

    // Validation
    if (empty($title) || empty($slug) || empty($content)) {
        throw new Exception('Title, slug, and content are required');
    }

    // Validate slug format
    if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
        throw new Exception('Slug must contain only lowercase letters, numbers, and hyphens');
    }

    // Check if slug already exists
    $stmt = $pdo->prepare("SELECT id FROM news_posts WHERE slug = ?");
    $stmt->execute([$slug]);
    if ($stmt->fetch()) {
        throw new Exception('A post with this slug already exists');
    }

    // Insert post
    $stmt = $pdo->prepare("
        INSERT INTO news_posts (
            title, slug, content, excerpt, author_id,
            featured_image, is_published, published_at,
            created_at, updated_at
        ) VALUES (?, ?, ?, ?, ?, ?, ?, ?, NOW(), NOW())
    ");

    $published_at = $is_published ? date('Y-m-d H:i:s') : null;

    $stmt->execute([
        $title,
        $slug,
        $content,
        $excerpt,
        $user['id'],
        $featured_image ?: null,
        $is_published,
        $published_at
    ]);

    echo json_encode([
        'success' => true,
        'message' => 'Post created successfully',
        'post_id' => $pdo->lastInsertId()
    ]);
}

function updatePost($pdo, $user, $data) {
    $post_id = intval($data['post_id'] ?? 0);
    $title = trim($data['title'] ?? '');
    $slug = trim($data['slug'] ?? '');
    $excerpt = trim($data['excerpt'] ?? '');
    $content = trim($data['content'] ?? '');
    $featured_image = trim($data['featured_image'] ?? '');
    $is_published = isset($data['is_published']) ? 1 : 0;

    if ($post_id <= 0) {
        throw new Exception('Invalid post ID');
    }

    // Validation
    if (empty($title) || empty($slug) || empty($content)) {
        throw new Exception('Title, slug, and content are required');
    }

    // Validate slug format
    if (!preg_match('/^[a-z0-9-]+$/', $slug)) {
        throw new Exception('Slug must contain only lowercase letters, numbers, and hyphens');
    }

    // Check if slug already exists (excluding current post)
    $stmt = $pdo->prepare("SELECT id FROM news_posts WHERE slug = ? AND id != ?");
    $stmt->execute([$slug, $post_id]);
    if ($stmt->fetch()) {
        throw new Exception('A post with this slug already exists');
    }

    // Get current post to check if we're publishing for the first time
    $stmt = $pdo->prepare("SELECT is_published FROM news_posts WHERE id = ?");
    $stmt->execute([$post_id]);
    $current_post = $stmt->fetch();

    if (!$current_post) {
        throw new Exception('Post not found');
    }

    // Set published_at if publishing for the first time
    $published_at = null;
    if ($is_published && !$current_post['is_published']) {
        $published_at = date('Y-m-d H:i:s');
    }

    // Update post
    if ($published_at) {
        $stmt = $pdo->prepare("
            UPDATE news_posts
            SET title = ?, slug = ?, content = ?, excerpt = ?,
                featured_image = ?, is_published = ?, published_at = ?,
                updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $title, $slug, $content, $excerpt,
            $featured_image ?: null, $is_published, $published_at,
            $post_id
        ]);
    } else {
        $stmt = $pdo->prepare("
            UPDATE news_posts
            SET title = ?, slug = ?, content = ?, excerpt = ?,
                featured_image = ?, is_published = ?, updated_at = NOW()
            WHERE id = ?
        ");
        $stmt->execute([
            $title, $slug, $content, $excerpt,
            $featured_image ?: null, $is_published,
            $post_id
        ]);
    }

    echo json_encode([
        'success' => true,
        'message' => 'Post updated successfully'
    ]);
}

function deletePost($pdo, $data) {
    $post_id = intval($data['post_id'] ?? 0);

    if ($post_id <= 0) {
        throw new Exception('Invalid post ID');
    }

    $stmt = $pdo->prepare("DELETE FROM news_posts WHERE id = ?");
    $stmt->execute([$post_id]);

    echo json_encode([
        'success' => true,
        'message' => 'Post deleted successfully'
    ]);
}
