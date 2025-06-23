<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["admin"])) {
  header("Location: index.php");
  exit();
}

if (isset($_GET['id'])) {
  $id = $_GET['id'];
  $sql = "DELETE FROM userinfo WHERE id = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "i", $id);
  mysqli_stmt_execute($stmt);
}

header("Location: admin_dashboard.php");
exit();
?>
