<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$message = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    $customer = $_POST['customer'];
    $ltid = $_POST['licenseType'];
    $grade = $_POST['grade'];
    $testType = $_POST['testType'];

    $insert = "insert into Test (CustomerID, LTID, Grade, TestType)
               values ('$customer', '$ltid', '$grade', '$testType')";

    if ($conn->query($insert)) {
        $message = "<div class='alert alert-success'>Test Added Successfully!</div>";
    } else {
        $message = "<div class='alert alert-danger'>Error: " . $conn->error . "</div>";
    }
}

$tests = $conn->query("select TestID, CustomerID, LTID, Grade, TestType
                              from Test
                              order by TestID desc");
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Tests</title>

    <link href='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css' rel='stylesheet'>
    <link rel="stylesheet" href="dashboard.css">
</head>

<body>

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

    <div class="container mt-4">

        <h2 class="fw-bold mb-3 text-primary">📝 Manage Tests</h2>

        <?= $message ?>

        <div class="card shadow p-4 mb-4">
            <h4 class="mb-3">Add New Test</h4>

            <form method="POST">

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Customer ID</label>
                        <input type="text" class="form-control" name="customer" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">License Type ID (LTID)</label>
                        <input type="number" class="form-control" name="licenseType" min="1" max="6" required>
                    </div>
                </div>

                <div class="row mb-3">
                    <div class="col-md-6">
                        <label class="form-label">Grade (0-30)</label>
                        <input type="number" class="form-control" name="grade" min="0" max="30" required>
                    </div>

                    <div class="col-md-6">
                        <label class="form-label">Test Type</label>
                        <select class="form-select" name="testType" required>
                            <option value="Theory">Theory</option>
                            <option value="Practical">Practical</option>
                        </select>
                    </div>
                </div>

                <button class="btn btn-primary w-100">Add Test</button>
            </form>
        </div>

        <div class="card shadow p-4">
            <h4 class="mb-3">All Tests</h4>

            <table class="table table-bordered table-striped text-center">
                <thead class="table-primary">
                    <tr>
                        <th>ID</th>
                        <th>Customer ID</th>
                        <th>LTID</th>
                        <th>Grade</th>
                        <th>Test Type</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($row = $tests->fetch_assoc()): ?>
                        <tr>
                            <td><?= $row["TestID"] ?></td>
                            <td><?= $row["CustomerID"] ?></td>
                            <td><?= $row["LTID"] ?></td>
                            <td><?= $row["Grade"] ?></td>
                            <td><?= $row["TestType"] ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>

            </table>
        </div>

    </div>

    <script src='https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js'></script>
</body>

</html>