<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
require "config.php";

$licenseNumber = $_GET['lic'];
$custID = $_GET['cust'];

$getCustomer = $conn->query("select * from customer where CustIDNo = '$custID'");
$customer = $getCustomer->fetch_assoc();

$getLicenseInfo = $conn->query("select c.*, l.LTName 
                                       from custlic c
                                       join licensetype l on c.LTID = l.LTID
                                       where c.LicenseNumber = '$licenseNumber'");
$license = $getLicenseInfo->fetch_assoc();
$issueDate = $conn->query("select IssueDate from license where LicenseNumber = '$licenseNumber'")->fetch_assoc();

?>

<html>

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
            <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">⛍ Driving License Management System</a>

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

                <div class="mx-auto"
                    style="width:380px; background:#f0e0c0; border-radius:18px; padding:18px; box-shadow:0 12px 25px rgba(0,0,0,0.12); transition:0.7s ease;"
                    onmouseover="this.style.transform='scale(1.9)'"
                    onmouseout="this.style.transform='scale(1)'">

                    <div class="d-flex justify-content-between">
                        <div>
                            <h5 class="m-0 fw-bold">DRIVING LICENSE</h5>
                            <p class="m-0" style="font-size:11px;">Palestinian Authority</p>
                        </div>

                        <div style="width:42px; height:30px; background:#d4af37; border-radius:6px;">
                        </div>
                    </div>

                    <div class="d-flex justify-content-between mt-3">

                        <div style="font-size:12px; text-align:left;">

                            <div class="d-flex justify-content-between w-100">
                                <div>
                                    <strong>1. </strong> <?= $customer['LName'] ?>
                                    <br><strong>2. </strong><?= $customer['FName'] ?>
                                </div>
                            </div>

                            <div class="d-flex justify-content-between w-100 mt-1">

                                <div>
                                    <strong>3. </strong> <?= $customer['BirthDate'] ?>
                                </div>
                                <div></div>
                                <div><strong>4a. </strong> <?= $issueDate['IssueDate'] ?></div>
                            </div>

                            <div class="d-flex justify-content-between w-100 mt-1">
                                <div><strong>4b. </strong> <?= $license['ExpireDate'] ?></div>
                                <div><strong>4d. </strong> <?= $license['FirstIssueDate'] ?></div>
                            </div>

                            <div class="d-flex justify-content-between w-100 mt-1">
                                <div><strong>5. </strong> <?= $license['LicenseNumber'] ?></div>
                                <div><strong>6. </strong> <?= $customer['CustIDNo'] ?></div>
                            </div>

                            <div class="mt-1">
                                <strong>7. </strong> <?= $customer['Address'] ?>
                            </div>

                            <div class="mt-1">
                                <strong>8. </strong> <?= $license['LTName'] ?>
                            </div>

                            <div class="mt-1">
                                <strong>9. </strong> <?= $customer['BloodGroup'] ?>
                            </div>

                        </div>

                        <div style="width:95px; height:125px; overflow:hidden; border-radius:10px; border:2px solid #666; background:#eee;">
                            <img src="<?= $_SESSION['customer_photo'] ?>" style="width:100%; height:100%; object-fit:cover;">
                        </div>

                    </div>
                </div>

                <p class="mt-2" style="font-size:13px; color:#555;">Hover over the card to zoom 🔍</p>

            </div>
        </div>

    </body>

</html>