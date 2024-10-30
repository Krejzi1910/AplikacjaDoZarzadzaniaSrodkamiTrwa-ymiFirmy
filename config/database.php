<?php
header('Content-Type: application/json; charset=utf-8');
$host = 'localhost';
$db_name = 'asset_managment';
$username = 'root';
$password = 'root';
$charset = 'utf8mb4';

try {
    $conn = new PDO("mysql:host=$host;dbname=$db_name;charset=$charset", $username, $password);
    $conn->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    $conn->exec("set names utf8mb4");
} catch(PDOException $exception) { 
    echo json_encode(["error" => "Connection error: " . $exception->getMessage()]);
}
?>
