<?php
include("check_login.php");
include("include/connect.php");
$aid = intval($_SESSION['aid']);
$role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
$role = $role_result && mysqli_num_rows($role_result) > 0 ? mysqli_fetch_assoc($role_result)['role'] : null;
$pid = intval($_GET['pid'] ?? 0);
$product = null;
if ($pid > 0) {
    $result = mysqli_query($con, "SELECT * FROM products WHERE pid = $pid");
    if ($result) {
        $product = mysqli_fetch_assoc($result);
    }
}
$error = '';
if ($product && isset($_POST['add_cart'])) {
    $qty = intval($_POST['qty'] ?? 1);
    mysqli_query($con, "INSERT INTO cart (aid, pid, cqty) VALUES (" . intval($_SESSION['aid']) . ", $pid, $qty) ON DUPLICATE KEY UPDATE cqty = cqty + $qty");
    header("Location: cart.php");
    exit();
}
if ($product && isset($_POST['add_wishlist'])) {
    mysqli_query($con, "INSERT IGNORE INTO wishlist (aid, pid) VALUES (" . intval($_SESSION['aid']) . ", $pid)");
    header("Location: wishlist.php");
    exit();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Product Details</title>
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
                <li><a href="profile.php">Profile</a></li>
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
        <?php if ($product): ?>
            <div class="product-detail">
                <img src="product_images/<?php echo htmlspecialchars($product['img']); ?>" alt="<?php echo htmlspecialchars($product['pname']); ?>">
                <div>
                    <h2><?php echo htmlspecialchars($product['pname']); ?></h2>
                    <p><?php echo htmlspecialchars($product['description']); ?></p>
                    <p><strong>Price:</strong> $<?php echo $product['price']; ?></p>
                    <p><strong>Category:</strong> <?php echo htmlspecialchars($product['category']); ?></p>
                    <form method="post">
                        <input type="number" name="qty" value="1" min="1" class="input1">
                        <button type="submit" name="add_cart" class="btn">Add to Cart</button>
                        <button type="submit" name="add_wishlist" class="btn">Add to Wishlist</button>
                    </form>
                </div>
            </div>
        <?php else: ?>
            <p>Product not found.</p>
        <?php endif; ?>
    </section>

    <script src="script.js"></script>
</body>
</html>





