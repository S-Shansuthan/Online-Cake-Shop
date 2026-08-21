<?php
// customer/catalog.php - Customer Catalog View (Member 1)
require_once __DIR__ . '/../config/database.php';

$extra_css = '/Cake_Verse/assets/css/customer.css';
$extra_js = '/Cake_Verse/assets/js/customer.js';
include __DIR__ . '/../includes/header.php';
?>

<div class="hero">
    <h1>Welcome to Cake Verse</h1>
    <p>Handcrafted cakes for your special moments</p>
</div>

<div class="filters-bar">
    <div class="search-box">
        <input type="text" id="searchInput" class="form-control" placeholder="Search cakes by name...">
    </div>
    <div class="category-filter">
        <select id="categoryFilter" class="form-control">
            <option value="">All Occasions</option>
            <option value="Birthday">Birthday</option>
            <option value="Wedding">Wedding</option>
            <option value="Anniversary">Anniversary</option>
            <option value="Cupcake">Cupcake</option>
            <option value="Other">Other</option>
        </select>
    </div>
</div>

<div id="cakeGrid" class="cake-grid">
    <!-- Cakes will be loaded here via JavaScript -->
    <div class="empty-state">Loading delicious cakes...</div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
