<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

session_start();

require_once __DIR__ . "/config/ldap.php";

$data = json_decode(file_get_contents("php://input"), true);
$username = trim($data["username"] ?? "");
$password = $data["password"] ?? "";

header('Content-Type: application/json');

if (authenticateStudent($username, $password)) {

    $_SESSION["user"] = strtolower($username);

    echo json_encode([
        "status" => "success",
        "user"   => strtolower($username)
    ]);

} else {
    echo json_encode([
        "status" => "error",
        "message" => "Invalid login"
    ]);
}
?>
