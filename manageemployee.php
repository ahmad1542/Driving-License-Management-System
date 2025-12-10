<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require "config.php";

$employee = null;
$notFound = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    $EmpID = trim($_POST['EmpID']);

    $stmt = $conn->prepare("SELECT * FROM Employee WHERE EmployeeID = ?");
    $stmt->bind_param("i", $EmpID);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        $employee = $result->fetch_assoc();
    } else {
        $notFound = true;
    }
}
?>

<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Employees</title>

    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css">

    <style>
        .big-space {
            margin-top: 80px !important;
        }

        body {
            background: linear-gradient(135deg, #d0f5ee, #e8f2ff);
            font-family: Arial, sans-serif;
            padding-top: 100px;
        }

        .search-card {
            max-width: 500px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .icon-btn {
            border: none;
            background: #0a57d0;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            width: 100%;
            transition: 0.2s;
        }

        .icon-btn:hover {
            background: #0849a8;
        }

        .result-card {
            max-width: 450px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 15px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .delete-btn {
            background: #d9534f;
            color: white;
            padding: 8px 15px;
            border: none;
            border-radius: 8px;
            width: 100%;
        }

        .delete-btn:hover {
            background: #b32d2a;
        }
    </style>
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm fixed-top w-100" style="padding: 0;">
        <div class="container-fluid d-flex justify-content-between align-items-center" style="padding: .7rem 1rem;">

            <a class="navbar-brand fw-bold text-primary fs-4 m-0" href="dashboard.php">
                🚗 Driving License Management System
            </a>

            <div class="d-flex align-items-center gap-3">

                <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "Admin"): ?>
                    <span class="badge bg-danger rounded-pill px-3 py-2 fs-6 m-0">🔑 Admin</span>
                <?php endif; ?>

                <span class="fw-semibold m-0"><?= $_SESSION['username'] ?></span>

                <a href="logout.php" class="btn btn-outline-danger">Logout</a>

            </div>

        </div>
    </nav>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 1): ?>
        <div class="alert alert-success text-center">Employee deleted successfully.</div>
    <?php endif; ?>

    <?php if (isset($_GET['deleted']) && $_GET['deleted'] == 0): ?>
        <div class="alert alert-danger text-center">Failed to delete employee.</div>
    <?php endif; ?>

    <div class="search-card">
        <h3 class="text-center mb-3" style="color:#003a7a;">👨‍💼 Manage Employees</h3>

        <form method="POST">

            <div class="input-group mb-3">
                <input type="text" name="EmpID" class="form-control" placeholder="Enter Employee ID..." inputmode="numeric" pattern="[0-9]*" required>
                <button type="submit" class="btn btn-primary" style="border-radius: 0 8px 8px 0;">🔍 search</button>
            </div>
        </form>

        <button class="icon-btn w-100 big-space" onclick="window.location='addemployee.php'" title="Add Employee">
            ➕ Add Employee
        </button>
    </div>

    <?php if ($employee): ?>
        <div class="result-card">
            <h5 class="text-center mb-3">Employee Found</h5>

            <p><strong>ID:</strong> <?= $employee['EmployeeID'] ?></p>
            <p><strong>First Name:</strong> <?= $employee['FirstName'] ?></p>
            <p><strong>Second Name:</strong> <?= $employee['SecondName'] ?></p>
            <p><strong>Last Name:</strong> <?= $employee['LastName'] ?></p>

            <button class="delete-btn mt-3"
                onclick="if(confirm('Delete this employee?')) window.location='deleteemployee.php?id=<?= $employee['EmployeeID'] ?>'">
                🗑 Delete
            </button>

            <button class="icon-btn mt-2"
                onclick="window.location='editemployee.php?id=<?= $employee['EmployeeID'] ?>'">
                ✏️ Edit
            </button>
        </div>
    <?php endif; ?>

    <?php if ($notFound): ?>
        <div class="result-card text-center">
            <h5>No Employee Found</h5>
            <p>The Employee ID you searched for does not exist.</p>
        </div>
    <?php endif; ?>

</body>

</html>