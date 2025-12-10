<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

$isAdmin = isset($_SESSION['role']) && $_SESSION['role'] === "Admin";
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLMS Dashboard</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

    <nav class="navbar navbar-expand-lg navbar-light bg-white shadow-sm px-4 py-3">
        <a class="navbar-brand fw-bold text-primary fs-4">⛍ Driving License Management System</a>

        <div class="d-flex ms-auto align-items-center gap-3">

            <?php if ($isAdmin): ?>
                <span class="badge bg-danger rounded-pill px-3 py-2 fs-6">
                    🔑 Admin
                </span>
            <?php endif; ?>

            <span class="fw-semibold text-dark">Hello, <?php echo $_SESSION['username']; ?> 👋</span>
            <a href="logout.php" class="btn btn-outline-danger">Logout</a>
        </div>


    </nav>

    <div class="container mt-4">

        <div class="row g-4">

            <div class="col-12 col-md-6 col-lg-4">
                <a href="managecustomers.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>👥 Manage Customers</h3>
                        <p>Add, edit, and view customer information.</p>
                    </div>
                </a>
            </div>


            <div class="col-12 col-md-6 col-lg-4">
                <a href="managetests.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>📝 Manage Tests</h3>
                        <p>Record theory and practical test results.</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="issuelicense.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>🪪 Issue License</h3>
                        <p>Issue a new driving license after tests.</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="renewlicense.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>🔄 Renew License</h3>
                        <p>Extend license expiration dates.</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="upgradelicense.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>⬆️ Upgrade License</h3>
                        <p>Upgrade license type (LV → Truck, etc.).</p>
                    </div>
                </a>
            </div>

            <div class="col-12 col-md-6 col-lg-4">
                <a href="licensehistory.php" class="text-decoration-none text-dark">
                    <div class="card shadow card-box h-100">
                        <h3>📜 License History</h3>
                        <p>View previous renewals and upgrades.</p>
                    </div>
                </a>
            </div>

            <?php if ($isAdmin): ?>
                <div class="col-12 col-md-6 col-lg-4">
                    <a href="manageemployee.php" class="text-decoration-none text-dark">
                        <div class="card shadow card-box h-100" style="border-left: 5px solid #dc3545;">
                            <h3>👨‍💼 Manage Employees</h3>
                            <p>Add, edit, delete, and view employee accounts.</p>
                        </div>
                    </a>
                </div>
            <?php endif; ?>

        </div>

    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>
</body>

</html>