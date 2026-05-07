<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");

if (!isset($_SESSION['aid']) || $_SESSION['aid'] <= 0) {
    header("Location: admin.php");
    exit();
}
$aid = intval($_SESSION['aid']);
$roleResult = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid");
if (!$roleResult || mysqli_num_rows($roleResult) === 0) {
    header("Location: admin.php");
    exit();
}
$roleRow = mysqli_fetch_assoc($roleResult);
if ($roleRow['role'] !== 'admin') {
    header("Location: index.php");
    exit();
}

$message = '';
if (isset($_GET['delete_pid'])) {
    $delete_pid = intval($_GET['delete_pid']);

    $orderCheck = mysqli_query($con, "SELECT 1 FROM `order-details` WHERE pid = $delete_pid LIMIT 1");
    if ($orderCheck && mysqli_num_rows($orderCheck) > 0) {
        $message = 'Cannot delete this product because it is part of an existing order.';
    } else {
        mysqli_query($con, "DELETE FROM cart WHERE pid = $delete_pid");
        mysqli_query($con, "DELETE FROM wishlist WHERE pid = $delete_pid");
        mysqli_query($con, "DELETE FROM reviews WHERE pid = $delete_pid");

        $deleteQuery = "DELETE FROM products WHERE pid = $delete_pid";
        if (mysqli_query($con, $deleteQuery)) {
            if (mysqli_affected_rows($con) > 0) {
                $message = 'Product deleted successfully.';
            } else {
                $message = 'Product not deleted. It may not exist.';
            }
        } else {
            $message = 'Failed to delete product: ' . mysqli_error($con);
        }
    }
}
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add_product'])) {
    $pname = mysqli_real_escape_string($con, trim($_POST['pname']));
    $category = mysqli_real_escape_string($con, trim($_POST['category']));
    $description = mysqli_real_escape_string($con, trim($_POST['description']));
    $price = floatval($_POST['price']);
    $qtyavail = intval($_POST['qtyavail']);
    $brand = mysqli_real_escape_string($con, trim($_POST['brand']));

    $img = '';
    if (isset($_FILES['img']) && $_FILES['img']['error'] === UPLOAD_ERR_OK) {
        $uploadDir = 'product_images/';
        $fileName = basename($_FILES['img']['name']);
        $filePath = $uploadDir . $fileName;
        $fileType = strtolower(pathinfo($filePath, PATHINFO_EXTENSION));
        $allowedTypes = ['jpg', 'jpeg', 'png', 'gif'];

        if (in_array($fileType, $allowedTypes) && move_uploaded_file($_FILES['img']['tmp_name'], $filePath)) {
            $img = $fileName;
        } else {
            $message = 'Invalid image file or upload failed.';
        }
    } else {
        $message = 'Image upload is required.';
    }

    if ($pname && $price > 0 && $qtyavail >= 0 && $img) {
        $query = "INSERT INTO products (pname, category, description, price, qtyavail, img, brand, created_by) VALUES ('$pname', '$category', '$description', $price, $qtyavail, '$img', '$brand', $aid)";
        if (mysqli_query($con, $query)) {
            $message = 'Product added successfully.';
        } else {
            $message = 'Failed to add product: ' . mysqli_error($con);
        }
    } elseif (!$message) {
        $message = 'Please fill in required fields correctly.';
    }
}

$contacts = mysqli_query($con, "SELECT cm.*, a.username FROM contact_messages cm LEFT JOIN accounts a ON cm.aid = a.aid ORDER BY cm.created_at DESC");
$products = mysqli_query($con, "SELECT p.*, a.username AS author FROM products p LEFT JOIN accounts a ON p.created_by = a.aid ORDER BY p.created_at DESC");
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Admin Dashboard</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body class="admin-page">
    <section id="header">
        <a href="index.php"><img src="img/logo_new.png" class="logo" alt="Logo" style="width:140px;height:auto;" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>                <li><a href="about.php">About</a></li>
                <li><a href="contact.php">Contact</a></li>
                <li><a href="profile.php">Profile</a></li>
                <li><a class="active" href="admin_dashboard.php">Admin</a></li>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <main class="admin-dashboard">
        <section class="admin-hero">
            <div>
                <h1>Admin Dashboard</h1>
                <p>Manage products, view customer messages, and keep the store tidy with the same site theme.</p>
            </div>
            <div class="admin-hero-badge">KosovaGameHub Admin</div>
        </section>

        <section class="section-p1 admin-section">
            <h2>Contact Messages</h2>
            <table class="admin-table">
            <thead>
                <tr><th>ID</th><th>Name</th><th>Email</th><th>Subject</th><th>Message</th><th>Sent By</th><th>Date</th></tr>
            </thead>
            <tbody>
                <?php if ($contacts && mysqli_num_rows($contacts) > 0): ?>
                    <?php while ($msg = mysqli_fetch_assoc($contacts)): ?>
                        <tr>
                            <td><?php echo $msg['id']; ?></td>
                            <td><?php echo htmlspecialchars($msg['name']); ?></td>
                            <td><?php echo htmlspecialchars($msg['email']); ?></td>
                            <td><?php echo htmlspecialchars($msg['subject']); ?></td>
                            <td><?php echo htmlspecialchars($msg['message']); ?></td>
                            <td><?php echo $msg['username'] ? htmlspecialchars($msg['username']) : 'Guest'; ?></td>
                            <td><?php echo $msg['created_at']; ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="7">No contact messages yet.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>

    <section class="section-p1 admin-section">
        <h2>Add New Product</h2>
        <?php if ($message): ?>
            <p class="admin-message"><?php echo htmlspecialchars($message); ?></p>
        <?php endif; ?>
        <form method="post" enctype="multipart/form-data" class="admin-form">
            <input type="text" name="pname" placeholder="Product Name" required>
            <input type="text" name="category" placeholder="Category">
            <textarea name="description" placeholder="Description"></textarea>
            <input type="number" step="0.01" name="price" placeholder="Price" required>
            <input type="number" name="qtyavail" placeholder="Quantity Available" required>
            <input type="file" name="img" accept="image/*" required>
            <input type="text" name="brand" placeholder="Brand">
            <button type="submit" name="add_product">Add Product</button>
        </form>
    </section>

    <section class="section-p1 admin-section">
        <h2>Products Created By</h2>
        <table class="admin-table">
            <thead>
                <tr><th>PID</th><th>Name</th><th>Brand</th><th>Category</th><th>Price</th><th>Qty</th><th>Created By</th><th>Date</th><th>Action</th></tr>
            </thead>
            <tbody>
                <?php if ($products && mysqli_num_rows($products) > 0): ?>
                    <?php while ($product = mysqli_fetch_assoc($products)): ?>
                        <tr>
                            <td><?php echo $product['pid']; ?></td>
                            <td><?php echo htmlspecialchars($product['pname']); ?></td>
                            <td><?php echo htmlspecialchars($product['brand']); ?></td>
                            <td><?php echo htmlspecialchars($product['category']); ?></td>
                            <td><?php echo '$' . $product['price']; ?></td>
                            <td><?php echo $product['qtyavail']; ?></td>
                            <td><?php echo $product['author'] ? htmlspecialchars($product['author']) : 'Unknown'; ?></td>
                            <td><?php echo $product['created_at']; ?></td>
                            <td><a href="?delete_pid=<?php echo $product['pid']; ?>" onclick="return confirm('Delete this product?')">Delete</a></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr><td colspan="9">No products found.</td></tr>
                <?php endif; ?>
            </tbody>
        </table>
    </section>
</main>

    <script src="script.js"></script>
</body>
</html>





