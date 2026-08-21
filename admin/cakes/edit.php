<?php
// admin/cakes/edit.php - Edit Cake (Member 3)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';
require_once __DIR__ . '/../../includes/validation.php';

requireAdminAuth();

$id = getSanitizedQueryInput('id');
if (!$id || !is_numeric($id)) {
    redirect('/Cake_Verse/admin/cakes/index.php');
}

$dbConnection = getDBConnection();
$error = '';

// Fetch existing cake
$preparedStatement = $dbConnection->prepare("SELECT * FROM cakes WHERE id = ?");
$preparedStatement->bind_param("i", $id);
$preparedStatement->execute();
$result = $preparedStatement->get_result();

if ($result->num_rows === 0) {
    redirect('/Cake_Verse/admin/cakes/index.php');
}

$cake = $result->fetch_assoc();
$preparedStatement->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = getSanitizedPostInput('name');
    $flavor = getSanitizedPostInput('flavor');
    $category = getSanitizedPostInput('category');
    $weight = getSanitizedPostInput('weight');
    $price = getSanitizedPostInput('price');
    $description = getSanitizedPostInput('description');
    $availability = isset($_POST['availability']) ? 1 : 0;
    $image = getSanitizedPostInput('image', 'default-cake.png'); 
    
    if (empty($name) || empty($flavor) || empty($category) || empty($weight) || empty($price)) {
        $error = 'Please fill all required fields.';
    } elseif (!validatePrice($price)) {
        $error = 'Invalid price value.';
    } else {
        $preparedStatement = $dbConnection->prepare("UPDATE cakes SET name=?, flavor=?, category=?, weight=?, price=?, image=?, description=?, availability=? WHERE id=?");
        $preparedStatement->bind_param("ssssdssii", $name, $flavor, $category, $weight, $price, $image, $description, $availability, $id);
        
        if ($preparedStatement->execute()) {
            redirect('/Cake_Verse/admin/cakes/index.php?success=1');
        } else {
            $error = 'Database error: ' . $dbConnection->error;
        }
        $preparedStatement->close();
    }
}

$extra_css = '/Cake_Verse/assets/css/admin.css';
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
            <h2>Edit Cake: <?php echo htmlspecialchars($cake['name']); ?></h2>
            <a href="/Cake_Verse/admin/cakes/index.php" class="btn btn-secondary">Back to List</a>
        </div>
        
        <?php if ($error): ?>
            <div class="text-danger mb-2 p-2" style="background:#f8d7da; border-radius:4px;"><?php echo $error; ?></div>
        <?php endif; ?>
        
        <form method="POST" action="" style="max-width: 600px;">
            <div class="form-group">
                <label for="name">Cake Name *</label>
                <input type="text" id="name" name="name" class="form-control" value="<?php echo htmlspecialchars($cake['name']); ?>" required>
            </div>
            
            <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
                <div class="form-group" style="flex:1; margin-bottom:0;">
                    <label for="flavor">Flavor *</label>
                    <input type="text" id="flavor" name="flavor" class="form-control" value="<?php echo htmlspecialchars($cake['flavor']); ?>" required>
                </div>
                <div class="form-group" style="flex:1; margin-bottom:0;">
                    <label for="category">Category *</label>
                    <select id="category" name="category" class="form-control" required>
                        <?php
                        $categories = ['Birthday', 'Wedding', 'Anniversary', 'Cupcake', 'Other'];
                        foreach ($categories as $cat) {
                            $selected = ($cat === $cake['category']) ? 'selected' : '';
                            echo "<option value=\"$cat\" $selected>$cat</option>";
                        }
                        ?>
                    </select>
                </div>
            </div>
            
            <div class="form-row" style="display:flex; gap:15px; margin-bottom:15px;">
                <div class="form-group" style="flex:1; margin-bottom:0;">
                    <label for="weight">Weight/Size *</label>
                    <input type="text" id="weight" name="weight" class="form-control" value="<?php echo htmlspecialchars($cake['weight']); ?>" required>
                </div>
                <div class="form-group" style="flex:1; margin-bottom:0;">
                    <label for="price">Price (LKR) *</label>
                    <input type="number" id="price" name="price" class="form-control" step="0.01" min="0" value="<?php echo htmlspecialchars($cake['price']); ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label for="image">Image Filename (Mock)</label>
                <input type="text" id="image" name="image" class="form-control" value="<?php echo htmlspecialchars($cake['image']); ?>">
            </div>
            
            <div class="form-group">
                <label for="description">Description</label>
                <textarea id="description" name="description" class="form-control" rows="4"><?php echo htmlspecialchars($cake['description'] ?? ''); ?></textarea>
            </div>
            
            <div class="form-group" style="margin-bottom:20px;">
                <label style="display:flex; align-items:center; gap:10px; cursor:pointer;">
                    <input type="checkbox" name="availability" value="1" <?php echo $cake['availability'] ? 'checked' : ''; ?> style="width:auto; transform:scale(1.2);">
                    Cake is currently available
                </label>
            </div>
            
            <button type="submit" class="btn">Update Cake</button>
        </form>
    </div>
</div>

<?php 
$dbConnection->close();
include __DIR__ . '/../../includes/footer.php'; 
?>
