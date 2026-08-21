<?php
// register.php - Customer Registration
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';
require_once __DIR__ . '/includes/validation.php';

if (isCustomerAuthenticated()) {
    redirect('/Cake_Verse/customer/catalog.php');
} elseif (isAdminAuthenticated()) {
    redirect('/Cake_Verse/admin/dashboard.php');
}

$error = '';
$success = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = getSanitizedPostInput('name');
    $email = getSanitizedPostInput('email');
    $phone = getSanitizedPostInput('phone');
    $password = $_POST['password'] ?? '';
    $confirm_password = $_POST['confirm_password'] ?? '';

    if (empty($name) || empty($email) || empty($phone) || empty($password) || empty($confirm_password)) {
        $error = 'Please fill all fields.';
    } elseif (!validateEmail($email)) {
        $error = 'Invalid email address.';
    } elseif (!validatePhone($phone)) {
        $error = 'Invalid phone number (must be 10 digits).';
    } elseif ($password !== $confirm_password) {
        $error = 'Passwords do not match.';
    } else {
        $dbConnection = getDBConnection();
        
        $preparedStatement = $dbConnection->prepare("SELECT id FROM customers WHERE email = ?");
        $preparedStatement->bind_param("s", $email);
        $preparedStatement->execute();
        $preparedStatement->store_result();
        
        if ($preparedStatement->num_rows > 0) {
            $error = 'Email already registered. Please login.';
        } else {
            $password_hash = password_hash($password, PASSWORD_DEFAULT);
            $preparedStatement->close();
            
            $preparedStatement = $dbConnection->prepare("INSERT INTO customers (name, email, phone, password_hash) VALUES (?, ?, ?, ?)");
            $preparedStatement->bind_param("ssss", $name, $email, $phone, $password_hash);
            
            if ($preparedStatement->execute()) {
                $success = 'Registration successful! You can now log in.';
            } else {
                $error = 'Database error. Please try again.';
            }
        }
        $preparedStatement->close();
        $dbConnection->close();
    }
}

$extra_css = '/Cake_Verse/assets/css/admin.css';
include __DIR__ . '/includes/header.php';
?>

<div class="admin-login-container" style="max-width: 500px;">
    <h2 class="text-center mb-2">Create an Account</h2>
    
    <?php if ($error): ?>
        <div class="text-danger mb-2 text-center" style="background:#f8d7da; padding:10px; border-radius:4px;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <?php if ($success): ?>
        <div class="text-success mb-2 text-center" style="background:#d4edda; padding:10px; border-radius:4px;"><?php echo $success; ?></div>
        <div class="text-center">
            <a href="/Cake_Verse/login.php" class="btn">Proceed to Login</a>
        </div>
    <?php else: ?>
        <form method="POST" action="">
            <div class="form-group">
                <label for="name">Full Name *</label>
                <input type="text" id="name" name="name" class="form-control" required value="<?php echo htmlspecialchars($name ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="email">Email Address *</label>
                <input type="email" id="email" name="email" class="form-control" required value="<?php echo htmlspecialchars($email ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="phone">Phone Number *</label>
                <input type="tel" id="phone" name="phone" class="form-control" placeholder="07XXXXXXXX" required value="<?php echo htmlspecialchars($phone ?? ''); ?>">
            </div>
            <div class="form-group">
                <label for="password">Password *</label>
                <input type="password" id="password" name="password" class="form-control" required>
            </div>
            <div class="form-group">
                <label for="confirm_password">Confirm Password *</label>
                <input type="password" id="confirm_password" name="confirm_password" class="form-control" required>
            </div>
            <button type="submit" class="btn btn-block">Register</button>
        </form>
        <div class="text-center mt-2">
            Already have an account? <a href="/Cake_Verse/login.php">Login here</a>
        </div>
    <?php endif; ?>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
