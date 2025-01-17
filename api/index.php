<?php
// Incluir archivos necesarios
require_once '../config/Database.php';
require_once '../src/models/User.php';

// Configurar headers
header("Access-Control-Allow-Origin: *");
header("Content-Type: application/json; charset=UTF-8");
header("Access-Control-Allow-Methods: POST");
header("Access-Control-Allow-Headers: Content-Type, Access-Control-Allow-Headers, Authorization, X-Requested-With");

// Obtener conexión a la base de datos
$database = new Config\Database();
$db = $database->getConnection();

// Instanciar objeto usuario
$user = new Models\User($db);

// Obtener datos enviados
$data = json_decode(file_get_contents("php://input"));

// Determinar la ruta
$request_uri = $_SERVER['REQUEST_URI'];
$endpoint = basename($request_uri);

// Procesar según el endpoint
switch($endpoint) {
    case 'register':
        if(!empty($data->username) && !empty($data->password)) {
            $result = $user->register($data->username, $data->password);
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Datos incompletos"
            ]);
        }
        break;

    case 'login':
        if(!empty($data->username) && !empty($data->password)) {
            $result = $user->login($data->username, $data->password);
            http_response_code(200);
            echo json_encode($result);
        } else {
            http_response_code(400);
            echo json_encode([
                "status" => "error",
                "message" => "Datos incompletos"
            ]);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode([
            "status" => "error",
            "message" => "Endpoint no encontrado"
        ]);
}
?>