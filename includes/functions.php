<?php
// includes/functions.php - Shared Utility Functions (Member 4)

/**
 * Sanitizes input to prevent Cross-Site Scripting (XSS) attacks.
 * It converts special characters to HTML entities and removes slashes.
 * 
 * @param mixed $data The string or array of strings to sanitize.
 * @return mixed The sanitized data.
 */
function sanitizeInput($data) {
    if (is_array($data)) {
        return array_map('sanitizeInput', $data);
    }
    return htmlspecialchars(stripslashes(trim($data)));
}

/**
 * Redirects the user to a new URL securely.
 * 
 * @param string $url The path to redirect to (e.g., '/Cake_Verse/login.php').
 */
function redirect($url) {
    header("Location: $url");
    exit();
}

/**
 * Formats a raw number into a readable Sri Lankan Rupee (LKR) currency string.
 * 
 * @param float $amount The raw amount (e.g., 2500.5)
 * @return string The formatted price (e.g., LKR 2,500.50)
 */
function formatPrice($amount) {
    return 'LKR ' . number_format($amount, 2);
}

/**
 * Safely retrieves and sanitizes a value from the HTTP POST request (Form submissions).
 * 
 * @param string $key The name of the input field.
 * @param string $default The default value to return if the input is missing.
 * @return string The sanitized input value.
 */
function getSanitizedPostInput($key, $default = '') {
    return isset($_POST[$key]) ? sanitizeInput($_POST[$key]) : $default;
}

/**
 * Safely retrieves and sanitizes a value from the HTTP GET request (URL parameters).
 * 
 * @param string $key The name of the URL parameter.
 * @param string $default The default value to return if the parameter is missing.
 * @return string The sanitized query value.
 */
function getSanitizedQueryInput($key, $default = '') {
    return isset($_GET[$key]) ? sanitizeInput($_GET[$key]) : $default;
}
?>
