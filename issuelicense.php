<?php
session_start();
require "config.php";

$message = "";

if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $custID = $_POST["customerid"];
    $ltid   = $_POST["licensetype"];

    $customerExist = $conn->query("select * from customer where CustIDNo = '$custID'");
    $customer = $customerExist->fetch_assoc();

    if (!$customer) {
        $message = "<div class='alert alert-danger'>❌ Customer does not exist.</div>";
    } else {

        $canIssue = true;

        $hasLicense = $conn->query("select * from custlic 
                                           where CustID = '$custID' and LTID = '$ltid'");
        $existingLicense = $hasLicense->fetch_assoc();

        if ($existingLicense) {
            $message = "
                <div class='alert alert-warning'>
                    ⚠️ This customer already has a license for this license type.<br>
                    <b>License Number:</b> {$existingLicense['LicenseNumber']}<br>
                    <b>Expire Date:</b> {$existingLicense['ExpireDate']}<br><br>
                    You cannot issue a new one.
                </div>";
            $canIssue = false;
        }

        if ($canIssue) {

            $passedTests = $conn->query("select TestType, Grade
                                                from test
                                                where CustomerID = '$custID' and LTID = '$ltid'");

            $practical_ok = false;
            $theory_ok    = false;

            while ($row = $passedTests->fetch_assoc()) {
                if ($row['TestType'] === "Practical" && $row['Grade'] >= 25) {
                    $practical_ok = true;
                }
                if ($row['TestType'] === "Theory" && $row['Grade'] >= 25) {
                    $theory_ok = true;
                }
            }

            if (!$practical_ok || !$theory_ok) {
                $message = "<div class='alert alert-danger'>
                            ❌ Customer must pass both Practical & Theory (≥25).
                            </div>";
                $canIssue = false;
            }
        }

        if ($canIssue) {

            $licenseNumber = rand(10000000, 99999999);
            $firstIssue    = date("Y-m-d");
            $expire        = date("Y-m-d", strtotime("+5 years"));

            $insert = $conn->query("insert into license (LicenseNumber, IssueDate)
                                           values ('$licenseNumber', '$firstIssue')");

            if (!$insert) {
                $message = "<div class='alert alert-danger'>❌ Failed to create license record.</div>";
            } else {

                $insert = $conn->query("insert into custlic (CustID, LicenseNumber, LTID, FirstIssueDate, ExpireDate)
                                               values ('$custID', '$licenseNumber', '$ltid', '$firstIssue', '$expire')");

                if ($insert) {
                    header("Location: card.php?lic=$licenseNumber&cust=$custID");
                    exit();
                } else {
                    $message = "<div class='alert alert-danger'>❌ Error issuing license.</div>";
                }
            }
        }
    }
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Issue License</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">
</head>

<body style="background:linear-gradient(135deg,#d0f5ee,#e8f2ff);">

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
        <a class="navbar-brand fw-bold text-primary fs-4" href="dashboard.php">⛍ Driving License Management System</a>

        <div class="d-flex ms-auto align-items-center gap-3">

            <?php if (isset($_SESSION['role']) && $_SESSION['role'] === "Admin"): ?>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">🔑 Admin</span>
            <?php endif; ?>

            <span class="fw-semibold"><?php echo $_SESSION['username']; ?></span>

            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>
    </nav>

    <div class="container mt-5">

        <h2 class="text-center mb-4 fw-bold" style="color:#003a7a;">
            🪪 Issue New License
        </h2>

        <div class="card shadow-sm mx-auto" style="max-width:500px;">
            <div class="card-body">

                <?= $message ?>

                <form method="POST">

                    <label class="form-label">Customer ID</label>
                    <input type="text" name="customerid" class="form-control mb-3" required>

                    <label class="form-label">License Type (LTID)</label>
                    <input type="number" name="licensetype" class="form-control mb-3" required>

                    <button class="btn btn-primary w-100">
                        Issue License
                    </button>

                </form>

            </div>
        </div>

    </div>

</body>

</html>