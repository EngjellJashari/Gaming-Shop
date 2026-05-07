<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");
$error = '';

if (isset($_POST['submit'])) {
    $firstname = mysqli_real_escape_string($con, trim($_POST['firstName']));
    $lastname = mysqli_real_escape_string($con, trim($_POST['lastName']));
    $username = mysqli_real_escape_string($con, trim($_POST['username']));
    $password = mysqli_real_escape_string($con, trim($_POST['password']));
    $confirmpassword = mysqli_real_escape_string($con, trim($_POST['confirmPassword']));
    $dob = mysqli_real_escape_string($con, trim($_POST['dob']));
    $contact = mysqli_real_escape_string($con, trim($_POST['phone']));
    $gen = mysqli_real_escape_string($con, trim($_POST['gender']));
    $email = mysqli_real_escape_string($con, trim($_POST['email']));

    $query = "SELECT * FROM accounts WHERE username = '$username' OR phone='$contact' OR email='$email'";
    $result = mysqli_query($con, $query);
    if ($result && mysqli_num_rows($result) > 0) {
        $existing = mysqli_fetch_assoc($result);
        if ($existing['username'] === $username) {
            $error = 'Username already exists';
        } elseif ($existing['email'] === $email) {
            $error = 'Email already exists';
        } elseif ($existing['phone'] === $contact) {
            $error = 'Phone number already exists';
        } else {
            $error = 'Credentials already exists';
        }
    } elseif ($password !== $confirmpassword) {
        $error = 'Passwords do not match';
    } elseif (strlen($password) < 8) {
        $error = 'Passwords too short';
    } elseif (strtotime($dob) > time()) {
        $error = 'Invalid date';
    } elseif ($gen === 'S') {
        $error = 'Select gender';
    } elseif (!preg_match('/^\d{11}$/', $contact)) {
        $error = 'Invalid number';
    } else {
        $password_hash = password_hash($password, PASSWORD_DEFAULT);
        $query = "INSERT INTO accounts (afname, alname, phone, email, dob, username, gender, password) VALUES ('$firstname', '$lastname', '$contact', '$email', '$dob', '$username', '$gen', '$password_hash')";
        if (mysqli_query($con, $query)) {
            header("Location: login.php");
            exit();
        } else {
            $error = 'Unable to create account';
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
    <title>Sign Up</title>
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
                <li><a class="active" href="signup.php">SignUp</a></li>
            </ul>
        </div>
        <div class="header-icons">
            <a href="cart.php"><img src="img/shopping-cart.png" alt="Cart" style="width:20px;height:20px;" /></a>
            <a href="wishlist.php"><img src="img/wishlist.png" alt="Wishlist" style="width:20px;height:20px;" /></a>
        </div>
    </section>

    <form method="post" id="form" onsubmit="return validateSignupForm()">
        <h3>Sign Up</h3>
        <?php if ($error): ?>
            <div class="form-error"><?php echo htmlspecialchars($error); ?></div>
        <?php endif; ?>
        <input class="input1" id="fn" name="firstName" type="text" placeholder="First Name *" required>
        <input class="input1" id="ln" name="lastName" type="text" placeholder="Last Name *" required>
        <input class="input1" id="user" name="username" type="text" placeholder="Username *" required>
        <input class="input1" id="email" name="email" type="email" placeholder="Email *" required>
        <input class="input1" id="pass" name="password" type="password" placeholder="Password *" required>
        <input class="input1" id="cpass" name="confirmPassword" type="password" placeholder="Confirm Password *" required>
        <input class="input1" id="dob" name="dob" type="date" placeholder="Date Of Birth " required>
        <input class="input1" id="contact" name="phone" type="text" placeholder="Contact *" required>
        <select class="select1" id="gen" name="gender" required>
            <option value="S">Select Gender</option>
            <option value="M">Male</option>
            <option value="F">Female</option>
        </select>
        <button name="submit" type="submit" class="btn">Submit</button>
    </form>

    <div class="sign">
        <a href="login.php" class="signn">Already have an account?</a>
    </div>

    <script src="script.js"></script>
</body>
</html>





