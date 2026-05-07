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
$products = mysqli_query($con, "SELECT * FROM products WHERE qtyavail > 0 ORDER BY pid DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Shop</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/logo_new.png" class="logo" alt="Logo" style="width:140px;height:auto;" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a class="active" href="shop.php">Shop</a></li>
                <li><a href="about.php">About</a></li>
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
        <h2>Products</h2>
        <div class="pro-container">
            <?php if ($products && mysqli_num_rows($products) > 0): ?>
                <?php while ($product = mysqli_fetch_assoc($products)): ?>
                    <div class="pro">
                        <a href="sproduct.php?pid=<?php echo $product['pid']; ?>">
                            <img src="product_images/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['pname']); ?>" />
                            <div class="des">
                                <span><?php echo htmlspecialchars($product['brand']); ?></span>
                                <h5><?php echo htmlspecialchars($product['pname']); ?></h5>
                                <h4>$<?php echo $product['price']; ?></h4>
                            </div>
                        </a>
                    </div>
                <?php endwhile; ?>
            <?php else: ?>
                <p>No products found.</p>
            <?php endif; ?>
        </div>
    </section>

    <script src="script.js"></script>
</body>
</html>


