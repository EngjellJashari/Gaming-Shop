<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");
$role = null;
if (isset($_SESSION['aid']) && $_SESSION['aid'] > 0) {
    $aid_session = intval($_SESSION['aid']);
    $role_result = mysqli_query($con, "SELECT role FROM accounts WHERE aid = $aid_session");
    if ($role_result && mysqli_num_rows($role_result) > 0) {
        $role_row = mysqli_fetch_assoc($role_result);
        $role = $role_row['role'];
    }
}
$message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['contact-submit'])) {
    $name = mysqli_real_escape_string($con, trim($_POST['name']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));
    $subject = mysqli_real_escape_string($con, trim($_POST['subject']));
    $msgText = mysqli_real_escape_string($con, trim($_POST['message']));
    $aid = isset($_SESSION['aid']) && $_SESSION['aid'] > 0 ? intval($_SESSION['aid']) : 'NULL';

    if ($name === '' || $email === '' || $subject === '' || $msgText === '') {
        $message = 'Please fill in all fields.';
    } else {
        $insert = "INSERT INTO contact_messages (aid, name, email, subject, message) VALUES ($aid, '$name', '$email', '$subject', '$msgText')";
        if (mysqli_query($con, $insert)) {
            $message = 'Your message has been sent successfully.';
        } else {
            $message = 'Unable to send your message.';
        }
    }
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8" />
    <meta http-equiv="X-UA-Compatible" content="IE=edge" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Contact</title>
    <link rel="stylesheet" href="style.css" />
</head>
<body>
    <section id="header">
        <a href="index.php"><img src="img/logo_new.png" class="logo" alt="Logo" style="width:140px;height:auto;" /></a>
        <div>
            <ul id="navbar">
                <li><a href="index.php">Home</a></li>
                <li><a href="shop.php">Shop</a></li>                <li><a href="about.php">About</a></li>
                <li><a class="active" href="contact.php">Contact</a></li>
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
        <h2>Contact Us</h2>
        <?php if ($message): ?>
            <div class="form-message"><?php echo htmlspecialchars($message); ?></div>
        <?php endif; ?>
        <form method="post" id="contact-form" onsubmit="return validateContactForm()">
            <input class="input1" id="contact-name" name="name" type="text" placeholder="Name *" required>
            <input class="input1" id="contact-email" name="email" type="email" placeholder="Email *" required>
            <input class="input1" id="contact-subject" name="subject" type="text" placeholder="Subject *" required>
            <textarea class="input1" id="contact-message" name="message" placeholder="Message *" required></textarea>
            <button type="submit" name="contact-submit" class="btn">Send Message</button>
        </form>
    </section>

    <script src="script.js"></script>
</body>
</html>





