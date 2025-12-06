<?php
global $conn;
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
        $_SESSION['username'] = $username;
        header("location: dashboard.php");
    } else {
        $flag = 1;
    }
}
?>


<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Page</title>
    <link rel="stylesheet" href="login.css">
</head>
<body>

<header>
    <span>🔑 License Manager</span>
</header>

<main>
    <h1>Welcome Back</h1>

    <form action="login.php" method="POST">
        <label for="username">Username</label>
        <input type="text" id="username" name="username" placeholder="" required/>
        <label for="password">Password</label>
        <div class="password-box">
            <input type="password" id="password" name="password" required/>
            <span class="eye" id="togglePassword">👁️</span>
        </div>
        <div class="<?php if ($flag == 1) { echo "alert alert-danger"; } ?>">
            <br>
            <h style="color: red; font-weight: bold; display: flex; justify-content: center" id="error">
                <?php
                if ($flag == 1) {
                    echo "Username or Password is incorrect";
                }
                ?>

            </h>
        </div>
        <button type="submit" class="login-btn">Login</button>
    </form>
</main>

<script>
    var toggle = document.getElementById("togglePassword");
    var passInput = document.getElementById("password");

    toggle.addEventListener("click", () => {
        var type = passInput.type === "password" ? "text" : "password";
        passInput.type = type;
    });
</script>

</body>
</html>
