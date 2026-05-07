<?php
session_start();
include("include/connect.php");
$error = '';

if (isset($_POST['submit'])) {
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));

    $query = "SELECT * FROM accounts WHERE username='$username' AND password='$password'";
    $result = mysqli_query($con, $query);

    if ($result && mysqli_num_rows($result) > 0) {
        $row = mysqli_fetch_assoc($result);
        if ($row['role'] === 'admin') {
            $_SESSION['aid'] = $row['aid'];
            header("Location: admin_dashboard.php");
            exit();
        } else {
            $error = 'Access denied: Not an admin';
        }
    } else {
        $error = 'Wrong credentials';
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Login</title>
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
                <li><a href="login.php">login</a></li>
                <li><a href="signup.php">SignUp</a></li>
                <li><a class="active" href="admin.php">Admin</a></li>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <form method="post" id="form">
        <h3>Admin Login</h3>
        <?php if ($error): ?>
            <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <input class="input1" name="username" type="text" placeholder="Username *" required>
        <input class="input1" name="password" type="password" placeholder="Password *" required>
        <button type="submit" class="btn" name="submit">Login</button>
    </form>

    <script src="script.js"></script>
</body>
</html>





