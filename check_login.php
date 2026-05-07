<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");

if (!isset($_SESSION['aid']) || $_SESSION['aid'] <= 0) {
    if (isset($_COOKIE['remember_token'])) {
        $token = mysqli_real_escape_string($con, $_COOKIE['remember_token']);
        $query = "SELECT aid FROM accounts WHERE remember_token='$token'";
        $result = mysqli_query($con, $query);
        if ($result && mysqli_num_rows($result) > 0) {
            $row = mysqli_fetch_assoc($result);
            $_SESSION['aid'] = $row['aid'];
        } else {
            header("Location: login.php");
            exit();
        }
    } else {
        header("Location: login.php");
        exit();
    }
}
?>
