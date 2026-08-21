<?php
// customer/payment-status.php - Payment Result View (Member 2)
require_once __DIR__ . '/../includes/functions.php';

$status = getSanitizedQueryInput('status');
$order_id = getSanitizedQueryInput('order_id');

$extra_css = '/Cake_Verse/assets/css/checkout.css';
include __DIR__ . '/../includes/header.php';
?>

<?php if ($status === 'success' && $order_id): ?>
    <div class="status-container status-success">
        <div class="status-icon">✅</div>
        <h2>Payment Successful!</h2>
        <p>Thank you for your order. Your cake will be prepared soon.</p>
        
        <div class="status-details">
            <strong>Order Reference:</strong> #<?php echo htmlspecialchars($order_id); ?><br>
            Please keep this reference number for your records.
        </div>
        
        <a href="/Cake_Verse/customer/catalog.php" class="btn">Continue Shopping</a>
    </div>
<?php else: ?>
    <div class="status-container status-error">
        <div class="status-icon">❌</div>
        <h2>Payment Failed</h2>
        <p>There was an issue processing your payment. Please try again.</p>
        
        <div class="mt-2">
            <a href="/Cake_Verse/customer/catalog.php" class="btn btn-secondary">Return to Catalog</a>
        </div>
    </div>
<?php endif; ?>

<?php include __DIR__ . '/../includes/footer.php'; ?>
