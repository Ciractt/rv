<?php
// oauth_login.php - Initiate OAuth login flow
require_once 'config.php';
require_once 'oauth_helper.php';

$provider = $_GET['provider'] ?? '';

// Validate provider
if (!in_array($provider, ['google', 'twitch', 'discord'])) {
    $_SESSION['oauth_error'] = 'Invalid OAuth provider';
    header('Location: login.php');
    exit;
}

try {
    $oauth = new OAuthHelper();
    $authUrl = $oauth->getAuthorizationUrl($provider);
    
    // Redirect to provider's authorization page
    header('Location: ' . $authUrl);
    exit;
    
} catch (Exception $e) {
    error_log('OAuth Init Error: ' . $e->getMessage());
    $_SESSION['oauth_error'] = 'Failed to initialize OAuth: ' . $e->getMessage();
    header('Location: login.php');
    exit;
}