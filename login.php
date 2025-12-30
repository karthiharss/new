<?php
session_start();
include "db.php";

// Only process POST requests
if ($_SERVER["REQUEST_METHOD"] === "POST") {

    // Get input values safely
    $email = mysqli_real_escape_string($conn, trim($_POST['email']));
    $password = $_POST['password'];

    // Check if email exists
    $sql = "SELECT * FROM registration WHERE email='$email'";
    $result = mysqli_query($conn, $sql);

    if ($result && mysqli_num_rows($result) === 1) {
        $row = mysqli_fetch_assoc($result);

        // Verify password
        if (password_verify($password, $row['password'])) {
            // Set session
            $_SESSION['user'] = $row['fullname'];
            $_SESSION['email'] = $row['email'];

            // Redirect to index
            header("Location: index.html");
            exit();
        } else {
            echo "<script>alert('Incorrect password'); window.location.href='login.html';</script>";
            exit();
        }
    } else {
        echo "<script>alert('Email not found'); window.location.href='login.html';</script>";
        exit();
    }

} else {
    // Redirect if accessed directly
    header("Location: login.html");
    exit();
}
?>
