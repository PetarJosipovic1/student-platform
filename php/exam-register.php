<?php
session_start();
require_once __DIR__ . "/db.php";

header("Content-Type: application/json; charset=utf-8");

if (!isset($_SESSION["user_id"])) {
  http_response_code(401);
  echo json_encode(["ok" => false, "message" => "Nisi ulogovan."]);
  exit;
}

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  http_response_code(405);
  echo json_encode(["ok" => false, "message" => "Neispravan zahtev."]);
  exit;
}

$userId = (int)$_SESSION["user_id"];
$subjectId = (int)($_POST["subject_id"] ?? 0);
$examDate = trim($_POST["exam_date"] ?? "");

if ($subjectId <= 0 || $examDate === "") {
  echo json_encode(["ok" => false, "message" => "Popuni sva polja."]);
  exit;
}

/* basic validacija datuma: YYYY-MM-DD */
if (!preg_match('/^\d{4}-\d{2}-\d{2}$/', $examDate)) {
  echo json_encode(["ok" => false, "message" => "Neispravan format datuma."]);
  exit;
}

/* spriječi duplu prijavu za isti predmet i datum */
$stmtCheck = mysqli_prepare(
  $conn,
  "SELECT id FROM exam_registrations
   WHERE user_id = ? AND subject_id = ? AND exam_date = ?
   LIMIT 1"
);
mysqli_stmt_bind_param($stmtCheck, "iis", $userId, $subjectId, $examDate);
mysqli_stmt_execute($stmtCheck);
$res = mysqli_stmt_get_result($stmtCheck);

if ($res && mysqli_num_rows($res) === 1) {
  mysqli_stmt_close($stmtCheck);
  echo json_encode(["ok" => false, "message" => "Već si prijavio ovaj ispit za izabrani datum."]);
  exit;
}
mysqli_stmt_close($stmtCheck);

/* insert */
$stmt = mysqli_prepare(
  $conn,
  "INSERT INTO exam_registrations (user_id, subject_id, exam_date, status)
   VALUES (?, ?, ?, 'pending')"
);
mysqli_stmt_bind_param($stmt, "iis", $userId, $subjectId, $examDate);

if (mysqli_stmt_execute($stmt)) {
  mysqli_stmt_close($stmt);
  echo json_encode(["ok" => true, "message" => "Ispit je uspješno prijavljen."]);
} else {
  mysqli_stmt_close($stmt);
  echo json_encode(["ok" => false, "message" => "Greška pri upisu u bazu."]);
}
exit;
