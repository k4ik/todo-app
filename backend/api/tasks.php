<?php 

header("Access-Control-Allow-Origin: *");
header("Access-Control-Allow-Methods: GET, POST, DELETE");
header("Access-Control-Allow-Headers: Content-Type");


$tasks = array();

function getTasks() {
    global $tasks;
    return $tasks;
}

function addTask($task) {
    global $tasks;
    $tasks[] = $task;
}

function deleteTask($id) {
    global $tasks;
    if (isset($tasks[$id])) {
        unset($tasks[$id]);
    }
}

$method = $_SERVER["REQUEST_METHOD"];

switch($method) {
    case 'GET':
        echo json_encode(getTasks());
        break;
    case 'POST':
        $data = json_decode(file_get_contents('php://input'), true);
        addTask($data['task']);
        echo json_encode(array("message" => "Task added"));
        break;
    case 'DELETE':
        $id = $_GET['id'];
        deleteTask($id);
        echo json_encode(array("message" => "Task deleted"));
        break;
}

?>