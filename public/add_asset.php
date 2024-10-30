<?php
header("Content-Type: application/json");

$servername = "localhost";
$username = "root";
$password = "root";
$dbname = "asset_managment";

$conn = new mysqli($servername, $username, $password, $dbname);
if ($conn->connect_error) {
    die(json_encode(["status" => "error", "message" => "Błąd połączenia z bazą danych"]));
}

$data = json_decode(file_get_contents('php://input'), true);

$name = $data['name'];
$location = $data['location'];
$place = $data['place'];
$responsible_person = $data['responsible_person'];

$sql = "INSERT INTO assets (name, location, place, responsible_person) VALUES ('$name', '$location', '$place', '$responsible_person')";

if ($conn->query($sql) === TRUE) {
    echo json_encode(["status" => "success", "message" => "Środek trwały został dodany"]);
} else {
    echo json_encode(["status" => "error", "message" => "Błąd podczas dodawania: " . $conn->error]);
}

$conn->close();
?>
