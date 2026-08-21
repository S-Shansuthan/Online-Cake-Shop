<?php
// admin/users/index.php - Admin User Management
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdminAuth(); // Protect route

$extra_css = '/Cake_Verse/assets/css/admin.css';
$extra_js = '/Cake_Verse/assets/js/admin.js';
include __DIR__ . '/../../includes/header.php';

$dbConnection = getDBConnection();
$sql = "SELECT * FROM customers ORDER BY created_at DESC";
$result = $dbConnection->query($sql);
?>

<div class="admin-container">
    <aside class="sidebar">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="/Cake_Verse/admin/dashboard.php">Dashboard</a></li>
            <li><a href="/Cake_Verse/admin/cakes/index.php">Manage Cakes</a></li>
            <li><a href="/Cake_Verse/admin/orders/index.php">Manage Orders</a></li>
            <li><a href="/Cake_Verse/admin/users/index.php" class="active">Manage Users</a></li>
        </ul>
    </aside>
    
    <div class="admin-content">
        <h2>Registered Customers</h2>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Phone</th>
                            <th>Registered On</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td><strong><?php echo htmlspecialchars($row['name']); ?></strong></td>
                                <td><?php echo htmlspecialchars($row['email']); ?></td>
                                <td><?php echo htmlspecialchars($row['phone']); ?></td>
                                <td><?php echo date('Y-m-d H:i', strtotime($row['created_at'])); ?></td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No customers registered yet.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$dbConnection->close();
include __DIR__ . '/../../includes/footer.php'; 
?>
