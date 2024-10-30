<?php
header('Content-Type: application/json; charset=utf-8');
require_once '../config/database.php';
require_once '../src/AssetController.php';

$controller = new AssetController($conn);

if (isset($_GET['id'])) {
    $id = intval($_GET['id']);
    $controller->getAssetById($id); 
} else {
    $controller->getAssets(); 
}
?>
