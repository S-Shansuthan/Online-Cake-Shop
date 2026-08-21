<?php
// logout.php - Unified Logout System
require_once __DIR__ . '/includes/auth.php';
require_once __DIR__ . '/includes/functions.php';

// Safe to call both, it unsets session variables
logoutAdmin();
logoutCustomer();

redirect('/Cake_Verse/login.php');
?>
