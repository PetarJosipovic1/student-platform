<?php
$host = "localhost";
$user = "root";
$pass = "";
$dbname = "student_platform";

$conn = mysqli_connect($host, $user, $pass, $dbname);

if (!$conn) {
  die("Greška pri konekciji sa bazom.");
}

// charset za naša slova
mysqli_set_charset($conn, "utf8");
