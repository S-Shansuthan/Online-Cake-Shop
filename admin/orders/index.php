<?php
// admin/orders/index.php - Admin Order Management (Member 4)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdminAuth(); // Protect route

$extra_css = '/Cake_Verse/assets/css/admin.css';
$extra_js = '/Cake_Verse/assets/js/admin.js';
include __DIR__ . '/../../includes/header.php';

$dbConnection = getDBConnection();
$sql = "SELECT o.*, c.name as cake_name, oi.quantity 
        FROM orders o 
        LEFT JOIN order_items oi ON o.id = oi.order_id 
        LEFT JOIN cakes c ON oi.cake_id = c.id
        ORDER BY o.created_at DESC";
$result = $dbConnection->query($sql);
?>

<div class="admin-container">
    <aside class="sidebar">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="/Cake_Verse/admin/dashboard.php">Dashboard</a></li>
            <li><a href="/Cake_Verse/admin/cakes/index.php">Manage Cakes</a></li>
            <li><a href="/Cake_Verse/admin/orders/index.php" class="active">Manage Orders</a></li>
            <li><a href="/Cake_Verse/admin/users/index.php">Manage Users</a></li>
        </ul>
    </aside>
    
    <div class="admin-content">
        <h2>Order Management</h2>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Customer Info</th>
                            <th>Cake & Qty</th>
                            <th>Message</th>
                            <th>Total</th>
                            <th>Payment</th>
                            <th>Status</th>
                            <th>Action</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td>#<?php echo htmlspecialchars($row['id']); ?></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['customer_name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($row['customer_phone']); ?></small>
                                </td>
                                <td>
                                    <?php echo htmlspecialchars($row['cake_name'] ?? 'N/A'); ?><br>
                                    <small>Qty: <?php echo htmlspecialchars($row['quantity'] ?? '0'); ?></small>
                                </td>
                                <td>
                                    <span class="custom-message" title="<?php echo htmlspecialchars($row['custom_message'] ?? 'None'); ?>">
                                        <?php 
                                        $msg = htmlspecialchars($row['custom_message'] ?? 'None');
                                        echo strlen($msg) > 20 ? substr($msg, 0, 20) . '...' : $msg; 
                                        ?>
                                    </span>
                                </td>
                                <td><?php echo formatPrice($row['total_amount']); ?></td>
                                <td>
                                    <span class="badge payment-<?php echo strtolower($row['payment_status']); ?>">
                                        <?php echo htmlspecialchars($row['payment_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <span class="badge status-<?php echo strtolower($row['order_status']); ?>" id="status-badge-<?php echo $row['id']; ?>">
                                        <?php echo htmlspecialchars($row['order_status']); ?>
                                    </span>
                                </td>
                                <td>
                                    <select class="status-select" data-order-id="<?php echo $row['id']; ?>" onchange="updateOrderStatus(this)">
                                        <?php
                                        $statuses = ['Pending', 'Confirmed', 'Preparing', 'Ready', 'Completed', 'Cancelled'];
                                        foreach ($statuses as $status) {
                                            $selected = ($status == $row['order_status']) ? 'selected' : '';
                                            echo "<option value=\"$status\" $selected>$status</option>";
                                        }
                                        ?>
                                    </select>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <div class="empty-state">
                <p>No orders found.</p>
            </div>
        <?php endif; ?>
    </div>
</div>

<?php 
$dbConnection->close();
include __DIR__ . '/../../includes/footer.php'; 
?>
