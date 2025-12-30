<?php
$conn = new mysqli("localhost","root","","proj");
$id = $_GET['id'];
$conn->query("DELETE FROM missing_pets WHERE id=$id");
header("Location: admin_missing_pets.php");
