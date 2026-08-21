<?php
// login.php - Unified Login System (Admin & Customer)
require_once __DIR__ . '/config/database.php';
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Redirect if already logged in
if (isAdminAuthenticated()) {
    redirect('/Cake_Verse/admin/dashboard.php');
} elseif (isCustomerAuthenticated()) {
    redirect('/Cake_Verse/customer/catalog.php');
}

$error = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $identifier = getSanitizedPostInput('identifier'); // Can be email or username
    $password = $_POST['password'] ?? '';
    
    if (empty($identifier) || empty($password)) {
        $error = 'Please enter both your email/username and password.';
    } else {
        $dbConnection = getDBConnection();
        $auth_success = false;
        
        // 1. Check Admins Table First (by username)
        $preparedStatement = $dbConnection->prepare("SELECT id, username, password_hash FROM admins WHERE username = ?");
        $preparedStatement->bind_param("s", $identifier);
        $preparedStatement->execute();
        $result = $preparedStatement->get_result();
        
        if ($result->num_rows === 1) {
            $admin = $result->fetch_assoc();
            if (password_verify($password, $admin['password_hash'])) {
                loginAdmin($admin['id'], $admin['username']);
                $auth_success = true;
                $preparedStatement->close();
                $dbConnection->close();
                redirect('/Cake_Verse/admin/dashboard.php');
            }
        }
        $preparedStatement->close();
        
        // 2. If not admin, check Customers Table (by email)
        if (!$auth_success) {
            $preparedStatement = $dbConnection->prepare("SELECT id, name, email, phone, password_hash FROM customers WHERE email = ?");
            $preparedStatement->bind_param("s", $identifier);
            $preparedStatement->execute();
            $result = $preparedStatement->get_result();
            
            if ($result->num_rows === 1) {
                $customer = $result->fetch_assoc();
                if (password_verify($password, $customer['password_hash'])) {
                    loginCustomer($customer['id'], $customer['name'], $customer['email'], $customer['phone']);
                    $auth_success = true;
                    $preparedStatement->close();
                    $dbConnection->close();
                    redirect('/Cake_Verse/customer/catalog.php');
                }
            }
            $preparedStatement->close();
        }
        
        $dbConnection->close();
        
        if (!$auth_success) {
            $error = 'Invalid credentials. Please check your username/email and password.';
        }
    }
}

$extra_css = '/Cake_Verse/assets/css/admin.css'; // Reusing admin.css for login box styling
include __DIR__ . '/includes/header.php';
?>

<div class="admin-login-container">
    <div class="text-center mb-2">
        <h2>Welcome to Cake Verse</h2>
        <p>Please log in to your account</p>
    </div>
    
    <?php if ($error): ?>
        <div class="text-danger mb-2 text-center" style="background:#f8d7da; padding:10px; border-radius:4px;"><?php echo $error; ?></div>
    <?php endif; ?>
    
    <form method="POST" action="">
        <div class="form-group">
            <label for="identifier">Email Address or Admin Username</label>
            <input type="text" id="identifier" name="identifier" class="form-control" required placeholder="example@email.com or admin">
        </div>
        <div class="form-group">
            <label for="password">Password</label>
            <input type="password" id="password" name="password" class="form-control" required>
        </div>
        <button type="submit" class="btn btn-block" style="font-size: 1.1rem; padding: 12px;">Secure Login</button>
    </form>
    
    <div class="text-center mt-2" style="border-top: 1px solid #ddd; padding-top: 15px; margin-top: 15px;">
        <p>New to Cake Verse?</p>
        <a href="/Cake_Verse/register.php" class="btn btn-secondary btn-block">Create a Customer Account</a>
    </div>
</div>

<?php include __DIR__ . '/includes/footer.php'; ?>
