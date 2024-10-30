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
$name = $data['name'];
$location = $data['location'];
$place = $data['place']; 
$responsible_person = $data['responsible_person'];

if (isset($asset_id)) {
    $checkSql = "SELECT * FROM assets WHERE id = $asset_id";
    $result = $conn->query($checkSql);

    if ($result->num_rows > 0) {
        $sql = "UPDATE assets SET name='$name', location='$location', place='$place', responsible_person='$responsible_person' WHERE id = $asset_id";

        if ($conn->query($sql) === TRUE) {
            echo json_encode(["status" => "success", "message" => "Środek trwały został zaktualizowany"]);
        } else {
            echo json_encode(["status" => "error", "message" => "Błąd podczas aktualizacji: " . $conn->error]);
        }
    } else {
        echo json_encode(["status" => "error", "message" => "Nie znaleziono środka trwałego o podanym ID."]);
    }
} else {
    echo json_encode(["status" => "error", "message" => "ID środka trwałego nie zostało przekazane"]);
}

$conn->close();
?>
