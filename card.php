<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require "config.php";

$licenseNumber = $_GET['lic'];
$custID = $_GET['cust'];

// Fetch customer info
$stmt = $conn->prepare("SELECT * FROM customer WHERE CustIDNo = ?");
$stmt->bind_param("s", $custID);
$stmt->execute();
$customer = $stmt->get_result()->fetch_assoc();

// Fetch license info (from custlic + licensetype table)
$stmt = $conn->prepare("
    SELECT c.*, l.LTName 
    FROM custlic c
    JOIN licensetype l ON c.LTID = l.LTID
    WHERE c.LicenseNumber = ?
");
$stmt->bind_param("i", $licenseNumber);
$stmt->execute();
$license = $stmt->get_result()->fetch_assoc();

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Card</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body class="d-flex justify-content-center align-items-center"
      style="min-height:100vh; background:linear-gradient(135deg,#d0f5ee,#e8f2ff);">


<body style="min-height:100vh; background:linear-gradient(135deg,#d0f5ee,#e8f2ff);">

<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3 fixed-top">
    <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">🚗 Driving License Management System</a>

    <div class="d-flex ms-auto align-items-center gap-3">

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "Admin"): ?>
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">🔑 Admin</span>
        <?php endif; ?>

        <span class="fw-semibold"><?php echo $_SESSION['username']; ?></span>

        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</nav>

<div class="d-flex justify-content-center align-items-center flex-column" style="min-height:85vh; padding-top:70px;">

    <div class="text-center">

        <h1 class="mb-4 fw-bold" style="color:#003a7a;">
            🪪 License Card
        </h1>

        <!-- CARD -->
        <div class="mx-auto"
             style="
         width:380px;
         background:linear-gradient(145deg,#f4e6c8,#e9d9b5);
         border-radius:18px;
         padding:18px;
         box-shadow:0 12px 25px rgba(0,0,0,0.12);
         transition:0.4s ease;
         cursor:pointer;
         "
             onmouseover="this.style.transform='scale(1.12)'"
             onmouseout="this.style.transform='scale(1)'">

            <!-- HEADER -->
            <div class="d-flex justify-content-between">
                <div>
                    <h5 class="m-0 fw-bold">DRIVING LICENSE</h5>
                    <p class="m-0" style="font-size:11px;">Palestinian Authority</p>
                </div>

                <div style="
                width:42px; height:30px;
                background:linear-gradient(135deg,gold,#d4af37);
                border-radius:6px;">
                </div>
            </div>

            <!-- BODY -->
            <div class="d-flex justify-content-between mt-3">

                <!-- LEFT INFO COLUMN -->
                <div style="font-size:12px; text-align:left;">

                    <!-- NAME + BIRTHDATE -->
                    <div class="d-flex justify-content-between w-100">
                        <div>
                            <strong>1. </strong> <?= $customer['LName'] ?>
                            <br><strong>2. </strong><?= $customer['FName'] ?>
                        </div>
                    </div>

                    <!-- ISSUE DATE -->
                    <div class="d-flex justify-content-between w-100 mt-1">

                        <div>
                            <strong>3. </strong> <?= $customer['BirthDate'] ?>
                        </div>
                        <div></div>
                        <div><strong>4a. </strong> <?= $license['FirstIssueDate'] ?></div>
                    </div>

                    <!-- EXPIRE + FIRST ISSUE -->
                    <div class="d-flex justify-content-between w-100 mt-1">
                        <div><strong>4b. </strong> <?= $license['ExpireDate'] ?></div>
                        <div><strong>4d. </strong> <?= $license['FirstIssueDate'] ?></div>
                    </div>

                    <!-- LICENSE NUMBER + CUSTOMER ID -->
                    <div class="d-flex justify-content-between w-100 mt-1">
                        <div><strong>5. </strong> <?= $license['LicenseNumber'] ?></div>
                        <div><strong>6. </strong> <?= $customer['CustIDNo'] ?></div>
                    </div>

                    <!-- ADDRESS -->
                    <div class="mt-1">
                        <strong>7. </strong> <?= $customer['Address'] ?>
                    </div>

                    <!-- LICENSE TYPE -->
                    <div class="mt-1">
                        <strong>8. </strong> <?= $license['LTName'] ?>
                    </div>

                    <!-- BLOOD GROUP -->
                    <div class="mt-1">
                        <strong>9. </strong> <?= $customer['BloodGroup'] ?>
                    </div>

                </div>

                <!-- PHOTO -->
                <div style="
                            width:95px;
                            height:125px;
                            overflow:hidden;                 /* hides overflow */
                            border-radius:10px;
                            border:2px solid #666;
                            background:#eee;
                            display:flex;
                            align-items:center;
                            justify-content:center;
                        ">
                    <img src="<?= $_SESSION['customer_photo'] ?>"
                         style="
                                width:100%;
                                height:100%;
                                object-fit:cover;        /* crop elegantly */
                                object-position:center;  /* center the crop */
                             ">
                </div>

            </div>
        </div>

        <p class="mt-2" style="font-size:13px; color:#555;">Hover over the card to zoom ✨</p>

    </div>
</div>

</body>
</html>
