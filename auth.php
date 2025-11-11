<?php
// auth.php - Handle login, logout, and registration
require_once 'config.php';

$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'register') {
        $username = trim($_POST['username'] ?? '');
        $email = trim($_POST['email'] ?? '');
        $password = $_POST['password'] ?? '';
        $confirm_password = $_POST['confirm_password'] ?? '';
        
        if (empty($username) || empty($email) || empty($password)) {
            $response['message'] = 'All fields are required';
        } elseif ($password !== $confirm_password) {
            $response['message'] = 'Passwords do not match';
        } elseif (strlen($password) < 6) {
            $response['message'] = 'Password must be at least 6 characters';
        } else {
            $pdo = getDB();
            
            // Check if username or email exists
            $stmt = $pdo->prepare("SELECT id FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $email]);
            
            if ($stmt->fetch()) {
                $response['message'] = 'Username or email already exists';
            } else {
                // Create user
                $password_hash = password_hash($password, PASSWORD_DEFAULT);
                $stmt = $pdo->prepare("INSERT INTO users (username, email, password_hash) VALUES (?, ?, ?)");
                
                if ($stmt->execute([$username, $email, $password_hash])) {
                    $response['success'] = true;
                    $response['message'] = 'Registration successful! You can now log in.';
                } else {
                    $response['message'] = 'Registration failed. Please try again.';
                }
            }
        }
    } elseif ($action === 'login') {
        $username = trim($_POST['username'] ?? '');
        $password = $_POST['password'] ?? '';
        
        if (empty($username) || empty($password)) {
            $response['message'] = 'Username and password are required';
        } else {
            $pdo = getDB();
            $stmt = $pdo->prepare("SELECT id, username, password_hash FROM users WHERE username = ? OR email = ?");
            $stmt->execute([$username, $username]);
            $user = $stmt->fetch();
            
            if ($user && password_verify($password, $user['password_hash'])) {
                $_SESSION['user_id'] = $user['id'];
                $_SESSION['username'] = $user['username'];
                
                // Update last login
                $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                $stmt->execute([$user['id']]);
                
                $response['success'] = true;
                $response['message'] = 'Login successful';
                $response['redirect'] = 'index.php';
            } else {
                $response['message'] = 'Invalid username or password';
            }
        }
    } elseif ($action === 'riot_login') {
        $riot_id = trim($_POST['riot_id'] ?? ''); // Format: Name#TAG
        
        if (empty($riot_id) || !preg_match('/^.+#.+$/', $riot_id)) {
            $response['message'] = 'Invalid Riot ID format. Use Name#TAG';
        } else {
            // Split riot_id into gameName and tagLine
            list($gameName, $tagLine) = explode('#', $riot_id, 2);
            
            // Call Riot API to get PUUID
            $api_url = "https://" . RIOT_API_REGION . ".api.riotgames.com/riot/account/v1/accounts/by-riot-id/" . urlencode($gameName) . "/" . urlencode($tagLine);
            
            $ch = curl_init();
            curl_setopt($ch, CURLOPT_URL, $api_url);
            curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
            curl_setopt($ch, CURLOPT_HTTPHEADER, [
                'X-Riot-Token: ' . RIOT_API_KEY
            ]);
            
            $result = curl_exec($ch);
            $http_code = curl_getinfo($ch, CURLINFO_HTTP_CODE);
            curl_close($ch);
            
            if ($http_code === 200) {
                $data = json_decode($result, true);
                $puuid = $data['puuid'] ?? null;
                
                if ($puuid) {
                    $pdo = getDB();
                    
                    // Check if user exists with this Riot ID
                    $stmt = $pdo->prepare("SELECT id, username FROM users WHERE riot_puuid = ?");
                    $stmt->execute([$puuid]);
                    $user = $stmt->fetch();
                    
                    if ($user) {
                        // User exists, log them in
                        $_SESSION['user_id'] = $user['id'];
                        $_SESSION['username'] = $user['username'];
                        
                        $stmt = $pdo->prepare("UPDATE users SET last_login = NOW() WHERE id = ?");
                        $stmt->execute([$user['id']]);
                        
                        $response['success'] = true;
                        $response['message'] = 'Riot login successful';
                        $response['redirect'] = 'index.php';
                    } else {
                        // New user, need to complete registration
                        $_SESSION['temp_riot_id'] = $riot_id;
                        $_SESSION['temp_riot_puuid'] = $puuid;
                        
                        $response['success'] = true;
                        $response['message'] = 'Riot account verified';
                        $response['redirect'] = 'complete_registration.php';
                    }
                } else {
                    $response['message'] = 'Failed to verify Riot account';
                }
            } elseif ($http_code === 404) {
                $response['message'] = 'Riot account not found';
            } else {
                $response['message'] = 'Failed to connect to Riot API';
            }
        }
    } elseif ($action === 'logout') {
        session_destroy();
        $response['success'] = true;
        $response['redirect'] = 'index.php';
    }
    
    header('Content-Type: application/json');
    echo json_encode($response);
    exit;
}
