<?php
session_start();
require "config.php";
$flag = 0;
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $username = "";
    $password = "";
    if (isset($_POST['username'])) {
        $username = $_POST['username'];
    }
    if (isset($_POST['password'])) {
        $password = $_POST['password'];
    }
    $result = $conn->query("select * from account where UserName = '$username' and Password = '$password'");

    if ($result->num_rows > 0) {

        $roleIdResult = $conn->query("select RoleID from account where UserName = '$username'");
        $roleIdRow = $roleIdResult->fetch_assoc();
        $roleId = $roleIdRow['RoleID'];

        $roleNameResult = $conn->query("select RoleName from role where RoleID = '$roleId'");
        $roleNameRow = $roleNameResult->fetch_assoc();
        $roleName = $roleNameRow['RoleName'];

        $_SESSION['username'] = $username;
        $_SESSION['role'] = $roleName;
        header("location: dashboard.php");
    } else {
        $flag = 1;
    }
}
?>

<html>
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Login Page</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet">

    <link rel="stylesheet" href="login.css">
</head>
<body>

<header class="mt-4 text-center">
    <span class="fs-3 fw-bold text-primary">🔑 License Manager</span>
</header>

<main class="container d-flex justify-content-center mt-4">
    <div class="col-12 col-md-6 col-lg-4">

        <h1 class="text-center mb-3 fw-bold" style="color: #0f2338;">Welcome Back</h1>

        <form action="login.php" method="POST" class="p-4 rounded shadow bg-white">

            <label for="username" class="form-label">Username</label>
            <input type="text" id="username" name="username" required class="form-control">

            <label for="password" class="form-label mt-3">Password</label>
            <div class="position-relative">
                <input type="password" id="password" name="password" required class="form-control">
                <span class="position-absolute top-50 end-0 translate-middle-y pe-3" id="togglePassword"
                      style="cursor:pointer; opacity:0.7;">
                    👁️
                </span>
            </div>
            <div class="<?php if ($flag == 1) {
                echo "alert alert-danger";
            } ?>">
                <br>
                <h style="color: red; font-weight: bold; display: flex; justify-content: center" id="error">
                    <?php
                    if ($flag == 1) {
                        echo "Username or Password is incorrect";
                    }
                    ?>

                </h>
            </div>
            <button type="submit" class="btn w-100 mt-4 login-btn">Login</button>
        </form>

    </div>
</main>

<script>
    var toggle = document.getElementById("togglePassword");
    var pass = document.getElementById("password");
    toggle.onclick = () => pass.type = pass.type === "password" ? "text" : "password";
</script>

<script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js"></script>

</body>
</html>