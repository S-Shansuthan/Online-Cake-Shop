<?php
// api/cakes/get-cakes.php - API for fetching cakes (Member 1)
header('Content-Type: application/json');
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/functions.php';

$dbConnection = getDBConnection();

$search = isset($_GET['search']) ? '%' . $_GET['search'] . '%' : '%';
$category = isset($_GET['category']) ? $_GET['category'] : '';

$sql = "SELECT * FROM cakes WHERE availability = TRUE AND name LIKE ?";
$params = [$search];
$types = "s";

if (!empty($category)) {
    $sql .= " AND category = ?";
    $params[] = $category;
    $types .= "s";
}

$sql .= " ORDER BY created_at DESC";

$preparedStatement = $dbConnection->prepare($sql);
$preparedStatement->bind_param($types, ...$params);
$preparedStatement->execute();
$result = $preparedStatement->get_result();

$cakes = [];
while ($row = $result->fetch_assoc()) {
    $row['formatted_price'] = formatPrice($row['price']);
    $cakes[] = $row;
}

echo json_encode(['success' => true, 'data' => $cakes]);

$preparedStatement->close();
$dbConnection->close();
?>
