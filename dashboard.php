<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>DLMS Dashboard</title>
    <link rel="stylesheet" href="dashboard.css">
</head>
<body>

<div class="sidebar">
    <h2>🚗 DLMS</h2>
    <ul>
        <li>🏠 Dashboard</li>
        <li>🧑 Customers</li>
        <li>📝 Tests</li>
        <li>🎓 Issue License</li>
        <li>🔄 Renew License</li>
        <li>⬆️ Upgrade License</li>
        <li>📜 History</li>
        <li class="logout"><a href="login.php">🚪 Logout</a></li>
    </ul>
</div>

<div class="main">

    <header>
        <h1>Welcome, <?php echo $_SESSION['username']; ?> 👋</h1>
    </header>

    <section class="cards">

        <div class="card">
            <h3>🧑 Manage Customers</h3>
            <p>Add, edit, and view customer information.</p>
        </div>

        <div class="card">
            <h3>📝 Manage Tests</h3>
            <p>Record theory and practical test results.</p>
        </div>

        <div class="card">
            <h3>🎓 Issue License</h3>
            <p>Issue a new driving license after tests.</p>
        </div>

        <div class="card">
            <h3>🔄 Renew License</h3>
            <p>Extend license expiration dates.</p>
        </div>

        <div class="card">
            <h3>⬆️ Upgrade License</h3>
            <p>Upgrade license type (e.g., LV → Truck).</p>
        </div>

        <div class="card">
            <h3>📜 License History</h3>
            <p>View previous renewals and upgrades.</p>
        </div>

    </section>

</div>

</body>
</html>
