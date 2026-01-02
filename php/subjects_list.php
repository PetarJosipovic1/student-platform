<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["user_id"])) {
  http_response_code(401);
  echo json_encode([]);
  exit;
}

$result = mysqli_query(
  $conn,
  "SELECT id, name FROM subjects ORDER BY semester, name"
);

$list = [];
if ($result) {
  while ($row = mysqli_fetch_assoc($result)) {
    $list[] = $row;
  }
}

echo json_encode($list);
exit;
