<?php
include("check_login.php");
include("include/connect.php");
$aid = intval($_SESSION['aid']);
$role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
$role = $role_result && mysqli_num_rows($role_result) > 0 ? mysqli_fetch_assoc($role_result)['role'] : null;
$message = '';

if (isset($_POST['place_order'])) {
    $address = mysqli_real_escape_string($con, trim($_POST['address']));
    $city = mysqli_real_escape_string($con, trim($_POST['city']));
    $country = mysqli_real_escape_string($con, trim($_POST['country']));
    $total = floatval($_POST['total'] ?? 0);

    if ($address === '' || $city === '' || $country === '') {
        $message = 'Please complete all fields.';
    } else {
        mysqli_query($con, "INSERT INTO orders (dateod, datedel, aid, address, city, country, account, total) VALUES (CURDATE(), NULL, $aid, '$address', '$city', '$country', NULL, $total)");
        $orderId = mysqli_insert_id($con);
        $cartItems = mysqli_query($con, "SELECT pid, cqty FROM cart WHERE aid = $aid");
        while ($row = mysqli_fetch_assoc($cartItems)) {
            mysqli_query($con, "INSERT INTO `order-details` (oid, pid, qty) VALUES ($orderId, {$row['pid']}, {$row['cqty']})");
        }
        mysqli_query($con, "DELETE FROM cart WHERE aid = $aid");
        $message = 'Order placed successfully.';
    }
}

$cartResult = mysqli_query($con, "SELECT c.pid, c.cqty, p.pname, p.price FROM cart c JOIN products p ON c.pid = p.pid WHERE c.aid = $aid");
$total = 0;
while ($item = mysqli_fetch_assoc($cartResult)) {
    $total += $item['cqty'] * $item['price'];
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Checkout</title>
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
        <h2>Checkout</h2>
        <?php if ($message): ?>
            <div class="form-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post">
            <input class="input1" name="address" type="text" placeholder="Address" required>
            <input class="input1" name="city" type="text" placeholder="City" required>
            <input class="input1" name="country" type="text" placeholder="Country" required>
            <input type="hidden" name="total" value="<?php echo $total; ?>">
            <p>Total amount: $<?php echo number_format($total, 2); ?></p>
            <button type="submit" name="place_order" class="btn">Place Order</button>
        </form>
    </section>

    <script src="script.js"></script>
</body>
</html>





