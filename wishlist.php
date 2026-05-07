<?php
include("check_login.php");
include("include/connect.php");
$aid = intval($_SESSION['aid']);
$role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
$role = $role_result && mysqli_num_rows($role_result) > 0 ? mysqli_fetch_assoc($role_result)['role'] : null;
if (isset($_GET['remove'])) {
    $pid = intval($_GET['remove']);
    mysqli_query($con, "DELETE FROM wishlist WHERE aid = $aid AND pid = $pid");
    header("Location: wishlist.php");
    exit();
}
$wishlist = mysqli_query($con, "SELECT w.pid, p.pname, p.price, p.img FROM wishlist w JOIN products p ON w.pid = p.pid WHERE w.aid = $aid");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Wishlist</title>
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
        <h2>Your Wishlist</h2>
        <?php if ($wishlist && mysqli_num_rows($wishlist) > 0): ?>
            <div class="pro-container">
                <?php while ($item = mysqli_fetch_assoc($wishlist)): ?>
                    <div class="pro">
                        <a href="sproduct.php?pid=<?php echo $item['pid']; ?>">
                            <img src="product_images/<?php echo htmlspecialchars($item['img']); ?>" alt="<?php echo htmlspecialchars($item['pname']); ?>" />
                            <div class="des">
                                <h5><?php echo htmlspecialchars($item['pname']); ?></h5>
                                <h4>$<?php echo $item['price']; ?></h4>
                            </div>
                        </a>
                        <a href="wishlist.php?remove=<?php echo $item['pid']; ?>" class="btn">Remove</a>
                    </div>
                <?php endwhile; ?>
            </div>
        <?php else: ?>
            <p>Your wishlist is empty.</p>
        <?php endif; ?>
    </section>

    <script src="script.js"></script>
</body>
</html>





