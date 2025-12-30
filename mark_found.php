<?php
$conn = new mysqli("localhost","root","","proj");
$id = $_GET['id'];
$conn->query("UPDATE missing_pets SET status='Resolved' WHERE id=$id");
header("Location: admin_missing_pets.php");
