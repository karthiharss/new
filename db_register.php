<?php
$conn_reg = new mysqli("localhost", "root", "", "registration_db");

if ($conn_reg->connect_error) {
    die("Registration DB Connection Failed: " . $conn_reg->connect_error);
}
?>
