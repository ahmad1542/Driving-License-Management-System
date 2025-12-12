<?php
session_start();
if (!isset($_SESSION['username'])) {
    header("Location: login.php");
    exit();
}

require "config.php";

$customer = null;
$notFound = false;

if (isset($_GET['search'])) {
    $id = trim($_GET['search']);

    if ($id !== "") {
        $result = $conn->query("select * from Customer where CustIDNo = '$id'");

        if ($result->num_rows > 0) {
            $customer = $result->fetch_assoc();
        } else {
            $notFound = true;
        }
    }
}
?>

<html>

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Manage Customers</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <style>
        .big-space {
            margin-top: 40px;
        }

        body {
            background: linear-gradient(135deg, #d0f5ee, #e8f2ff);
            font-family: Arial;
        }

        .search-card {
            max-width: 500px;
            margin: 40px auto;
            padding: 25px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.08);
        }

        .result-card,
        .notfound-card {
            max-width: 500px;
            margin: 20px auto;
            padding: 20px;
            background: white;
            border-radius: 12px;
            box-shadow: 0 4px 12px rgba(0, 0, 0, 0.10);
        }

        .notfound-card {
            color: #bb0000;
            text-align: center;
        }

        .icon-btn {
            border: none;
            background: #0a57d0;
            color: white;
            padding: 10px 18px;
            border-radius: 8px;
            transition: 0.2s;
        }

        .icon-btn:hover {
            background: #0849a8;
        }
    </style>
</head>

<body>


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

    <div class="search-card">
        <h4 class="text-center mb-3" style="color:#003a7a;">Manage Customers</h4>

        <form method="GET">
            <div class="input-group mb-3">

                <input type="text" name="search" class="form-control"
                    placeholder="Enter Customer ID..."
                    inputmode="numeric" pattern="[0-9]*" required>

                <button class="btn icon-btn" type="submit" title="Search">
                    🔍 Search
                </button>
            </div>
        </form>

        <button class="icon-btn w-100 big-space" onclick="window.location='addcustomer.php'" title="Add Customer">
            ➕ Add Customer
        </button>
    </div>

    <?php if ($customer): ?>
        <div class="result-card">
            <h5 class="mb-2" style="color:#003a7a;">👤 Customer Found</h5>

            <p><strong>Name:</strong>
                <?= $customer['FName'] . " " . $customer['LName']; ?>
            </p>

            <p><strong>ID:</strong> <?= $customer['CustIDNo']; ?></p>
            <p><strong>Address:</strong> <?= $customer['Address']; ?></p>

            <button class="icon-btn mt-2"
                onclick="window.location='editcustomer.php?id=<?= $customer['CustIDNo']; ?>'">
                ✏️ Edit Customer
            </button>
        </div>
    <?php endif; ?>

    <?php if ($notFound): ?>
        <div class="notfound-card">
            ❗ Customer Not Found
        </div>
    <?php endif; ?>

</body>

</html>