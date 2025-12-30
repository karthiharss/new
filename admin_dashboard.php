<?php
session_start();

/* ADMIN AUTH CHECK */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "proj");
if ($conn->connect_error) {
    die("DB Connection Failed");
}

/* COUNTS */
$totalMissing = $conn->query("SELECT COUNT(*) c FROM missing_pets")->fetch_assoc()['c'];
$totalFound   = $conn->query("SELECT COUNT(*) c FROM found_pets")->fetch_assoc()['c'];
$totalUsers   = $conn->query("SELECT COUNT(*) c FROM user")->fetch_assoc()['c'];

/* RECENT PETS */
$recentMissing = $conn->query("SELECT pet_name, image, created_at FROM missing_pets ORDER BY created_at DESC LIMIT 3");
$recentFound   = $conn->query("SELECT species, image, created_at FROM found_pets ORDER BY created_at DESC LIMIT 3");
/* DASHBOARD CARDS DATA */
$missing = $conn->query("
    SELECT pet_name, breed, address, contact, image, created_at, 'Missing' AS status
    FROM missing_pets
    ORDER BY created_at DESC
    LIMIT 6
");

$found = $conn->query("
    SELECT id,species, breed, location, contact, image, created_at,'Found' AS status
    FROM found_pets
    ORDER BY created_at DESC
");

?>


<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin Dashboard</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
*{box-sizing:border-box;}
body{margin:0;font-family:Poppins,sans-serif;background:#f5f7fb;}

/* ===== TOP MENU ===== */
.topbar{
    position:fixed;
    top:0;
    left:0;
    width:100%;
    height:70px;
    background:#ffffff;
    border-bottom:1px solid #5574c3ff;
    display:flex;
    align-items:center;
    justify-content:space-between;
    padding:0 30px;
    z-index:100;
}

.topbar .logo{
    font-size:22px;
    font-weight:600;
    color:#ff6f61;
}

.menu{
    display:flex;
    gap:15px;
}

.menu a{
    padding:10px 18px;
    border-radius:10px;
    text-decoration:none;
    color:#555;
    font-size:14px;
    font-weight:600;
    transition:.2s;
}

.menu a.active,
.menu a:hover{
    background:#ff6f61;
    color:white;
}

/* ===== MAIN CONTENT ===== */
.main{
    margin-top:90px;
    padding:30px;
}

.header{display:flex; justify-content:space-between; align-items:center; margin-bottom:25px;}
.section-title{margin:30px 0 15px;}

/* ===== CARD GRID ===== */
.cards{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(220px,1fr));
    gap:20px;
    margin-bottom:30px;
}
.stat-card{
    background:#fff;
    padding:20px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.stat-card h3{margin:0;font-size:26px;}
.stat-card span{color:#777;font-size:14px;}

.quick-links a{
    display:inline-block;
    margin-right:10px;
    padding:10px 16px;
    background:#ff6f61;
    color:white;
    border-radius:10px;
    text-decoration:none;
    font-size:14px;
}

.recent{
    display:grid;
    grid-template-columns:repeat(auto-fit,minmax(200px,1fr));
    gap:20px;
}
.pet-box{
    background:#fff;
    padding:15px;
    border-radius:16px;
    text-align:center;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
.pet-box img{
    width:70px;height:70px;
    border-radius:50%;
    object-fit:cover;
    margin-bottom:10px;
}

table{
    width:100%;
    border-collapse:collapse;
    background:#fff;
    border-radius:16px;
    overflow:hidden;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}
th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
}
th{background:#fafafa;}

</style>
</head>
<body>

<!-- ===== TOP MENU (INSIDE DASHBOARD FILE) ===== -->
<div class="topbar">
    <div class="logo">🐾 Admin Panel</div>

    <div class="menu">
        <a href="admin_dashboard.php" class="active">Dashboard</a>
        <a href="admin_users.php">Users</a>
        <a href="admin_settings.php">Settings</a>
        <a href="admin_missing_pets.php">Missing Pets</a>
        <a href="admin_found_pets.php">Found Pets</a>
        <a href="index.html">Logout</a>
    </div>
</div>


<!-- MAIN CONTENT -->
<div class="main">
    <div class="cards">
    <div class="stat-card">
        <h3>🐕 <?= $totalMissing ?></h3>
        <span>Missing Pets</span>
    </div>

    <div class="stat-card">
        <h3>🐾 <?= $totalFound ?></h3>
        <span>Found Pets</span>
    </div>

    <div class="stat-card">
        <h3>👤 <?= $totalUsers ?></h3>
        <span>Registered Users</span>
    </div>
</div>
<div class="quick-links">
    <a href="admin_missing_pets.php">View Missing Pets</a>
    <a href="admin_found_pets.php">View Found Pets</a>
</div>
<h3>Recently Added Pets</h3>
<div class="recent">

<?php while($m = $recentMissing->fetch_assoc()){ ?>
<div class="pet-box">
    <img src="uploads/<?= htmlspecialchars($m['image']) ?>" 
     onerror="this.src='assets/no-pet.png'">
    <strong><?= htmlspecialchars($m['pet_name']) ?></strong><br>
    <small>Missing</small>
</div>
<?php } ?>

<?php while($f = $recentFound->fetch_assoc()){ ?>
<div class="pet-box">
    <img src="<?= $f['image'] ?: 'assets/no-pet.png' ?>">
    <strong><?= htmlspecialchars($f['species']) ?></strong><br>
    <small>Found</small>
</div>
<?php } ?>

</div>
<h3 style="margin-top:30px">Recent Activity</h3>

<table>
<tr>
    <th>Pet</th>
    <th>Status</th>
    <th>Date</th>
</tr>

<?php
$activity = $conn->query("
    SELECT pet_name name, 'Missing' status, created_at FROM missing_pets
    UNION ALL
    SELECT species, 'Found', created_at FROM found_pets
    ORDER BY created_at DESC LIMIT 6
");
while($a = $activity->fetch_assoc()){
?>
<tr>
    <td><?= htmlspecialchars($a['name']) ?></td>
    <td><?= $a['status'] ?></td>
    <td><?= date("d M Y", strtotime($a['created_at'])) ?></td>
</tr>
<?php } ?>
</table>

<div class="header">
    <h2>Admin Dashboard</h2>
</div>

<h3 class="section-title">Missing Pets</h3>
<div class="cards">
<?php if ($missing && $missing->num_rows > 0) { ?>
    <?php while($m = $missing->fetch_assoc()){ ?>
        <div class="card">
            <div class="status Missing"><?= $m['status'] ?></div>
            <div class="avatar">
                <img src="uploads/<?= htmlspecialchars($m['image']) ?>" 
                    alt="Pet Image"
                    onerror="this.src='assets/no-pet.png'">

            </div>
            <h4><?= htmlspecialchars($m['pet_name']) ?></h4>
            <small><?= htmlspecialchars($m['breed']) ?></small>
            <div class="info">
                <div>📍 <?= htmlspecialchars($m['address']) ?></div>
                <div>☎ <?= htmlspecialchars($m['contact']) ?></div>
            </div>
            <div class="footer"><?= date("d M Y", strtotime($m['created_at'])) ?></div>
        </div>
    <?php } ?>
<?php } else { ?>
    <p>No missing pets found.</p>
<?php } ?>

</div>

<h3 class="section-title">Found Pets</h3>
<div class="cards">
<?php if ($found && $found->num_rows > 0) { ?>
    <?php while($f = $found->fetch_assoc()){ ?>
        <div class="card">
            <div class="status Found"><?= $f['status'] ?></div>
            <div class="avatar">
                <img src="<?= $f['image'] ?: 'assets/no-pet.png' ?>">
            </div>
            <h4><?= htmlspecialchars($f['species']) ?></h4>
            <small><?= htmlspecialchars($f['breed']) ?></small>
            <div class="info">
                <div>📍 <?= htmlspecialchars($f['location']) ?></div>
                <div>☎ <?= htmlspecialchars($f['contact']) ?></div>
            </div>
            <div class="footer"><?= date("d M Y", strtotime($f['created_at'])) ?></div>
        </div>
    <?php } ?>
<?php } else { ?>
    <p>No found pets found.</p>
<?php } ?>

</div>

</div>
</body>
</html>
