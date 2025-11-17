<?php
// oauth_helper.php - OAuth Provider Helper

require_once 'vendor/autoload.php';
require_once 'config.php';

use League\OAuth2\Client\Provider\Google;
use Depotwarehouse\OAuth2\Client\Twitch\Provider\Twitch;
use Wohali\OAuth2\Client\Provider\Discord;

class OAuthHelper {
    private $config;
    
    public function __construct() {
        $this->config = require 'oauth_config.php';
    }
    
    /**
     * Get OAuth provider instance
     */
    public function getProvider($providerName) {
        switch ($providerName) {
            case 'google':
                return new Google([
                    'clientId'     => $this->config['google']['client_id'],
                    'clientSecret' => $this->config['google']['client_secret'],
                    'redirectUri'  => $this->config['google']['redirect_uri'],
                ]);
                
case 'twitch':
    $provider = new Twitch([
        'clientId'     => $config['client_id'],
        'clientSecret' => $config['client_secret'],
        'redirectUri'  => $config['redirect_uri'],
    ]);
    
    // Override to use new Twitch OAuth endpoints
    $provider->authorizationUrl = 'https://id.twitch.tv/oauth2/authorize';
    $provider->accessTokenUrl = 'https://id.twitch.tv/oauth2/token';
    
    return $provider;
                
            case 'discord':
                return new Discord([
                    'clientId'     => $this->config['discord']['client_id'],
                    'clientSecret' => $this->config['discord']['client_secret'],
                    'redirectUri'  => $this->config['discord']['redirect_uri'],
                ]);
                
            default:
                throw new Exception('Unknown OAuth provider: ' . $providerName);
        }
    }
    
    /**
     * Get authorization URL
     */
    public function getAuthorizationUrl($providerName) {
        $provider = $this->getProvider($providerName);
        $scopes = $this->config[$providerName]['scopes'];
        
        $authUrl = $provider->getAuthorizationUrl([
            'scope' => $scopes
        ]);
        
        // Store state in session for CSRF protection
        $_SESSION['oauth2state'] = $provider->getState();
        $_SESSION['oauth_provider'] = $providerName;
        
        return $authUrl;
    }
    
    /**
     * Handle OAuth callback and get user info
     */
    public function handleCallback($providerName, $code, $state) {
        // Verify state to prevent CSRF
        if (empty($state) || ($state !== $_SESSION['oauth2state'])) {
            unset($_SESSION['oauth2state']);
            throw new Exception('Invalid state');
        }
        
        unset($_SESSION['oauth2state']);
        
        $provider = $this->getProvider($providerName);
        
        try {
            // Get access token
            $token = $provider->getAccessToken('authorization_code', [
                'code' => $code
            ]);
            
            // Get user details
            $resourceOwner = $provider->getResourceOwner($token);
            
            // Normalize user data across providers
            return $this->normalizeUserData($providerName, $resourceOwner);
            
        } catch (Exception $e) {
            throw new Exception('Failed to get access token: ' . $e->getMessage());
        }
    }
    
    /**
     * Normalize user data from different providers
     */
    private function normalizeUserData($provider, $resourceOwner) {
        $data = $resourceOwner->toArray();
        
        switch ($provider) {
            case 'google':
                return [
                    'provider'    => 'google',
                    'provider_id' => $data['sub'],
                    'email'       => $data['email'],
                    'username'    => $data['name'] ?? explode('@', $data['email'])[0],
                    'avatar_url'  => $data['picture'] ?? null,
                    'verified'    => $data['email_verified'] ?? false,
                ];
                
            case 'twitch':
                return [
                    'provider'    => 'twitch',
                    'provider_id' => $data['id'],
                    'email'       => $data['email'] ?? null,
                    'username'    => $data['display_name'] ?? $data['login'],
                    'avatar_url'  => $data['profile_image_url'] ?? null,
                    'verified'    => true,
                ];
                
            case 'discord':
                return [
                    'provider'    => 'discord',
                    'provider_id' => $data['id'],
                    'email'       => $data['email'] ?? null,
                    'username'    => $data['username'] . '#' . $data['discriminator'],
                    'avatar_url'  => isset($data['avatar']) 
                        ? "https://cdn.discordapp.com/avatars/{$data['id']}/{$data['avatar']}.png"
                        : null,
                    'verified'    => $data['verified'] ?? false,
                ];
                
            default:
                throw new Exception('Unknown provider');
        }
    }
    
    /**
     * Find or create user from OAuth data
     */
    public function findOrCreateUser($userData) {
        $pdo = getDB();
        
        // Try to find user by OAuth provider and ID
        $stmt = $pdo->prepare("
            SELECT * FROM users 
            WHERE oauth_provider = ? AND oauth_id = ?
        ");
        $stmt->execute([$userData['provider'], $userData['provider_id']]);
        $user = $stmt->fetch();
        
        if ($user) {
            // Update last login and avatar
            $stmt = $pdo->prepare("
                UPDATE users 
                SET last_login = NOW(), 
                    avatar_url = ?
                WHERE id = ?
            ");
            $stmt->execute([$userData['avatar_url'], $user['id']]);
            
            return $user;
        }
        
        // Check if email already exists (link accounts)
        if (!empty($userData['email'])) {
            $stmt = $pdo->prepare("SELECT * FROM users WHERE email = ?");
            $stmt->execute([$userData['email']]);
            $existingUser = $stmt->fetch();
            
            if ($existingUser) {
                // Link OAuth to existing account
                $stmt = $pdo->prepare("
                    UPDATE users 
                    SET oauth_provider = ?, 
                        oauth_id = ?,
                        avatar_url = ?,
                        last_login = NOW()
                    WHERE id = ?
                ");
                $stmt->execute([
                    $userData['provider'],
                    $userData['provider_id'],
                    $userData['avatar_url'],
                    $existingUser['id']
                ]);
                
                return $existingUser;
            }
        }
        
        // Create new user
        $username = $this->generateUniqueUsername($userData['username']);
        
        $stmt = $pdo->prepare("
            INSERT INTO users (
                username, email, password_hash, 
                oauth_provider, oauth_id, avatar_url,
                created_at, last_login
            ) VALUES (?, ?, NULL, ?, ?, ?, NOW(), NOW())
        ");
        
        $stmt->execute([
            $username,
            $userData['email'],
            $userData['provider'],
            $userData['provider_id'],
            $userData['avatar_url']
        ]);
        
        $userId = $pdo->lastInsertId();
        
        // Return newly created user
        $stmt = $pdo->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$userId]);
        return $stmt->fetch();
    }
    
    /**
     * Generate unique username if needed
     */
    private function generateUniqueUsername($baseUsername) {
        $pdo = getDB();
        
        // Remove special characters and spaces
        $username = preg_replace('/[^a-zA-Z0-9_]/', '', str_replace(' ', '_', $baseUsername));
        $username = substr($username, 0, 50); // Limit length
        
        // Check if username exists
        $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ?");
        $stmt->execute([$username]);
        
        if (!$stmt->fetch()) {
            return $username;
        }
        
        // Add number suffix if exists
        $counter = 1;
        while (true) {
            $newUsername = $username . $counter;
            $stmt->execute([$newUsername]);
            
            if (!$stmt->fetch()) {
                return $newUsername;
            }
            
            $counter++;
        }
    }
}