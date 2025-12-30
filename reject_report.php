<?php
include "db.php";
$id = $_GET['id'];
$type = $_GET['type'];

$table = ($type == 'missing') ? "missing_pets" : "found_pets";
mysqli_query($conn, "UPDATE $table SET status='Rejected' WHERE id=$id");

header("Location: admin_dashboard.php");
?>
