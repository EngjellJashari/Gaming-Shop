<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}
include("include/connect.php");

if (isset($_SESSION['aid']) && $_SESSION['aid'] > 0) {
    $aid = intval($_SESSION['aid']);
    mysqli_query($con, "DELETE FROM cart WHERE aid = $aid");
}
setcookie('remember_token', '', time() - 3600, "/");
session_destroy();
header("Location: index.php");
exit();
?>
