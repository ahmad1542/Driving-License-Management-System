<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$message = "";
$licenseData = null;

// --------------------------------------
// SEARCH LICENSE BY LICENSE NUMBER
// --------------------------------------
if (isset($_POST["search"])) {

    $licenseNumber = $_POST["licenseNumber"];

    $stmt = $conn->prepare("
        SELECT c.CustID, c.LicenseNumber, c.LTID, c.FirstIssueDate, 
               c.ExpireDate, l.IssueDate
        FROM CustLic c
        JOIN License l ON c.LicenseNumber = l.LicenseNumber
        WHERE c.LicenseNumber = ?
    ");
    $stmt->bind_param("i", $licenseNumber);
    $stmt->execute();
    $licenseData = $stmt->get_result()->fetch_assoc();

    if (!$licenseData) {
        $message = "<div class='alert alert-danger'>❌ License not found!</div>";
    }
}

// --------------------------------------
// HANDLE RENEW LICENSE
// --------------------------------------
if (isset($_POST["renew"])) {

    $cust = $_POST["custId"];
    $licenseNumber = $_POST["licenseNumber"];

    $oldIssue  = $_POST["oldIssue"];
    $oldExpire = $_POST["oldExpire"];
    $ltid      = $_POST["ltid"];
    $newExpire = $_POST["newExpire"];

    $today = date("Y-m-d");

    // 1) Determine next UpdateID
    $stmt = $conn->prepare("
        SELECT IFNULL(MAX(UpdateID),0)+1 AS NextID
        FROM LicenseUpdate
        WHERE LicenseNumber = ?
    ");
    $stmt->bind_param("i", $licenseNumber);
    $stmt->execute();
    $updateId = $stmt->get_result()->fetch_assoc()["NextID"];

    // 2) Insert old values into LicenseUpdate
    $stmt = $conn->prepare("
        INSERT INTO LicenseUpdate (LicenseNumber, UpdateID, LTID, IssueDate, ExpireDate)
        VALUES (?, ?, ?, ?, ?)
    ");
    $stmt->bind_param("iiiss",
            $licenseNumber,
            $updateId,
            $ltid,
            $oldIssue,
            $oldExpire
    );
    $stmt->execute();

    // 3) Update License → new IssueDate
    $stmt = $conn->prepare("
        UPDATE License
        SET IssueDate = ?
        WHERE LicenseNumber = ?
    ");
    $stmt->bind_param("si", $today, $licenseNumber);
    $stmt->execute();

    // 4) Update CustLic → new ExpireDate
    $stmt = $conn->prepare("
        UPDATE CustLic
        SET ExpireDate = ?
        WHERE LicenseNumber = ?
    ");
    $stmt->bind_param("si", $newExpire, $licenseNumber);

    if ($stmt->execute()) {
        $message = "<div class='alert alert-success'>✔ License renewed successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>❌ Error renewing license.</div>";
    }
}
?>


<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Renew License</title>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
    <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">🚗 Driving License Management System</a>

    <div class="d-flex ms-auto align-items-center gap-3">

        <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "Admin"): ?>
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">🔑 Admin</span>
        <?php endif; ?>

        <span class="fw-semibold"><?php echo $_SESSION['username']; ?></span>

        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="fw-bold mb-3 text-primary">🔄 Renew License</h2>

    <?= $message ?>

    <!-- SEARCH CUSTOMER -->
    <div class="card shadow p-4 mb-4">
        <h4 class="mb-3">Search License</h4>

        <form method="POST">
            <label class="form-label">Enter License Number:</label>
            <input type="text" name="licenseNumber" class="form-control" required>

            <button class="btn btn-primary mt-3" name="search">Search</button>
        </form>
    </div>

    <!-- SHOW RESULTS -->
    <?php if ($licenseData): ?>
        <div class="card shadow p-4 mb-4">
            <h4 class="mb-3">Current License Details</h4>

            <form method="POST">

                <div class="row">
                    <div class="col-md-6 mb-3">
                        <label>Customer ID</label>
                        <input type="text" class="form-control" value="<?= $licenseData['CustID'] ?>" readonly>
                    </div>

                    <div class="col-md-6 mb-3">
                        <label>License Number</label>
                        <input type="text" class="form-control" value="<?= $licenseData['LicenseNumber'] ?>" readonly>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>License Type (LTID)</label>
                        <input type="text" class="form-control" value="<?= $licenseData['LTID'] ?>" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>First Issue Date</label>
                        <input type="text" class="form-control" value="<?= $licenseData['FirstIssueDate'] ?>" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Current Expire Date</label>
                        <input type="text" class="form-control" value="<?= $licenseData['ExpireDate'] ?>" readonly>
                    </div>
                </div>

                <div class="mt-3">
                    <label>New Expire Date</label>
                    <input type="date" name="newExpire" class="form-control" required>
                </div>

                <!-- Hidden Fields for Update -->
                <input type="hidden" name="custId" value="<?= $licenseData['CustID'] ?>">
                <input type="hidden" name="licenseNumber" value="<?= $licenseData['LicenseNumber'] ?>">
                <input type="hidden" name="ltid" value="<?= $licenseData['LTID'] ?>">
                <input type="hidden" name="oldExpire" value="<?= $licenseData['ExpireDate'] ?>">
                <input type="hidden" name="oldIssue" value="<?= $licenseData['IssueDate'] ?>">

                <button class="btn btn-success w-100 mt-4" name="renew">Renew License</button>

            </form>
        </div>
    <?php endif; ?>

</div>

<script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
</body>
</html>
