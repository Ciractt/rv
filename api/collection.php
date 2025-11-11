<?php
require_once '../config.php';
requireLogin();

header('Content-Type: application/json');

$pdo = getDB();
$user = getCurrentUser();
$response = ['success' => false, 'message' => ''];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $action = $_POST['action'] ?? '';
    
    if ($action === 'add') {
        $card_id = intval($_POST['card_id'] ?? 0);
        
        if ($card_id <= 0) {
            $response['message'] = 'Invalid card ID';
        } else {
            // Check if card exists
            $stmt = $pdo->prepare("SELECT id FROM cards WHERE id = ?");
            $stmt->execute([$card_id]);
            
            if (!$stmt->fetch()) {
                $response['message'] = 'Card not found';
            } else {
                // Check if already in collection
                $stmt = $pdo->prepare("SELECT id, quantity FROM user_collections WHERE user_id = ? AND card_id = ?");
                $stmt->execute([$user['id'], $card_id]);
                $existing = $stmt->fetch();
                
                if ($existing) {
                    // Update quantity
                    $stmt = $pdo->prepare("UPDATE user_collections SET quantity = quantity + 1 WHERE id = ?");
                    $stmt->execute([$existing['id']]);
                } else {
                    // Add new card
                    $stmt = $pdo->prepare("INSERT INTO user_collections (user_id, card_id, quantity) VALUES (?, ?, 1)");
                    $stmt->execute([$user['id'], $card_id]);
                }
                
                $response['success'] = true;
                $response['message'] = 'Card added to collection';
            }
        }
    } elseif ($action === 'update_quantity') {
        $card_id = intval($_POST['card_id'] ?? 0);
        $change = intval($_POST['change'] ?? 0);
        
        if ($card_id <= 0 || $change == 0) {
            $response['message'] = 'Invalid parameters';
        } else {
            $stmt = $pdo->prepare("SELECT id, quantity FROM user_collections WHERE user_id = ? AND card_id = ?");
            $stmt->execute([$user['id'], $card_id]);
            $collection = $stmt->fetch();
            
            if (!$collection) {
                $response['message'] = 'Card not in collection';
            } else {
                $new_quantity = $collection['quantity'] + $change;
                
                if ($new_quantity <= 0) {
                    // Remove from collection
                    $stmt = $pdo->prepare("DELETE FROM user_collections WHERE id = ?");
                    $stmt->execute([$collection['id']]);
                    $response['message'] = 'Card removed from collection';
                } else {
                    // Update quantity
                    $stmt = $pdo->prepare("UPDATE user_collections SET quantity = ? WHERE id = ?");
                    $stmt->execute([$new_quantity, $collection['id']]);
                    $response['message'] = 'Quantity updated';
                }
                
                $response['success'] = true;
            }
        }
    } elseif ($action === 'remove') {
        $card_id = intval($_POST['card_id'] ?? 0);
        
        if ($card_id <= 0) {
            $response['message'] = 'Invalid card ID';
        } else {
            $stmt = $pdo->prepare("DELETE FROM user_collections WHERE user_id = ? AND card_id = ?");
            $stmt->execute([$user['id'], $card_id]);
            
            $response['success'] = true;
            $response['message'] = 'Card removed from collection';
        }
    }
}

echo json_encode($response);
