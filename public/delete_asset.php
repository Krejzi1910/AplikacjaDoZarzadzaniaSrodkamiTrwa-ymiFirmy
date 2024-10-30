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
$asset_id = $data['id'];

if (isset($asset_id)) {
    $checkSql = "SELECT * FROM assets WHERE id = $asset_id";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        $sql = "DELETE FROM assets WHERE id = $asset_id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Środek trwały został usunięty"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Błąd podczas usuwania: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Nie znaleziono środka trwałego o podanym ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID środka trwałego nie zostało przekazane"]);
}

$conn->close();
?>
