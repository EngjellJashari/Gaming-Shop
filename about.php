<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");
$role = null;
if (isset($_SESSION['aid']) && $_SESSION['aid'] > 0) {
    $aid = intval($_SESSION['aid']);
    $role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
    if ($role_result && mysqli_num_rows($role_result) > 0) {
        $role_row = mysqli_fetch_assoc($role_result);
        $role = $role_row['role'];
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>About</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/logo_new.png" class="logo" alt="Logo" style="width:140px;height:auto;" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>                <li><a class="active" href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <?php if (isset($_SESSION['aid']) && $_SESSION['aid'] > 0): ?>
                    <li><a href="profile.php">Profile</a></li>
                    <?php if ($role === 'admin'): ?>
                        <li><a href="admin_dashboard.php">Admin</a></li>
                    <?php endif; ?>
                <?php else: ?>
                    <li><a href="login.php">login</a></li>
                    <li><a href="signup.php">SignUp</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <section class="section-p1">
        <h2>About KosovaGameHub</h2>
        <p>KosovaGameHub is your destination for gaming equipment and accessories.</p>
    </section>

    <script src="script.js"></script>
</body>
</html>





