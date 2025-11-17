<?php
// oauth_callback.php - Handle OAuth callbacks from providers
require_once 'config.php';
require_once 'oauth_helper.php';

$provider = $_GET['provider'] ?? '';
$code = $_GET['code'] ?? '';
$state = $_GET['state'] ?? '';
$error = $_GET['error'] ?? '';

// Handle OAuth errors
if ($error) {
    $_SESSION['oauth_error'] = 'Authentication failed: ' . htmlspecialchars($error);
    header('Location: login.php');
    exit;
}

// Validate provider
if (!in_array($provider, ['google', 'twitch', 'discord'])) {
    $_SESSION['oauth_error'] = 'Invalid OAuth provider';
    header('Location: login.php');
    exit;
}

// Validate required parameters
if (empty($code) || empty($state)) {
    $_SESSION['oauth_error'] = 'Missing required OAuth parameters';
    header('Location: login.php');
    exit;
}

try {
    $oauth = new OAuthHelper();
    
    // Handle the callback and get user data
    $userData = $oauth->handleCallback($provider, $code, $state);
    
    // Find or create user
    $user = $oauth->findOrCreateUser($userData);
    
    if (!$user) {
        throw new Exception('Failed to create or find user');
    }
    
    // Set session
    $_SESSION['user_id'] = $user['id'];
    $_SESSION['username'] = $user['username'];
    
    // Success message
    $_SESSION['login_success'] = 'Successfully logged in with ' . ucfirst($provider);
    
    // Redirect to home
    header('Location: index.php');
    exit;
    
} catch (Exception $e) {
    error_log('OAuth Error: ' . $e->getMessage());
    $_SESSION['oauth_error'] = 'Authentication failed: ' . $e->getMessage();
    header('Location: login.php');
    exit;
}