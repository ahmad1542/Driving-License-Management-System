<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require "config.php";

$success = false;
$error = false;

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $CustIDNo = trim($_POST['CustIDNo']);
    $FName = trim($_POST['FName']);
    $SName = trim($_POST['SName']);
    $ThName = trim($_POST['ThName']);
    $LName = trim($_POST['LName']);
    $BirthDate = $_POST['BirthDate'];
    $BloodGroup = trim($_POST['BloodGroup']);
    $Address = trim($_POST['Address']);

    unset($_SESSION['customer_photo']);

    if (!empty($_FILES["photo"]["name"])) {
        $fileTmp = $_FILES["photo"]["tmp_name"];

        $imageData = base64_encode(file_get_contents($fileTmp));
        $mime = mime_content_type($fileTmp);

        $_SESSION["customer_photo"] = "data:$mime;base64,$imageData";
    }

    $insertCustomer = $conn->query("insert into Customer (CustIDNo, FName, SName, ThName, LName, BirthDate, BloodGroup, Address)
                                 values ('$CustIDNo', '$FName', '$SName', '$ThName', '$LName', '$BirthDate', '$BloodGroup', '$Address')");

    if ($insertCustomer) {

        if (isset($_POST['phones']) && is_array($_POST['phones'])) {
            foreach ($_POST['phones'] as $phone) {
                $phone = trim($phone);
                if ($phone !== "") {
                    $insertPhone = $conn->query("insert into PhoneNo (PhoneNo, CustID) values ('$phone', '$CustIDNo')");
                }
            }
        }

        $success = true;
    } else {
        $error = true;
    }
}
?>


<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Add Customer</title>

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
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
        }

        .icon-btn {
            border: none;
            background: #0a57d0;
            color: #ffffff;
            padding: 10px 18px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .icon-btn:hover {
            background: #0849a8;
        }
    </style>
</head>

<body>
    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
        <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">⛍ Driving License Management System</a>

        <div class="d-flex ms-auto align-items-center gap-3">

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "Admin"): ?>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">
                    🔑 Admin
                </span>
            <?php endif; ?>

            <span class="fw-semibold text-dark"><?php echo $_SESSION['username']; ?></span>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </nav>

    <div class="card-form">
        <h3 class="text-center mb-3" style="color:#003a7a;">➕ Add Customer</h3>

        <?php if ($success): ?>
            <div class="alert alert-success">Customer added successfully.</div>
        <?php endif; ?>

        <?php if ($error): ?>
            <div class="alert alert-danger">Error adding customer.</div>
        <?php endif; ?>

        <form method="POST" enctype="multipart/form-data">

            <div class="mb-3">
                <label class="form-label">Customer ID</label>
                <input type="text" name="CustIDNo" inputmode="numeric" pattern="[0-9]*"
                    class="form-control" required>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">First Name</label>
                    <input type="text" name="FName" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Second Name</label>
                    <input type="text" name="SName" class="form-control" required>
                </div>
            </div>

            <div class="row">
                <div class="col-md-6 mb-3">
                    <label class="form-label">Third Name</label>
                    <input type="text" name="ThName" class="form-control" required>
                </div>

                <div class="col-md-6 mb-3">
                    <label class="form-label">Last Name</label>
                    <input type="text" name="LName" class="form-control" required>
                </div>
            </div>

            <div class="mb-3">
                <label class="form-label">Birth Date</label>
                <input type="date" name="BirthDate" class="form-control" style="direction:ltr;" required>
            </div>

            <div class="mb-3">
                <label class="form-label">Blood Group</label>
                <select name="BloodGroup" class="form-control" required>
                    <option value="">Select</option>
                    <option>A+</option>
                    <option>A-</option>
                    <option>B+</option>
                    <option>B-</option>
                    <option>O+</option>
                    <option>O-</option>
                    <option>AB+</option>
                    <option>AB-</option>
                </select>
            </div>

            <div class="mb-3">
                <label class="form-label">Customer Photo</label>
                <input type="file" name="photo" accept="image/*" class="form-control">
            </div>

            <div class="mb-3">
                <label class="form-label">Address</label>
                <textarea name="Address" class="form-control" rows="2" required></textarea>
            </div>

            <div class="mb-2" id="phonesArea">
                <label class="form-label">Phone Numbers</label>

                <div class="input-group mb-2">
                    <input type="text" name="phones[]" class="form-control" placeholder="Enter phone number">
                </div>
            </div>

            <button type="button" class="btn btn-secondary mb-3" onclick="addPhone()">
                ➕ Add Another Phone
            </button>

            <button type="submit" class="icon-btn w-100">
                💾 Save Customer
            </button>

            <button type="button" class="icon-btn w-100 mt-3"
                onclick="window.location='managecustomers.php'">
                ↩ Back to Manage
            </button>

        </form>
    </div>

    <script>
        function addPhone() {
            var area = document.getElementById('phonesArea');
            var div = document.createElement('div');
            div.className = "input-group mb-2";
            div.innerHTML = '<input type="text" name="phones[]" class="form-control" placeholder="Enter phone number">';
            area.appendChild(div);
        }
    </script>

</body>

</html>