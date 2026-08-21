<?php
// admin/cakes/index.php - List Cakes (Member 3)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdminAuth();

$dbConnection = getDBConnection();
$result = $dbConnection->query("SELECT * FROM cakes ORDER BY created_at DESC");

$extra_css = '/Cake_Verse/assets/css/admin.css';
$extra_js = '/Cake_Verse/assets/js/admin.js';
include __DIR__ . '/../../includes/header.php';
?>

<div class="admin-container">
    <aside class="sidebar">
        <h3>Admin Menu</h3>
        <ul>
            <li><a href="/Cake_Verse/admin/dashboard.php">Dashboard</a></li>
            <li><a href="/Cake_Verse/admin/cakes/index.php" class="active">Manage Cakes</a></li>
            <li><a href="/Cake_Verse/admin/orders/index.php">Manage Orders</a></li>
            <li><a href="/Cake_Verse/admin/users/index.php">Manage Users</a></li>
        </ul>
    </aside>
    
    <div class="admin-content">
        <div class="admin-header">
            <h2>Manage Cakes</h2>
            <a href="/Cake_Verse/admin/cakes/create.php" class="btn">Add New Cake</a>
        </div>
        
        <?php if (isset($_GET['success'])): ?>
            <div class="text-success mb-2 p-2" style="background:#d4edda; border-radius:4px;">Action completed successfully!</div>
        <?php endif; ?>
        
        <?php if ($result && $result->num_rows > 0): ?>
            <div class="table-responsive">
                <table class="admin-table">
                    <thead>
                        <tr>
                            <th>Image</th>
                            <th>Name</th>
                            <th>Category</th>
                            <th>Price</th>
                            <th>Status</th>
                            <th>Actions</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php while ($row = $result->fetch_assoc()): ?>
                            <tr>
                                <td><img src="/Cake_Verse/assets/images/<?php echo htmlspecialchars($row['image']); ?>" alt="Cake" onerror="this.src='https://via.placeholder.com/50'"></td>
                                <td>
                                    <strong><?php echo htmlspecialchars($row['name']); ?></strong><br>
                                    <small><?php echo htmlspecialchars($row['flavor']); ?> | <?php echo htmlspecialchars($row['weight']); ?></small>
                                </td>
                                <td><?php echo htmlspecialchars($row['category']); ?></td>
                                <td><?php echo formatPrice($row['price']); ?></td>
                                <td>
                                    <?php if ($row['availability']): ?>
                                        <span class="badge payment-paid">Available</span>
                                    <?php else: ?>
                                        <span class="badge payment-failed">Out of Stock</span>
                                    <?php endif; ?>
                                </td>
                                <td class="action-links">
                                    <a href="/Cake_Verse/admin/cakes/edit.php?id=<?php echo $row['id']; ?>">Edit</a>
                                    <a href="/Cake_Verse/admin/cakes/delete.php?id=<?php echo $row['id']; ?>" class="text-danger" onclick="return confirmDelete()">Delete</a>
                                </td>
                            </tr>
                        <?php endwhile; ?>
                    </tbody>
                </table>
            </div>
        <?php else: ?>
            <p>No cakes found. <a href="/Cake_Verse/admin/cakes/create.php">Add one now</a>.</p>
        <?php endif; ?>
    </div>
</div>

<?php 
$dbConnection->close();
include __DIR__ . '/../../includes/footer.php'; 
?>
