<?php
require "config.php";

if (!isset($_GET['id'])) {
    die("No employee selected.");
}

$EmployeeID = intval($_GET['id']);

// حذف الإيميلات المرتبطة بالموظف
$delEmails = $conn->prepare("DELETE FROM Email WHERE EmployeeID = ?");
$delEmails->bind_param("i", $EmployeeID);
$delEmails->execute();

// حذف الموظف نفسه
$delEmp = $conn->prepare("DELETE FROM Employee WHERE EmployeeID = ?");
$delEmp->bind_param("i", $EmployeeID);

if ($delEmp->execute()) {
    // تم الحذف بنجاح
    header("Location: manage_employee.php?deleted=1");
    exit;
} else {
    // فشل الحذف
    header("Location: manage_employee.php?deleted=0");
    exit;
}
?>