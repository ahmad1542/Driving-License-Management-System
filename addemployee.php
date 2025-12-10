<?php
require "config.php";

$success = false;
$error   = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $EmployeeID = trim($_POST['EmployeeID']);
    $FirstName  = trim($_POST['FirstName']);
    $SecondName = trim($_POST['SecondName']);
    $LastName   = trim($_POST['LastName']);

    // INSERT EMPLOYEE (with manual ID)
    $stmt = $conn->prepare("INSERT INTO Employee (EmployeeID, FirstName, SecondName, LastName) VALUES (?, ?, ?, ?)");
    $stmt->bind_param("isss", $EmployeeID, $FirstName, $SecondName, $LastName);

    if ($stmt->execute()) {

        // INSERT MULTI EMAILS
        if (!empty($_POST['emails'])) {
            foreach ($_POST['emails'] as $email) {

                $email = trim($email);

                if ($email !== "") {
                    $stmt2 = $conn->prepare("INSERT INTO Email (Email, EmployeeID) VALUES (?, ?)");
                    $stmt2->bind_param("si", $email, $EmployeeID);
                    $stmt2->execute();
                }
            }
        }

        $success = true;

    } else {
        $error = true;
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Employee</title>

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
    <h3 class="text-center mb-3" style="color:#003a7a;">➕ Add Employee</h3>

    <?php if ($success): ?>
        <div class="alert alert-success">Employee added successfully.</div>
    <?php endif; ?>

    <?php if ($error): ?>
        <div class="alert alert-danger">Error adding employee.</div>
    <?php endif; ?>

    <form method="POST">

        <div class="mb-3">
            <label class="form-label">Employee ID</label>
            <input type="number" name="EmployeeID" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">First Name</label>
            <input type="text" name="FirstName" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Second Name</label>
            <input type="text" name="SecondName" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Last Name</label>
            <input type="text" name="LastName" class="form-control" required>
        </div>

        <div class="mb-2" id="emailsArea">
            <label class="form-label">Emails</label>

            <div class="input-group mb-2">
                <input type="email" name="emails[]" class="form-control" placeholder="Enter email address">
            </div>
        </div>

        <button type="button" class="btn btn-secondary mb-3" onclick="addEmail()">
            ➕ Add Another Email
        </button>

        <button type="submit" class="icon-btn">
            💾 Save Employee
        </button>

        <button type="button" class="icon-btn mt-3"
                onclick="window.location='manage_employee.php'">
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