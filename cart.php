<?php
include("check_login.php");
include("include/connect.php");
$aid = intval($_SESSION['aid']);
$role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
$role = $role_result && mysqli_num_rows($role_result) > 0 ? mysqli_fetch_assoc($role_result)['role'] : null;
$cartResult = mysqli_query($con, "SELECT c.pid, c.cqty, p.pname, p.price, p.img FROM cart c JOIN products p ON c.pid = p.pid WHERE c.aid = $aid");
$total = 0;
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Cart</title>
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
        <h2>Your Cart</h2>
        <?php if ($cartResult && mysqli_num_rows($cartResult) > 0): ?>
            <table>
                <thead><tr><th>Product</th><th>Qty</th><th>Price</th><th>Subtotal</th></tr></thead>
                <tbody>
                    <?php while ($item = mysqli_fetch_assoc($cartResult)): ?>
                        <?php $subtotal = $item['cqty'] * $item['price']; $total += $subtotal; ?>
                        <tr>
                            <td><?php echo htmlspecialchars($item['pname']); ?></td>
                            <td><?php echo $item['cqty']; ?></td>
                            <td>$<?php echo $item['price']; ?></td>
                            <td>$<?php echo number_format($subtotal, 2); ?></td>
                        </tr>
                    <?php endwhile; ?>
                </tbody>
            </table>
            <p><strong>Total: $<?php echo number_format($total, 2); ?></strong></p>
            <a href="checkout.php" class="btn">Proceed to Checkout</a>
        <?php else: ?>
            <p>Your cart is empty.</p>
        <?php endif; ?>
    </section>

    <script src="script.js"></script>
</body>
</html>





