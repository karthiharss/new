<?php 
include "session_protect.php"; 
?>

<!DOCTYPE html>
<html>
<head>
<title>Dashboard</title>
<style>
body {
  font-family: Arial, sans-serif;
  background:#ffece7;
  padding:40px;
}
.box {
  max-width:600px;
  margin:auto;
  background:white;
  padding:30px;
  border-radius:18px;
  box-shadow:0 5px 20px rgba(0,0,0,0.15);
  text-align:center;
}
.btn {
  padding:10px 22px;
  background:#ff6f61;
  color:white;
  text-decoration:none;
  border-radius:10px;
  font-weight:bold;
}
</style>
</head>

<body>

<div class="box">
  <h2>Welcome, <?php echo $_SESSION['user']; ?> 👋</h2>
  <p>You have successfully logged in to PetRescue.</p>

  <a class="btn" href="logout.php">Logout</a>
</div>

</body>
</html>
