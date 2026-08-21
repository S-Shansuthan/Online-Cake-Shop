<?php
// api/payment/process.php - Mock Payment Processing & Order Creation
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/validation.php';

if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    http_response_code(405);
    echo json_encode(['success' => false, 'message' => 'Method Not Allowed']);
    exit();
}

// Handle PayHere Webhook (notify_url)
if (isset($_POST['merchant_id']) && isset($_POST['order_id']) && isset($_POST['status_code'])) {
    // PayHere sends form-urlencoded data
    $order_id = intval($_POST['order_id']);
    $status_code = intval($_POST['status_code']);
    
    // Status Code 2 means success
    if ($status_code === 2) {
        $dbConnection = getDBConnection();
        $preparedStatement = $dbConnection->prepare("UPDATE orders SET payment_status = 'Paid', order_status = 'Confirmed' WHERE id = ?");
        $preparedStatement->bind_param("i", $order_id);
        $preparedStatement->execute();
        $preparedStatement->close();
        $dbConnection->close();
    }
    
    // Always respond 200 to PayHere
    echo json_encode(['success' => true]);
    exit();
}

$input = json_decode(file_get_contents('php://input'), true);

if (!$input) {
    echo json_encode(['success' => false, 'message' => 'Invalid JSON payload']);
    exit();
}

// Validation for Order Creation
$required_fields = ['cake_id', 'full_name', 'phone', 'address', 'quantity'];
foreach ($required_fields as $field) {
    if (empty($input[$field])) {
        echo json_encode(['success' => false, 'message' => "Missing required field: $field"]);
        exit();
    }
}

$cake_id = intval($input['cake_id']);
$quantity = intval($input['quantity']);
$full_name = sanitizeInput($input['full_name']);
$phone = sanitizeInput($input['phone']);
$email = sanitizeInput($input['email'] ?? '');
$address = sanitizeInput($input['address']);
$custom_message = sanitizeInput($input['custom_message'] ?? '');
$payment_method = sanitizeInput($input['payment_method'] ?? 'Debit Card'); // Catch PayPal vs Debit

if (!validatePhone($phone)) {
    echo json_encode(['success' => false, 'message' => 'Invalid Sri Lankan phone number (10 digits required)']);
    exit();
}

$dbConnection = getDBConnection();
$dbConnection->begin_transaction();

try {
    $preparedStatement = $dbConnection->prepare("SELECT price FROM cakes WHERE id = ? AND availability = TRUE");
    $preparedStatement->bind_param("i", $cake_id);
    $preparedStatement->execute();
    $result = $preparedStatement->get_result();
    
    if ($result->num_rows === 0) {
        throw new Exception("Cake not found or unavailable");
    }
    
    $cake = $result->fetch_assoc();
    $total_amount = $cake['price'] * $quantity;
    $preparedStatement->close();
    
    $payment_status = ($payment_method === 'PayHere') ? 'Pending' : 'Paid';
    $order_status = ($payment_method === 'PayHere') ? 'Pending' : 'Confirmed';
    
    // Append the payment method to the custom message internally so the Admin can see how they paid
    $internal_message = "[$payment_method] " . $custom_message;
    
    $preparedStatement = $dbConnection->prepare("INSERT INTO orders (customer_name, customer_phone, customer_email, delivery_address, total_amount, payment_status, order_status, custom_message) VALUES (?, ?, ?, ?, ?, ?, ?, ?)");
    $preparedStatement->bind_param("ssssdsss", $full_name, $phone, $email, $address, $total_amount, $payment_status, $order_status, $internal_message);
    $preparedStatement->execute();
    
    $order_id = $dbConnection->insert_id;
    $preparedStatement->close();
    
    $preparedStatement = $dbConnection->prepare("INSERT INTO order_items (order_id, cake_id, quantity, price) VALUES (?, ?, ?, ?)");
    $preparedStatement->bind_param("iiid", $order_id, $cake_id, $quantity, $cake['price']);
    $preparedStatement->execute();
    $preparedStatement->close();
    
    $dbConnection->commit();
    
    echo json_encode(['success' => true, 'order_id' => $order_id]);
    
} catch (Exception $e) {
    $dbConnection->rollback();
    echo json_encode(['success' => false, 'message' => $e->getMessage()]);
}

$dbConnection->close();
?>
