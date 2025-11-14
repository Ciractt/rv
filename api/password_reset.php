<?php
// Password Reset API
// Note: This is a basic implementation. For production, you should:
// 1. Send actual emails using PHPMailer or similar
// 2. Store reset tokens in database with expiration
// 3. Use HTTPS only

require_once '../config.php';

header('Content-Type: application/json');

$action = $_POST['action'] ?? '';

try {
    if ($action === 'forgot_password') {
        $email = trim($_POST['email'] ?? '');

        if (empty($email)) {
            throw new Exception('Email is required');
        }

        if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
            throw new Exception('Invalid email address');
        }

        $pdo = getDB();

        // Check if email exists
        $stmt = $pdo->prepare("SELECT id, username FROM users WHERE email = ?");
        $stmt->execute([$email]);
        $user = $stmt->fetch();

        if ($user) {
            // Generate reset token
            $token = bin2hex(random_bytes(32));
            $expires = date('Y-m-d H:i:s', strtotime('+1 hour'));

            // Store token in database
            $stmt = $pdo->prepare("
                INSERT INTO password_reset_tokens (user_id, token, expires_at, created_at)
                VALUES (?, ?, ?, NOW())
                ON DUPLICATE KEY UPDATE token = ?, expires_at = ?, created_at = NOW()
            ");
            $stmt->execute([$user['id'], $token, $expires, $token, $expires]);

            // In production, send email here
            // For now, we'll just return success
            // You would use PHPMailer or similar:
            /*
            $reset_link = SITE_URL . "/reset_password.php?token=" . $token;
            mail($email, "Password Reset Request", "Click here to reset: " . $reset_link);
            */

            echo json_encode([
                'success' => true,
                'message' => 'If an account exists with this email, you will receive password reset instructions. (Note: Email sending not configured - contact admin)'
            ]);
        } else {
            // Don't reveal if email exists or not (security best practice)
            echo json_encode([
                'success' => true,
                'message' => 'If an account exists with this email, you will receive password reset instructions.'
            ]);
        }

    } elseif ($action === 'reset_password') {
        $token = $_POST['token'] ?? '';
        $new_password = $_POST['new_password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';

        if (empty($token) || empty($new_password) || empty($confirm_password)) {
            throw new Exception('All fields are required');
        }

        if ($new_password !== $confirm_password) {
            throw new Exception('Passwords do not match');
        }

        if (strlen($new_password) < 8) {
            throw new Exception('Password must be at least 8 characters');
        }

        if (!preg_match('/[0-9]/', $new_password)) {
            throw new Exception('Password must contain at least 1 number');
        }

        if (!preg_match('/[!@#$%^&*()_+\-=\[\]{};:\'",.<>?\/\\|`~]/', $new_password)) {
            throw new Exception('Password must contain at least 1 symbol');
        }

        $pdo = getDB();

        // Verify token
        $stmt = $pdo->prepare("
            SELECT user_id FROM password_reset_tokens
            WHERE token = ? AND expires_at > NOW()
        ");
        $stmt->execute([$token]);
        $reset_request = $stmt->fetch();

        if (!$reset_request) {
            throw new Exception('Invalid or expired reset token');
        }

        // Update password
        $password_hash = password_hash($new_password, PASSWORD_DEFAULT);
        $stmt = $pdo->prepare("UPDATE users SET password_hash = ? WHERE id = ?");
        $stmt->execute([$password_hash, $reset_request['user_id']]);

        // Delete used token
        $stmt = $pdo->prepare("DELETE FROM password_reset_tokens WHERE token = ?");
        $stmt->execute([$token]);

        echo json_encode([
            'success' => true,
            'message' => 'Password reset successfully. You can now log in with your new password.'
        ]);

    } else {
        throw new Exception('Invalid action');
    }

} catch (Exception $e) {
    echo json_encode([
        'success' => false,
        'message' => $e->getMessage()
    ]);
}
