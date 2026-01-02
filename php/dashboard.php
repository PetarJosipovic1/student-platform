<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["user_id"])) {
    echo json_encode([
        "ok" => false
    ]);
    exit;
}

echo json_encode([
    "ok" => true,
    "name" => $_SESSION["full_name"] ?? "Student",
    "exams" => 0,
    "upcoming" => []
]);
exit;
