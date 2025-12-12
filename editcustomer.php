<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$customer = null;
$updated = false;
$notFound = false;

if (isset($_GET['id'])) {
    $id = trim($_GET['id']);

    $result = $conn->query("select * from Customer where CustIDNo = '$id'");

    if ($result->num_rows > 0) {
        $customer = $result->fetch_assoc();
    } else {
        $notFound = true;
    }
}

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    $id = $_POST['id'];
    $fname = $_POST['fname'];
    $sname = $_POST['sname'];
    $thname = $_POST['thname'];
    $lname = $_POST['lname'];
    $birth = $_POST['birth'];
    $blood = $_POST['blood'];
    $address = $_POST['address'];

    if (!empty($_FILES["photo"]["name"])) {

        $fileTmp = $_FILES["photo"]["tmp_name"];

        $imageData = base64_encode(file_get_contents($fileTmp));
        $mime = mime_content_type($fileTmp);

        $_SESSION["customer_photo"] = "data:$mime;base64,$imageData";
    }

    $updateCustomer = $conn->query("update Customer set FName='$fname', SName='$sname', ThName='$thname', LName='$lname', BirthDate='$birth', BloodGroup='$blood', Address='$address'
                                           where CustIDNo='$id'");

    if ($updateCustomer) {
        $updated = true;

        $getCustomer = $conn->query("select * from Customer where CustIDNo='$id'");
        $customer = $getCustomer->fetch_assoc();
    }
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Customer</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background: linear-gradient(135deg, #d0f5ee, #e8f2ff);">

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


    <div class="container">

        <?php if ($notFound): ?>
            <div class="alert alert-danger text-center mt-4">❗ Customer Not Found</div>
        <?php exit;
        endif; ?>

        <?php if ($updated): ?>
            <div class="alert alert-success text-center mt-4">✔ Customer updated successfully!</div>
        <?php endif; ?>

        <div class="card shadow-sm mx-auto mt-5" style="max-width: 600px; border-radius:12px;">
            <div class="card-body p-4">

                <h4 class="text-center mb-4" style="color:#003a7a; font-weight:bold;">
                    ✏️ Edit Customer
                </h4>

                <form method="POST" enctype="multipart/form-data">

                    <input type="hidden" name="id" value="<?= $customer['CustIDNo'] ?>">

                    <label class="form-label">First Name</label>
                    <input type="text" name="fname" class="form-control mb-3" value="<?= $customer['FName'] ?>" required>

                    <label class="form-label">Second Name</label>
                    <input type="text" name="sname" class="form-control mb-3" value="<?= $customer['SName'] ?>" required>

                    <label class="form-label">Third Name</label>
                    <input type="text" name="thname" class="form-control mb-3" value="<?= $customer['ThName'] ?>">

                    <label class="form-label">Last Name</label>
                    <input type="text" name="lname" class="form-control mb-3" value="<?= $customer['LName'] ?>" required>

                    <label class="form-label">Birth Date</label>
                    <input type="date" name="birth" class="form-control mb-3" value="<?= $customer['BirthDate'] ?>" required>

                    <label class="form-label">Blood Group</label>
                    <input type="text" name="blood" class="form-control mb-3" value="<?= $customer['BloodGroup'] ?>" required>

                    <div class="mb-3">
                        <label class="form-label">Current Photo</label><br>

                        <?php if (!empty($_SESSION["customer_photo"])): ?>
                            <img src="<?= $_SESSION["customer_photo"] ?>" width="120" style="border-radius:8px; border:1px solid #ccc;" alt="Customer Photo">
                        <?php else: ?>
                            <p>No photo uploaded</p>
                        <?php endif; ?>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Change Photo</label>
                        <input type="file" name="photo" accept="image/*" class="form-control">
                    </div>

                    <label class="form-label">Address</label>
                    <input type="text" name="address" class="form-control mb-3" value="<?= $customer['Address'] ?>" required>

                    <button class="btn w-100 text-white mt-3"
                        style="background:#0a57d0; font-size:17px; padding:12px;">
                        💾 Save Changes
                    </button>

                </form>

            </div>
        </div>

    </div>

</body>

</html>