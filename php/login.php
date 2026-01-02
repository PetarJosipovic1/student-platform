<?php
session_start();
require_once __DIR__ . "/db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
  header("Location: ../html/login.html");
  exit;
}

$email = trim($_POST["email"] ?? "");
$pass  = trim($_POST["pass"] ?? "");

if ($email === "" || $pass === "") {
  header("Location: ../html/login.html?err=1");
  exit;
}

$stmt = mysqli_prepare($conn, "SELECT id, full_name, password FROM users WHERE email = ? LIMIT 1");
mysqli_stmt_bind_param($stmt, "s", $email);
mysqli_stmt_execute($stmt);
$res = mysqli_stmt_get_result($stmt);

if ($res && mysqli_num_rows($res) === 1) {
  $user = mysqli_fetch_assoc($res);

  // password je hash iz baze
  if (password_verify($pass, $user["password"])) {
    $_SESSION["user_id"] = $user["id"];
    $_SESSION["full_name"] = $user["full_name"];

    header("Location: ../html/dashboard.html");
    exit;
  }
}

header("Location: ../html/login.html?err=1");
exit;
