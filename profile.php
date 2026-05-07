<?php
include("check_login.php");
include("include/connect.php");

$aid = intval($_SESSION['aid']);
$userResult = mysqli_query($con, "SELECT * FROM accounts WHERE aid = $aid");
$user = $userResult ? mysqli_fetch_assoc($userResult) : null;
$role = $user ? $user['role'] : null;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Profile</title>
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
                <li><a class="active" href="profile.php">Profile</a></li>
                <?php if ($role === 'admin'): ?>
                    <li><a href="admin_dashboard.php">Admin</a></li>
                <?php endif; ?>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <section class="section-p1">
        <h2>My Profile</h2>
        <?php if ($user): ?>
            <p><strong>Name:</strong> <?php echo htmlspecialchars($user['afname'] . ' ' . $user['alname']); ?></p>
            <p><strong>Username:</strong> <?php echo htmlspecialchars($user['username']); ?></p>
            <p><strong>Email:</strong> <?php echo htmlspecialchars($user['email']); ?></p>
            <p><strong>Phone:</strong> <?php echo htmlspecialchars($user['phone']); ?></p>
            <p><a href="logout.php">Logout</a></p>
        <?php else: ?>
            <p>User data not found.</p>
        <?php endif; ?>
    </section>

    <script src="script.js"></script>
</body>
</html>





