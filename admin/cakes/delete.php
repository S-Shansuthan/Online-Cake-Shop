<?php
// admin/cakes/delete.php - Delete Cake (Member 3)
require_once __DIR__ . '/../../config/database.php';
require_once __DIR__ . '/../../includes/auth.php';
require_once __DIR__ . '/../../includes/functions.php';

requireAdminAuth();

$id = getSanitizedQueryInput('id');
if ($id && is_numeric($id)) {
    $dbConnection = getDBConnection();
    
    // Check if cake is used in orders before deleting
    // In a real system, you might do soft deletes. For this practical, we'll allow cascade delete 
    // as defined in the DB schema, or restrict it depending on requirements.
    // The schema uses ON DELETE CASCADE for order_items.
    
    $preparedStatement = $dbConnection->prepare("DELETE FROM cakes WHERE id = ?");
    $preparedStatement->bind_param("i", $id);
    $preparedStatement->execute();
    $preparedStatement->close();
    $dbConnection->close();
}

redirect('/Cake_Verse/admin/cakes/index.php?success=1');
?>
