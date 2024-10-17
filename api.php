<?php
header("Content-Type: application/json");
require 'db.php';

$method = $_SERVER['REQUEST_METHOD'];

switch ($method) {
    
    case 'POST':
        $name = $_POST['name'];
        $description = $_POST['description'];
        $price = $_POST['price'];

        if (!empty($name) && !empty($price)) {
            $stmt = $conn->prepare("INSERT INTO services (name, description, price) VALUES (?, ?, ?)");
            $stmt->bind_param("ssd", $name, $description, $price);
            $stmt->execute();
            echo json_encode(["message" => "Service added successfully"]);
        } else {
            echo json_encode(["message" => "Name and price are required"]);
        }
        break;

    
    case 'GET':
        $result = $conn->query("SELECT * FROM services");
        $services = $result->fetch_all(MYSQLI_ASSOC);
        echo json_encode($services);
        break;

    
    case 'PUT':
        parse_str(file_get_contents("php://input"), $_PUT);
        $id = $_PUT['id'];
        $name = $_PUT['name'];
        $description = $_PUT['description'];
        $price = $_PUT['price'];

        if (!empty($id) && !empty($name) && !empty($price)) {
            $stmt = $conn->prepare("UPDATE services SET name = ?, description = ?, price = ? WHERE id = ?");
            $stmt->bind_param("ssdi", $name, $description, $price, $id);
            $stmt->execute();
            echo json_encode(["message" => "Service updated successfully"]);
        } else {
            echo json_encode(["message" => "ID, name, and price are required"]);
        }
        break;

    
    case 'DELETE':
        parse_str(file_get_contents("php://input"), $_DELETE);
        $id = $_DELETE['id'];

        if (!empty($id)) {
            $stmt = $conn->prepare("DELETE FROM services WHERE id = ?");
            $stmt->bind_param("i", $id);
            $stmt->execute();
            echo json_encode(["message" => "Service deleted successfully"]);
        } else {
            echo json_encode(["message" => "ID is required"]);
        }
        break;

    default:
        echo json_encode(["message" => "Invalid request"]);
        break;
}
?>
