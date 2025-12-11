<?php
session_start();
require "config.php";

if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

if (!isset($_GET['id'])) {
    die("No employee selected.");
}

$isAdmin = ($_SESSION['role'] === "Admin");
$EmployeeID = $_GET['id'];


if ($roleRow) {

    $roleName = $_SESSION['role'];

    if ($roleName === "Admin") {
        header("Location: manageemployee.php?error=cannot_delete_admin");
        exit();
    }
}

$conn->query("delete from Email where EmployeeID = '$EmployeeID'");
$deleted = $conn->query("delete from Employee where EmployeeID = '$EmployeeID'");

if ($deleted) {
    header("Location: manageemployee.php?deleted=1");
    exit;
} else {
    header("Location: manageemployee.php?deleted=0");
    exit;
}
?>
