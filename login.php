<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
header("Cache-Control: no-store, no-cache, must-revalidate, max-age=0");
header("Cache-Control: post-check=0, pre-check=0", false);
header("Pragma: no-cache");
header("Expires: 0");
include("include/connect.php");
$error = '';

if (isset($_SESSION['aid']) && $_SESSION['aid'] > 0) {
    if (!isset($_SESSION['role'])) {
        $aid = intval($_SESSION['aid']);
        $role_query = "SELECT role FROM accounts WHERE aid = $aid";
        $role_result = mysqli_query($con, $role_query);
        if ($role_result && mysqli_num_rows($role_result) > 0) {
            $role_row = mysqli_fetch_assoc($role_result);
            $_SESSION['role'] = $role_row['role'];
        } else {
            session_destroy();
            header("Location: login.php");
            exit();
        }
    }

    if ($_SESSION['role'] === 'admin') {
        header("Location: admin_dashboard.php");
    } else {
        header("Location: profile.php");
    }
    exit();
}

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = trim($_POST['password']);

    $query = "SELECT * FROM accounts WHERE username='$username' OR email='$username'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        $storedPassword = $row['password'];
        $passwordIsValid = password_verify($password, $storedPassword) || $password === $storedPassword;

        if ($passwordIsValid) {
            $_SESSION['aid'] = $row['aid'];
            $_SESSION['role'] = $row['role'];

            if (isset($_POST['remember'])) {
                $token = bin2hex(random_bytes(32));
                $update_query = "UPDATE accounts SET remember_token='$token' WHERE aid=" . intval($row['aid']);
                mysqli_query($con, $update_query);
                setcookie('remember_token', $token, time() + (86400 * 30), "/");
            }

            if ($row['role'] === 'admin') {
                header("Location: admin_dashboard.php");
            } else {
                header("Location: profile.php");
            }
            exit();
        }
    }

    $error = 'Wrong credentials';
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Login</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/logo_new.png" class="logo" alt="Logo" style="width:140px;height:auto;" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a class="active" href="login.php">login</a></li>
                <li><a href="signup.php">SignUp</a></li>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <form method="post" id="form" onsubmit="return validateLoginForm()">
        <h3>Login</h3>
        <?php if ($error): ?>
            <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <input class="input1" id="user" name="username" type="text" placeholder="Username *" required>
        <input class="input1" id="pass" name="password" type="password" placeholder="Password *" required>
        <label><input type="checkbox" name="remember" value="1"> Remember me</label>
        <button type="submit" class="btn" name="submit">Login</button>
    </form>

    <div class="sign">
        <a href="signup.php" class="signn">Do not have an account?</a>
    </div>

    <script src="script.js"></script>
    <script>
        window.addEventListener('pageshow', function(event) {
            if (event.persisted || (window.performance && window.performance.getEntriesByType('navigation').length && window.performance.getEntriesByType('navigation')[0].type === 'back_forward')) {
                window.location.reload();
            }
        });
    </script>
</body>
</html>





