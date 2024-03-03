<?php
require './conn.php';
header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");

$method = $_SERVER["REQUEST_METHOD"];

switch($method) {
    case 'GET':
        $sql = "SELECT * FROM tasks";
        $result = $conn->query($sql);

        $tasks = array();
        if ($result->num_rows > 0) {
            while ($row = $result->fetch_assoc()) {
                $tasks[] = $row;
            }
            echo json_encode($tasks);
        } 
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);

        if (isset($data['task'])) {
            $task = $data['task'];
            $sql = "INSERT INTO tasks (task) VALUES ('$task')";
            
            if ($conn->query($sql) === TRUE) {
                echo json_encode(array("message" => "Task created successfully"));
            } else {
                echo json_encode(array("message" => "Error creating task: " . $conn->error));
            }
        } else {
            echo json_encode(array("message" => "Task field is required"));
        }
        break;
    case 'DELETE':
        $data = json_decode(file_get_contents('php://input'), true);
    
        if (isset($data['id'])) {
            $id = $data['id'];
            $sql = "DELETE FROM tasks WHERE id = $id";
    
            if ($conn->query($sql) === TRUE) {
                echo json_encode(array("message" => "Task deleted successfully"));
            } else {
                echo json_encode(array("message" => "Error deleting task: " . $conn->error));
            }
        } else {
            echo json_encode(array("message" => "ID field is required"));
        }
        break;
}

$conn->close();
?>
