<?php
session_start();

if (!isset($_SESSION['user'])) {
    header("Location: login.html");
    exit();
}
?>
<header class="navbar" id="navbar">
    <div class="logo">
      <span class="logo-icon">🐾</span> PET<span>RESCUE</span>
    </div>

    <nav class="nav-links">

      <a href="#hero">Home</a>
      <a href="#about">About</a>
      <a href="#features">Features</a>
      <a href="#gallery">Gallery</a>
      <a href="#how">How it works</a>

      <?php if (isset($_SESSION['user_id'])): ?>
          
          <!-- Show only when user is logged in -->
          <button class="btn-nav" onclick="document.querySelector('#cta').scrollIntoView({behavior:'smooth'})">
            Donate
          </button>

          <a href="logout.php" class="btn-nav" style="background:#ef4444;">Logout</a>

      <?php else: ?>

          <!-- Show only when user NOT logged in -->
          <a href="login.php" class="btn-nav">Login</a>
          <a href="register.php" class="btn-nav">Register</a>

      <?php endif; ?>
      
    </nav>
</header>

