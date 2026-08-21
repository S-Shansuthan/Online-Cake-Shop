<?php
// customer/cake-details.php - Cake Details View (Member 1)
require_once __DIR__ . '/../config/database.php';
require_once __DIR__ . '/../includes/functions.php';

$cake_id = getSanitizedQueryInput('id');
if (!$cake_id || !is_numeric($cake_id)) {
    redirect('/Cake_Verse/customer/catalog.php');
}

$dbConnection = getDBConnection();
$preparedStatement = $dbConnection->prepare("SELECT * FROM cakes WHERE id = ? AND availability = TRUE");
$preparedStatement->bind_param("i", $cake_id);
$preparedStatement->execute();
$result = $preparedStatement->get_result();

if ($result->num_rows === 0) {
    $preparedStatement->close();
    $dbConnection->close();
    redirect('/Cake_Verse/customer/catalog.php');
}

$cake = $result->fetch_assoc();
$preparedStatement->close();
$dbConnection->close();

$extra_css = '/Cake_Verse/assets/css/customer.css';
include __DIR__ . '/../includes/header.php';
?>

<div class="cake-details-container">
    <div class="cake-details-image">
        <img src="/Cake_Verse/assets/images/<?php echo htmlspecialchars($cake['image']); ?>" alt="<?php echo htmlspecialchars($cake['name']); ?>" onerror="this.src='https://via.placeholder.com/600x400?text=No+Image'">
    </div>
    
    <div class="cake-details-info">
        <h1><?php echo htmlspecialchars($cake['name']); ?></h1>
        
        <div class="cake-price" style="font-size: 2rem; color: var(--primary-color); font-weight: 700; margin-bottom: 20px;">
            <?php echo formatPrice($cake['price']); ?>
        </div>
        
        <div class="cake-details-meta">
            <p><strong>Flavor:</strong> <?php echo htmlspecialchars($cake['flavor']); ?></p>
            <p><strong>Size/Weight:</strong> <?php echo htmlspecialchars($cake['weight']); ?></p>
            <p><strong>Category:</strong> <?php echo htmlspecialchars($cake['category']); ?></p>
        </div>
        
        <div class="cake-desc">
            <p><?php echo nl2br(htmlspecialchars($cake['description'] ?? 'No description available.')); ?></p>
        </div>
        
        <div style="margin-top: 30px;">
            <a href="/Cake_Verse/customer/checkout.php?cake_id=<?php echo $cake['id']; ?>" class="btn btn-block" style="font-size: 1.2rem; padding: 15px;">Order This Cake</a>
            <a href="/Cake_Verse/customer/catalog.php" class="btn btn-secondary btn-block mt-2">Back to Catalog</a>
        </div>
    </div>
</div>

<?php include __DIR__ . '/../includes/footer.php'; ?>
