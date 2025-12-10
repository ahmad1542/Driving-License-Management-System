<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$message = "";
$licenseData = null;
$licenseTypes = $conn->query("select LTID, LTName from LicenseType");

if (isset($_POST["search"])) {
    $licenseNumber = $_POST["licenseNumber"];

    $query = "select c.CustID, c.LicenseNumber, c.LTID, c.FirstIssueDate, c.ExpireDate, l.IssueDate
              from CustLic c
              join License l on c.LicenseNumber = l.LicenseNumber
              where c.LicenseNumber = '$licenseNumber'";

    $result = $conn->query($query);

    if ($result->num_rows > 0) {
        $licenseData = $result->fetch_assoc();
    } else {
        $message = "<div class='alert alert-danger'>License not found!</div>";
    }
}

// STEP 2 — PERFORM UPGRADE
if (isset($_POST["upgrade"])) {

    $cust = $_POST["custId"];
    $licenseNumber = $_POST["licenseNumber"];
    $oldExpire = $_POST["oldExpire"];
    $oldLTID = $_POST["oldLTID"];
    $oldIssue = $_POST["oldIssue"];
    $newLTID = $_POST["newLTID"];
    $newExpire = $_POST["newExpire"];
    $today = date("Y-m-d");

    // Prevent choosing same LTID
    if ($oldLTID == $newLTID) {
        $message = "<div class='alert alert-danger'>New license type must be DIFFERENT from the current type!</div>";
    }
    else {
        // Check if customer passed both tests (Theory + Practical)
        $testCheck = $conn->query("
            SELECT COUNT(*) AS PassedTests 
            FROM Test 
            WHERE CustomerID = '$cust' AND LTID = '$newLTID' AND Grade >= 18
        "); // 18 means pass (adjust your logic)

        $passed = $testCheck->fetch_assoc()["PassedTests"];

        if ($passed < 2) {
            $message = "<div class='alert alert-warning'>
                           Customer did NOT pass both tests for this license type!
                        </div>";
        } else {
            // Get next UpdateID
            $resNext = $conn->query("
                SELECT IFNULL(MAX(UpdateID),0) + 1 AS NextID
                FROM LicenseUpdate
                WHERE LicenseNumber = '$licenseNumber'
            ");
            $updateId = $resNext->fetch_assoc()["NextID"];

            // Insert OLD data into LicenseUpdate
            $insertHistory = "
                INSERT INTO LicenseUpdate (LicenseNumber, UpdateID, LTID, IssueDate, ExpireDate)
                VALUES ('$licenseNumber', '$updateId', '$oldLTID', '$oldIssue', '$oldExpire')
            ";

            // Update License → new IssueDate
            $updateLicense = "
                UPDATE License SET IssueDate = '$today'
                WHERE LicenseNumber = '$licenseNumber'
            ";

            // Update CustLic → new LicenseType + new expire date
            $updateCustLic = "
                UPDATE CustLic 
                SET LTID = '$newLTID', ExpireDate = '$newExpire'
                WHERE LicenseNumber = '$licenseNumber'
            ";

            if ($conn->query($insertHistory) &&
                $conn->query($updateLicense) &&
                $conn->query($updateCustLic)) {

                $message = "<div class='alert alert-success'>License upgraded successfully!</div>";
            }
            else {
                $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
            }
        }
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Upgrade License</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
    <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">🚗 Driving License Management System</a>

    <div class="d-flex ms-auto align-items-center gap-3">

        <?php if ($_SESSION['role'] === "Admin"): ?>
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">🔑 Admin</span>
        <?php endif; ?>

        <span class="fw-semibold"><?= $_SESSION['username'] ?></span>

        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="fw-bold mb-3 text-primary">⬆️ Upgrade License</h2>

    <?= $message ?>

    <!-- SEARCH LICENSE -->
    <div class="card shadow p-4 mb-4">
        <h4 class="mb-3">Search License</h4>

        <form method="POST">
            <label class="form-label">Enter License Number:</label>
            <input type="text" name="licenseNumber" class="form-control" required>
            <button class="btn btn-primary mt-3" name="search">Search</button>
        </form>
    </div>

    <!-- SHOW LICENSE DETAILS -->
    <?php if ($licenseData): ?>
        <div class="card shadow p-4 mb-4">
            <h4 class="mb-3">Current License Details</h4>

            <form method="POST">

                <div class="row">
                    <div class="col-md-4 mb-3">
                        <label>Customer ID</label>
                        <input type="text" class="form-control" value="<?= $licenseData['CustID'] ?>" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>License Number</label>
                        <input type="text" class="form-control" value="<?= $licenseData['LicenseNumber'] ?>" readonly>
                    </div>

                    <div class="col-md-4 mb-3">
                        <label>Current LTID</label>
                        <input type="text" class="form-control" value="<?= $licenseData['LTID'] ?>" readonly>
                    </div>
                </div>

                <h5 class="mt-3">Upgrade To:</h5>
                <select class="form-select" name="newLTID" required>
                    <option value="">-- Select New License Type --</option>
                    <?php while ($lt = $licenseTypes->fetch_assoc()): ?>
                        <option value="<?= $lt['LTID'] ?>"><?= $lt['LTName'] ?></option>
                    <?php endwhile; ?>
                </select>

                <label class="form-label mt-3">New Expire Date</label>
                <input type="date" name="newExpire" class="form-control" required>

                <!-- Hidden fields -->
                <input type="hidden" name="custId" value="<?= $licenseData['CustID'] ?>">
                <input type="hidden" name="licenseNumber" value="<?= $licenseData['LicenseNumber'] ?>">
                <input type="hidden" name="oldLTID" value="<?= $licenseData['LTID'] ?>">
                <input type="hidden" name="oldExpire" value="<?= $licenseData['ExpireDate'] ?>">
                <input type="hidden" name="oldIssue" value="<?= $licenseData['IssueDate'] ?>">

                <button class="btn btn-success w-100 mt-4" name="upgrade">Upgrade License</button>

            </form>
        </div>
    <?php endif; ?>

</div>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>
</html>
