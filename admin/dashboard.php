<?php
// admin/dashboard.php - Admin Dashboard (Member 3 & 4)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/auth.php';
require_once __DIR__ . '/../includes/functions.php';

requireAdminAuth();

$dbConnection = getDBConnection();

// Get basic stats
$stats = [
    'cakes' => 0,
    'orders_total' => 0,
    'orders_pending' => 0,
    'revenue' => 0
];

$res = $dbConnection->query("SELECT COUNT(*) as count FROM cakes");
if ($res) $stats['cakes'] = $res->fetch_assoc()['count'];

$res = $dbConnection->query("SELECT COUNT(*) as count, SUM(total_amount) as revenue FROM orders");
if ($res) {
    $row = $res->fetch_assoc();
    $stats['orders_total'] = $row['count'];
    $stats['revenue'] = $row['revenue'] ?? 0;
}

$res = $dbConnection->query("SELECT COUNT(*) as count FROM orders WHERE order_status = 'Pending'");
if ($res) $stats['orders_pending'] = $res->fetch_assoc()['count'];

$dbConnection->close();

$extra_css = '/Cake_Verse/assets/css/admin.css';
include __DIR__ . '/../includes/header.php';
?>

<div class="admin-container">
    <aside class="sidebar">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="/Cake_Verse/admin/dashboard.php" class="active">Dashboard</a></li>
            <li><a href="/Cake_Verse/admin/cakes/index.php">Manage Cakes</a></li>
            <li><a href="/Cake_Verse/admin/orders/index.php">Manage Orders</a></li>
            <li><a href="/Cake_Verse/admin/users/index.php">Manage Users</a></li>
        </ul>
    </aside>
    
    <div class="admin-content">
        <div class="admin-header">
            <h2>Welcome, <?php echo htmlspecialchars($_SESSION['admin_username']); ?>!</h2>
        </div>
        
        <div class="dashboard-cards">
            <div class="card">
                <h3>Total Cakes</h3>
                <div class="value"><?php echo $stats['cakes']; ?></div>
            </div>
            <div class="card">
                <h3>Total Orders</h3>
                <div class="value"><?php echo $stats['orders_total']; ?></div>
            </div>
            <div class="card">
                <h3>Pending Orders</h3>
                <div class="value text-danger"><?php echo $stats['orders_pending']; ?></div>
            </div>
            <div class="card">
                <h3>Total Revenue</h3>
                <div class="value" style="font-size: 1.5rem;"><?php echo formatPrice($stats['revenue']); ?></div>
            </div>
        </div>
        
        <h3>Quick Actions</h3>
        <div class="mt-2">
            <a href="/Cake_Verse/admin/cakes/create.php" class="btn">Add New Cake</a>
            <a href="/Cake_Verse/admin/orders/index.php" class="btn btn-secondary">View Pending Orders</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
