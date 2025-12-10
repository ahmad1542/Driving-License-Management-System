<?php
require "config.php";

$success = false;
$error   = false;
$employee = null;
$emails   = [];

if (!isset($_GET['id']) && $_SERVER["REQUEST_METHOD"] !== "POST") {
    die("No employee selected.");
}

// في POST نأخذ EmployeeID من الفورم، في GET نأخذه من الرابط
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $EmployeeID = intval($_POST['EmployeeID']);

    $FirstName  = trim($_POST['FirstName']);
    $SecondName = trim($_POST['SecondName']);
    $LastName   = trim($_POST['LastName']);

    // UPDATE EMPLOYEE
    $stmt = $conn->prepare("UPDATE Employee SET FirstName = ?, SecondName = ?, LastName = ? WHERE EmployeeID = ?");
    $stmt->bind_param("sssi", $FirstName, $SecondName, $LastName, $EmployeeID);

    if ($stmt->execute()) {

        // حذف كل الإيميلات القديمة
        $del = $conn->prepare("DELETE FROM Email WHERE EmployeeID = ?");
        $del->bind_param("i", $EmployeeID);
        $del->execute();

        // إدخال الإيميلات الجديدة
        if (!empty($_POST['emails']) && is_array($_POST['emails'])) {
            foreach ($_POST['emails'] as $email) {
                $email = trim($email);
                if ($email !== "") {
                    $ins = $conn->prepare("INSERT INTO Email (Email, EmployeeID) VALUES (?, ?)");
                    $ins->bind_param("si", $email, $EmployeeID);
                    $ins->execute();
                }
            }
        }

        $success = true;
    } else {
        $error = true;
    }

    $id = $EmployeeID;
} else {
    $id = intval($_GET['id']);
}

// جلب بيانات الموظف
$stmtEmp = $conn->prepare("SELECT * FROM Employee WHERE EmployeeID = ?");
$stmtEmp->bind_param("i", $id);
$stmtEmp->execute();
$resEmp = $stmtEmp->get_result();

if ($resEmp->num_rows > 0) {
    $employee = $resEmp->fetch_assoc();
} else {
    die("Employee not found.");
}

// جلب الإيميلات
$stmtEmails = $conn->prepare("SELECT Email FROM Email WHERE EmployeeID = ?");
$stmtEmails->bind_param("i", $id);
$stmtEmails->execute();
$resEmails = $stmtEmails->get_result();
while ($row = $resEmails->fetch_assoc()) {
    $emails[] = $row['Email'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Employee</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        body {
            background: linear-gradient(135deg, #d0f5ee, #e8f2ff);
            font-family: Arial, sans-serif;
        }

        .card-form {
            max-width: 650px;
            margin: 40px auto;
            padding: 25px;
            background: #ffffff;
            border-radius: 16px;
            box-shadow: 0 4px 12px rgba(0,0,0,0.10);
        }

        .icon-btn {
            border: none;
            background: #0a57d0;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            width: 100%;
            transition: 0.2s;
        }

        .icon-btn:hover {
            background: #0849a8;
        }
    </style>
</head>
<body>

<div class="card-form">
    <h3 class="text-center mb-3" style="color:#003a7a;">✏️ Edit Employee</h3>

    <?php if ($success): ?>
        <div class="alert alert-success">Employee updated successfully.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">Error updating employee.</div>
    <?php endif; ?>

    <form method="POST">
        <input type="hidden" name="EmployeeID" value="<?= htmlspecialchars($employee['EmployeeID']) ?>">

        <div class="mb-3">
            <label class="form-label">Employee ID</label>
            <input type="text" class="form-control" value="<?= htmlspecialchars($employee['EmployeeID']) ?>" disabled>
        </div>

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="FirstName" class="form-control"
                   value="<?= htmlspecialchars($employee['FirstName']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Second Name</label>
            <input type="text" name="SecondName" class="form-control"
                   value="<?= htmlspecialchars($employee['SecondName']) ?>" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="LastName" class="form-control"
                   value="<?= htmlspecialchars($employee['LastName']) ?>" required>
        </div>

        <div class="mb-2" id="emailsArea">
            <label class="form-label">Emails</label>

            <?php if (!empty($emails)): ?>
                <?php foreach ($emails as $email): ?>
                    <div class="input-group mb-2">
                        <input type="email" name="emails[]" class="form-control"
                               value="<?= htmlspecialchars($email) ?>" placeholder="Enter email address">
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <div class="input-group mb-2">
                    <input type="email" name="emails[]" class="form-control"
                           placeholder="Enter email address">
                </div>
            <?php endif; ?>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addEmail()">
            ➕ Add Another Email
        </button>

        <button type="submit" class="icon-btn">
            💾 Save Changes
        </button>

        <button type="button" class="icon-btn mt-3"
                onclick="window.location='manageemployee.php'">
            ↩️ Back to Manage
        </button>

    </form>
</div>

<script>
function addEmail() {
    const area = document.getElementById('emailsArea');
    const div = document.createElement('div');
    div.className = "input-group mb-2";
    div.innerHTML = '<input type="email" name="emails[]" class="form-control" placeholder="Enter email address">';
    area.appendChild(div);
}
</script>

</body>
</html>