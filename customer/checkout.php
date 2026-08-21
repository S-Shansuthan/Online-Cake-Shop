<?php
// customer/checkout.php - Checkout Form with PayPal/Debit (Member 2)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$cake_id = getSanitizedQueryInput('cake_id');
if (!$cake_id || !is_numeric($cake_id)) {
    redirect('/Cake_Verse/customer/catalog.php');
}

$dbConnection = getDBConnection();
$preparedStatement = $dbConnection->prepare("SELECT * FROM cakes WHERE id = ? AND availability = TRUE");
$preparedStatement->bind_param("i", $cake_id);
$preparedStatement->execute();
$result = $preparedStatement->get_result();

if ($result->num_rows === 0) {
    redirect('/Cake_Verse/customer/catalog.php');
}

$cake = $result->fetch_assoc();
$preparedStatement->close();
$dbConnection->close();

$extra_css = '/Cake_Verse/assets/css/checkout.css';
$extra_js = '/Cake_Verse/assets/js/checkout.js';
include __DIR__ . '/../includes/header.php';

// Pre-fill data if logged in
$prefill_name = isCustomerAuthenticated() ? $_SESSION['customer_name'] : '';
$prefill_email = isCustomerAuthenticated() ? $_SESSION['customer_email'] : '';
$prefill_phone = isCustomerAuthenticated() ? $_SESSION['customer_phone'] : '';
?>

<!-- PayHere Sandbox Script -->
<script type="text/javascript" src="https://www.payhere.lk/lib/payhere.js"></script>

<!-- PayPal JS SDK -->
<script src="https://www.paypal.com/sdk/js?client-id=sb&currency=USD"></script>

<!-- PayPal JS SDK -->


<div class="checkout-container">
    <div class="checkout-form-section">
        <h2>Customer Details & Delivery</h2>
        
        <?php if (!isCustomerAuthenticated()): ?>
            <div class="mb-2" style="background:#e9ecef; padding:15px; border-radius:4px;">
                <a href="/Cake_Verse/customer/login.php">Login to your account</a> for faster checkout!
            </div>
        <?php endif; ?>
        
        <div id="errorBox" class="text-danger mb-2" style="display: none;"></div>
        
        <form id="checkoutForm">
            <input type="hidden" id="cakeId" name="cake_id" value="<?php echo $cake['id']; ?>">
            
            <div class="form-row">
                <div class="form-group">
                    <label for="fullName">Full Name *</label>
                    <input type="text" id="fullName" name="full_name" class="form-control" value="<?php echo htmlspecialchars($prefill_name); ?>" required>
                </div>
                <div class="form-group">
                    <label for="phone">Phone Number *</label>
                    <input type="tel" id="phone" name="phone" class="form-control" placeholder="07XXXXXXXX" value="<?php echo htmlspecialchars($prefill_phone); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="email">Email Address</label>
                <input type="email" id="email" name="email" class="form-control" value="<?php echo htmlspecialchars($prefill_email); ?>">
            </div>
            
            <div class="form-group">
                <label for="address">Delivery Address *</label>
                <textarea id="address" name="address" class="form-control" required></textarea>
            </div>
            
            <h2 class="mt-2">Order Customization</h2>
            <div class="form-group">
                <label for="customMessage">Custom Message on Cake (Max 50 chars)</label>
                <input type="text" id="customMessage" name="custom_message" class="form-control" maxlength="50" placeholder="e.g. Happy 10th Anniversary!">
                <small>Leave blank if no message is required.</small>
            </div>
            
            <div class="form-group">
                <label for="quantity">Quantity</label>
                <select id="quantity" name="quantity" class="form-control" style="width: 100px;">
                    <option value="1">1</option>
                    <option value="2">2</option>
                    <option value="3">3</option>
                    <option value="4">4</option>
                    <option value="5">5</option>
                </select>
            </div>
            
            <h2 class="mt-2">Payment Method</h2>
            
            <div class="payment-tabs">
                <button type="button" class="payment-tab active" id="debitCardTabButton">PayHere (LKR)</button>
                <button type="button" class="payment-tab" id="paypalTabButton">PayPal (USD)</button>
                <button type="button" class="payment-tab" id="codTabButton">Cash on Delivery</button>
            </div>
            
            <div id="debitSection">
                <div id="debitForm" class="payment-form active">
                    <div class="text-center" style="padding: 20px;">
                        <img src="https://www.payhere.lk/downloads/images/payhere_short_banner.png" alt="PayHere" style="max-width: 250px; margin-bottom: 15px;">
                        <p>You will be securely redirected to PayHere to complete your transaction.</p>
                    </div>
                </div>
                <button type="button" id="payButton" class="btn btn-block mt-2" style="font-size: 1.2rem; padding: 15px;">
                    Pay & Place Order (Card)
                </button>
            </div>
            
            <div id="paypalSection" style="display: none; text-align: center; margin-top: 20px;">
                <p>Click below to pay securely via PayPal.</p>
                <div id="paypal-button-container"></div>
            </div>
            
            <div id="codSection" style="display: none;">
                <div class="payment-form active">
                    <p class="text-center" style="padding: 20px;">You will pay with Cash upon delivery. No card needed!</p>
                </div>
                <button type="button" id="payCodButton" class="btn btn-block mt-2" style="font-size: 1.2rem; padding: 15px;">Confirm Cash Order</button>
            </div>
            
        </form>
    </div>
    
    <div class="checkout-summary-section">
        <h2>Order Summary</h2>
        
        <div class="summary-item">
            <div>
                <strong><?php echo htmlspecialchars($cake['name']); ?></strong><br>
                <small><?php echo htmlspecialchars($cake['flavor']); ?> | <?php echo htmlspecialchars($cake['weight']); ?></small>
            </div>
            <div id="unitPrice" data-price="<?php echo $cake['price']; ?>">
                <?php echo formatPrice($cake['price']); ?>
            </div>
        </div>
        
        <div class="summary-item">
            <div>Quantity</div>
            <div id="summaryQty">1</div>
        </div>
        
        <div class="summary-total">
            <div>Total Amount</div>
            <div id="summaryTotal"><?php echo formatPrice($cake['price']); ?></div>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
