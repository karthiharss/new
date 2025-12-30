<?php
session_start();
include "db.php";

if ($_SERVER["REQUEST_METHOD"] !== "POST") {
    header("Location: admin_login.html");
    exit();
}

$username = mysqli_real_escape_string($conn, $_POST['username']);
$password = $_POST['password'];

$sql = "SELECT * FROM admin WHERE username='$username'";
$result = mysqli_query($conn, $sql);

if ($row = mysqli_fetch_assoc($result)) {

    if (password_verify($password, $row['password'])) {

        // ✅ SET SINGLE ADMIN SESSION
        $_SESSION['admin'] = true;
        $_SESSION['admin_id'] = $row['id'];
        $_SESSION['admin_username'] = $row['username'];

        header("Location: admin_dashboard.php");
        exit();

    } else {
        echo "<script>alert('Invalid password'); window.location='admin_login.html';</script>";
    }

} else {
    echo "<script>alert('Admin not found'); window.location='admin_login.html';</script>";
}
?>
