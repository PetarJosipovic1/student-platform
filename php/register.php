<?php
session_start();
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../html/register.html");
  exit;
}

$fullName = trim($_POST["full_name"] ?? "");
$email    = trim($_POST["email"] ?? "");
$pass     = trim($_POST["pass"] ?? "");

if ($fullName === "" || $email === "" || $pass === "") {
  header("Location: ../html/register.html?err=1");
  exit;
}

if (strlen($pass) < 8) {
  header("Location: ../html/register.html?err=2");
  exit;
}

/* provjera da li email postoji */
$stmt = mysqli_prepare($conn, "SELECT id FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) === 1) {
  mysqli_stmt_close($stmt);
  header("Location: ../html/register.html?err=3");
  exit;
}
mysqli_stmt_close($stmt);

/* hash lozinke */
$hash = password_hash($pass, PASSWORD_DEFAULT);

/* upis u bazu */
$stmt2 = mysqli_prepare(
  $conn,
  "INSERT INTO users (full_name, email, password) VALUES (?, ?, ?)"
);
mysqli_stmt_bind_param($stmt2, "sss", $fullName, $email, $hash);

if (!mysqli_stmt_execute($stmt2)) {
  mysqli_stmt_close($stmt2);
  header("Location: ../html/register.html?err=4");
  exit;
}
mysqli_stmt_close($stmt2);

/* opcionalno: auto-login nakon registracije */
$_SESSION["user_id"] = mysqli_insert_id($conn);
$_SESSION["full_name"] = $fullName;

/* uspjeh → dashboard */
header("Location: ../html/dashboard.html");
exit;
