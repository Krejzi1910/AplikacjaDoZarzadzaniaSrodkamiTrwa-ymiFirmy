<?php
header('Content-Type: application/json; charset=utf-8');

class AssetController {
    private $conn;

    public function __construct($db) {
        $this->conn = $db;
    }

    public function getAssets() {
        try {
            $query = "SELECT * FROM assets";
            $stmt = $this->conn->prepare($query);
            $stmt->execute();
            $assets = $stmt->fetchAll(PDO::FETCH_ASSOC);
            echo json_encode($assets);
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Błąd podczas pobierania aktywów']);
        }
    }

    public function getAssetById($id) {
        try {
            $query = "SELECT * FROM assets WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->execute();
            $asset = $stmt->fetch(PDO::FETCH_ASSOC);
            
            if ($asset) {
                echo json_encode($asset);
            } else {
                http_response_code(404);
                echo json_encode(['message' => 'Nie znaleziono aktywa o podanym ID.']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Błąd podczas pobierania aktywa']);
        }
    }

    public function createAsset() {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->name, $data->location, $data->place, $data->responsible_person)) {
            http_response_code(400);
            echo json_encode(['message' => 'Brak wymaganych danych']);
            return;
        }

        try {
            $query = "INSERT INTO assets (name, location, place, responsible_person) VALUES (:name, :location, :place, :responsible_person)";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':location', $data->location);
            $stmt->bindParam(':place', $data->place);
            $stmt->bindParam(':responsible_person', $data->responsible_person);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Asset został utworzony']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Nie można utworzyć Assetu']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Błąd podczas tworzenia aktywa']);
        }
    }

    public function updateAsset($id) {
        $data = json_decode(file_get_contents("php://input"));

        if (!isset($data->name, $data->location, $data->place, $data->responsible_person)) {
            http_response_code(400);
            echo json_encode(['message' => 'Brak wymaganych danych']);
            return;
        }

        try {
            $query = "UPDATE assets SET name = :name, location = :location, place = :place, responsible_person = :responsible_person WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            $stmt->bindParam(':name', $data->name);
            $stmt->bindParam(':location', $data->location);
            $stmt->bindParam(':place', $data->place);
            $stmt->bindParam(':responsible_person', $data->responsible_person);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Asset został zaktualizowany']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Nie można zaktualizować Assetu']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Błąd podczas aktualizacji aktywa']);
        }
    }

    public function deleteAsset($id) {
        try {
            $query = "DELETE FROM assets WHERE id = :id";
            $stmt = $this->conn->prepare($query);
            $stmt->bindParam(':id', $id);
            if ($stmt->execute()) {
                echo json_encode(['message' => 'Asset został usunięty']);
            } else {
                http_response_code(500);
                echo json_encode(['message' => 'Nie można usunąć Assetu']);
            }
        } catch (Exception $e) {
            http_response_code(500);
            echo json_encode(['message' => 'Błąd podczas usuwania aktywa']);
        }
    }
}
?>
