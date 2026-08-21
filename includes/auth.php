<?php
// includes/auth.php - Shared Auth Logic
session_start();

// --- Admin Auth ---
/**
 * Enforces Admin-only access.
 * If the user is not logged in as an Admin, they are redirected to the unified login page.
 */
function requireAdminAuth() {
    if (!isset($_SESSION['admin_id'])) {
        header("Location: /Cake_Verse/login.php");
        exit();
    }
}

/**
 * Checks if the current session belongs to an authenticated Admin.
 * 
 * @return bool True if logged in as Admin, False otherwise.
 */
function isAdminAuthenticated() {
    return isset($_SESSION['admin_id']);
}

/**
 * Creates session variables to log in an Admin.
 * 
 * @param int $id The Admin's database ID.
 * @param string $username The Admin's username.
 */
function loginAdmin($id, $username) {
    $_SESSION['admin_id'] = $id;
    $_SESSION['admin_username'] = $username;
}

/**
 * Destroys Admin session variables to log them out.
 */
function logoutAdmin() {
    unset($_SESSION['admin_id']);
    unset($_SESSION['admin_username']);
}

// --- Customer Auth ---

/**
 * Enforces Customer-only access.
 * If the user is not logged in as a Customer, they are redirected to the unified login page.
 */
function requireCustomerAuth() {
    if (!isset($_SESSION['customer_id'])) {
        header("Location: /Cake_Verse/login.php");
        exit();
    }
}

/**
 * Checks if the current session belongs to an authenticated Customer.
 * 
 * @return bool True if logged in as Customer, False otherwise.
 */
function isCustomerAuthenticated() {
    return isset($_SESSION['customer_id']);
}

/**
 * Creates session variables to log in a Customer securely.
 * 
 * @param int $id The Customer's database ID.
 * @param string $name The Customer's full name.
 * @param string $email The Customer's email.
 * @param string $phone The Customer's phone number.
 */
function loginCustomer($id, $name, $email, $phone) {
    $_SESSION['customer_id'] = $id;
    $_SESSION['customer_name'] = $name;
    $_SESSION['customer_email'] = $email;
    $_SESSION['customer_phone'] = $phone;
}

/**
 * Destroys Customer session variables to log them out.
 */
function logoutCustomer() {
    unset($_SESSION['customer_id']);
    unset($_SESSION['customer_name']);
    unset($_SESSION['customer_email']);
    unset($_SESSION['customer_phone']);
}
?>
