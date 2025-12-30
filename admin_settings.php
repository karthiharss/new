<?php
include "db.php";
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Settings – Pet Rescue</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@300;400;500;600;700&display=swap" rel="stylesheet">
<style>
    body {
        font-family: 'Poppins', sans-serif;
        margin: 0;
        background-color: #f4f4f4;
        color: #1f2937;
    }
    html, body {
    max-width: 100%;
    overflow-x: hidden;
}
    .top-menu {
    width: 100%;
    box-sizing: border-box;
    background-color: #ffffff; /* White top menu like dashboard */
    display: flex;
    position: sticky;
    top: 0;
    z-index: 1000;
    justify-content: space-between;
    align-items: center;
    padding: 15px 30px;
    border-bottom: 1px solid #e5e7eb; /* subtle border */
}

.top-menu .logo {
    font-size: 20px;
    font-weight: 600;
    color: #f87171; /* Coral/orange accent from dashboard */
}

.top-menu nav a {
    color: #1f2937; /* Dark text like dashboard tabs */
    margin-left: 20px;
    text-decoration: none;
    font-weight: 500;
    transition: 0.3s;
}

.top-menu nav a.active {
    background-color: #f87171; /* Active tab coral background */
    color: white;
    padding: 8px 12px;
    border-radius: 6px;
}

.top-menu nav a:hover {
    color: #f87171; /* Hover effect */
}


.settings-container {
    max-width: 800px;          /* keeps card compact */
    margin: 40px auto;         /* centers it */
    padding: 30px 40px;        /* reduced padding */
    box-sizing: border-box;
    background-color: #ffffff;
    color: #1f2937;
    border-radius: 12px;
    box-shadow: 0 6px 15px rgba(0,0,0,0.05);
}

    h2 {
        margin-bottom: 25px;
        font-weight: 600;
        color: #111827;
    }

    .settings-section {
        margin-bottom: 30px;
    }

    .settings-section h3 {
        margin-bottom: 15px;
        font-weight: 500;
        color: #4b5563;
    }

    .settings-section label {
        display: block;
        margin-bottom: 25px;
        font-size: 14px;
        color: #aaa;
    }

.settings-section input[type="text"],
.settings-section input[type="email"],
.settings-section input[type="password"] {
    width: 100%;
    max-width: 420px;     /* 🔹 controls field length */
    padding: 10px 12px;
    margin-bottom: 15px;
    border-radius: 6px;
    border: 1px solid #d1d5db;
    background-color: #f9fafb;
    color: #1f2937;
    box-sizing: border-box;
}


   .save-btn, .btn {
    background-color: #f87171; /* Coral/orange like dashboard buttons */
    color: white;
    border-radius: 6px;
    padding: 8px 15px;
    font-weight: 500;
    transition: 0.3s;
    text-decoration: none;
}

.save-btn:hover, .btn:hover {
    background-color: #dc2626; /* Slightly darker red/coral on hover */
}

</style>
</head>
<body>

<!-- Top Menu -->
<div class="top-menu">
    <div class="logo">Settings</div>
    <nav>
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_settings.php">Settings</a>
        <a href="logout.php">Logout</a>
    </nav>
</div>

<!-- Settings Card -->
<div class="settings-container">
    <h2>Admin Settings</h2>

    <!-- Profile Section -->
    <div class="settings-section">
        <h3>Profile</h3>
        <form action="update_profile.php" method="POST">
            <label for="admin_name">Name</label>
            <input type="text" name="admin_name" id="admin_name" value="Admin User" required>

            <label for="admin_email">Email</label>
            <input type="email" name="admin_email" id="admin_email" value="admin@petrescue.com" required>

        </form>
    </div>

    <!-- Change Password Section -->
    <div class="settings-section">
        <h3>Change Password</h3>
        <form action="change_password.php" method="POST">
            <label for="current_password">Current Password</label>
            <input type="password" name="current_password" id="current_password" required>

            <label for="new_password">New Password</label>
            <input type="password" name="new_password" id="new_password" required>

            <label for="confirm_password">Confirm New Password</label>
            <input type="password" name="confirm_password" id="confirm_password" required>

            <button type="submit" class="save-btn">Change Password</button>
        </form>
    </div>

    <!-- Portal Settings Section -->
    <div class="settings-section">
        <h3>Portal Settings (Optional)</h3>
        <form action="update_portal.php" method="POST">
            <label for="portal_name">Portal Name</label>
            <input type="text" name="portal_name" id="portal_name" value="Pet Rescue Portal" required>

            <label for="contact_email">Contact Email</label>
            <input type="email" name="contact_email" id="contact_email" value="contact@petrescue.com" required>

            <button type="submit" class="save-btn">Update Portal Settings</button>
        </form>
    </div>
</div>

</body>
</html>
