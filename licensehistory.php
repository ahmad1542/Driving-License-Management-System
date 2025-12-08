<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$message = "";
$customerInfo = null;
$fullHistory = [];

if (isset($_POST["search"])) {

    $licenseNumber = $_POST["licenseNumber"];

    // ---------------------------
    // GET CURRENT LICENSE DETAILS
    // ---------------------------
    $stmt = $conn->prepare("
        SELECT 
            c.CustID,
            c.LTID,
            c.FirstIssueDate,
            c.ExpireDate,
            l.IssueDate
        FROM CustLic c
        JOIN License l ON c.LicenseNumber = l.LicenseNumber
        WHERE c.LicenseNumber = ?
    ");
    $stmt->bind_param("i", $licenseNumber);
    $stmt->execute();
    $customerInfo = $stmt->get_result()->fetch_assoc();

    if (!$customerInfo) {
        $message = "<div class='alert alert-danger'>❌ License not found!</div>";
    } else {

        // Add current issuing record to timeline
        $fullHistory[] = [
                "Type" => "Issued",
                "LTID" => $customerInfo["LTID"],
                "IssueDate" => $customerInfo["IssueDate"],
                "ExpireDate" => $customerInfo["ExpireDate"]
        ];

        // ---------------------------
        // GET ALL RENEW / UPGRADE HISTORY
        // ---------------------------
        $stmt = $conn->prepare("
            SELECT UpdateID, LTID, IssueDate, ExpireDate
            FROM LicenseUpdate
            WHERE LicenseNumber = ?
            ORDER BY UpdateID DESC
        ");
        $stmt->bind_param("i", $licenseNumber);
        $stmt->execute();
        $updates = $stmt->get_result();

        while ($row = $updates->fetch_assoc()) {
            $fullHistory[] = [
                    "Type" => "Updated",
                    "LTID" => $row["LTID"],
                    "IssueDate" => $row["IssueDate"],
                    "ExpireDate" => $row["ExpireDate"]
            ];
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>License Full History</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>
<body>

<!-- NAVBAR -->
<nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
    <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">
        🚗 Driving License Management System
    </a>

    <div class="d-flex ms-auto align-items-center gap-3">
        <?php if ($_SESSION['role'] === "Admin"): ?>
            <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">🔑 Admin</span>
        <?php endif; ?>
        <span class="fw-semibold"><?= $_SESSION['username'] ?></span>
        <a href="logout.php" class="btn btn-outline-danger">Logout</a>
    </div>
</nav>

<div class="container mt-4">

    <h2 class="fw-bold mb-3 text-primary">📜 Full License History</h2>

    <?= $message ?>

    <!-- SEARCH FORM -->
    <div class="card shadow p-4 mb-4">
        <h4>Search License</h4>
        <form method="POST">
            <label class="form-label">Enter License Number:</label>
            <input type="text" name="licenseNumber" class="form-control" required>
            <button class="btn btn-primary mt-3" name="search">Search</button>
        </form>
    </div>

    <!-- CURRENT LICENSE DETAILS -->
    <?php if ($customerInfo): ?>
        <div class="card shadow p-4 mb-4">
            <h4>Current License Information</h4>
            <div class="row">
                <div class="col-md-3"><strong>Customer ID:</strong> <?= $customerInfo["CustID"] ?></div>
                <div class="col-md-3"><strong>LTID:</strong> <?= $customerInfo["LTID"] ?></div>
                <div class="col-md-3"><strong>First Issue:</strong> <?= $customerInfo["FirstIssueDate"] ?></div>
                <div class="col-md-3"><strong>Expire:</strong> <?= $customerInfo["ExpireDate"] ?></div>
            </div>
        </div>
    <?php endif; ?>

    <!-- FULL HISTORY TABLE -->
    <?php if (!empty($fullHistory)): ?>
        <div class="card shadow p-4 mb-4">
            <h4 class="mb-3">Complete License Timeline</h4>

            <table class="table table-striped table-bordered text-center">
                <thead class="table-primary">
                <tr>
                    <th>Event</th>
                    <th>License Type (LTID)</th>
                    <th>Issue Date</th>
                    <th>Expire Date</th>
                </tr>
                </thead>

                <tbody>
                <?php foreach ($fullHistory as $event): ?>
                    <tr>
                        <td><?= $event["Type"] ?></td>
                        <td><?= $event["LTID"] ?></td>
                        <td><?= $event["IssueDate"] ?></td>
                        <td><?= $event["ExpireDate"] ?></td>
                    </tr>
                <?php endforeach; ?>
                </tbody>
            </table>

        </div>
    <?php endif; ?>

</div>
</body>
</html>
