<?php
session_start();


/* ADMIN AUTH CHECK */
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost", "root", "", "proj");
$totalUsers = $conn->query("SELECT COUNT(*) c FROM user")->fetch_assoc()['c'];

$activeUsers = $conn->query("
    SELECT COUNT(*) c FROM user WHERE status='Active'
")->fetch_assoc()['c'];

$inactiveUsers = $conn->query("
    SELECT COUNT(*) c FROM user WHERE status='Inactive'
")->fetch_assoc()['c'];

if ($conn->connect_error) {
    die("DB Connection Failed");
}

/* FETCH USERS */
$users = $conn->query("
    SELECT user_id, name, email, role, created_at
    FROM user
    ORDER BY created_at DESC
");
$where = "1";

if (!empty($_GET['search'])) {
    $s = $conn->real_escape_string($_GET['search']);
    $where .= " AND u.name LIKE '%$s%'";
}

$role = $_GET['role'] ?? '';

$users = $conn->query("
    SELECT 
    u.user_id,
    u.name,
    u.email,
    u.status,
    u.created_at,
    COUNT(DISTINCT m.id) AS missing_count,
    COUNT(DISTINCT f.id) AS found_count
FROM user u
LEFT JOIN missing_pets m ON u.user_id = m.user_id
LEFT JOIN found_pets f ON u.user_id = f.user_id
WHERE $where
GROUP BY u.user_id

");

?>
<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin – Users</title>
<link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400;600&display=swap" rel="stylesheet">

<style>
body{
    margin:0;
    padding:30px;
    font-family:Poppins,sans-serif;
    background:#f5f7fb;
}

h2{margin-bottom:20px}

/* TABLE */
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
    border-bottom:1px solid #c04343ff;
    text-align:left;
}
th{background:#fafafa}
.cards{
    display: grid;
    grid-template-columns: repeat(3, 1fr);
    gap: 20px;
    margin-bottom: 25px;
}
.stat-card{
    background:#fff;
    padding:20px;
    border-radius:16px;
    box-shadow:0 10px 30px rgba(0,0,0,.08);
}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.active{background:#e6fffa;color:#009688}
.blocked{background:#ffeaea;color:#f44336}

/* ACTIONS */
.actions a{
    margin-right:10px;
    text-decoration:none;
    font-size:13px;
    font-weight:600;
}
.block{color:#ff9800}
.delete{color:#f44336}
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

/* PAGE CONTENT SPACING */
body{
    margin:0;
    font-family:Poppins,sans-serif;
    background:#f5f7fb;
}

.main{
    margin-top:90px;
    padding:30px;
}

</style>
</head>
<body>
<!-- ===== TOP MENU (INSIDE DASHBOARD FILE) ===== -->
<div class="topbar">
    <div class="logo">🐾 Admin Panel</div>

    <div class="menu">
        <a href="admin_dashboard.php">Dashboard</a>
        <a href="admin_users.php" class="active">Users</a>
        <a href="admin_settings.php">Settings</a>
        <a href="admin_missing_pets.php">Missing Pets</a>
        <a href="admin_found_pets.php">Found Pets</a>
        <a href="index.html">Logout</a>
</div>
</div>
<div class="main">
<h2>Registered Users</h2>
<div class="cards">
  <div class="stat-card">
    <h3><?= $totalUsers ?></h3>
    <span>Total Users</span>
  </div>

  <div class="stat-card">
    <h3><?= $activeUsers ?></h3>
    <span>Active</span>
  </div>

  <div class="stat-card">
    <h3><?= $inactiveUsers ?></h3>
    <span>Inactive</span>
  </div>
</div>
<form method="GET" class="filter-box">
    <input type="text" name="search" placeholder="Search by user name">

    <select name="role">
        <option value="">All Roles</option>
        <option value="registered">Registered Pet</option>
        <option value="missing">Missing Pet</option>
        <option value="found">Found Pet</option>
    </select>

    <button type="submit">Search</button>
</form>

<table>
<tr>
    <th>Name</th>
    <th>Email</th>
    <th>Role</th>
    <th>Status</th>
    <th>Registered Date</th>
    <th>Actions</th>
</tr>

<?php if ($users && $users->num_rows > 0) { ?>
<?php while ($u = $users->fetch_assoc()) {

    if ($u['missing_count'] > 0 && $u['found_count'] > 0) {
        $roleText = "Missing & Found User";
    } elseif ($u['missing_count'] > 0) {
        $roleText = "Missing Pet User";
    } elseif ($u['found_count'] > 0) {
        $roleText = "Found Pet User";
    } else {
        $roleText = "Registered Pet User";
    }

    // Role filter
    if ($role == 'missing' && $u['missing_count'] == 0) continue;
    if ($role == 'found' && $u['found_count'] == 0) continue;
    if ($role == 'registered' && ($u['missing_count'] > 0 || $u['found_count'] > 0)) continue;
?>
<tr>
    <td><?= htmlspecialchars($u['name']) ?></td>
    <td><?= htmlspecialchars($u['email']) ?></td>
    <td><?= $roleText ?></td>
    <td>
        <?php if ($u['status'] === 'Active') { ?>
            <span class="status active">Active</span>
        <?php } else { ?>
            <span class="status inactive">Inactive</span>
        <?php } ?>
    </td>
    <td><?= date("d M Y", strtotime($u['created_at'])) ?></td>
    <td class="actions">
        <?php if ($u['status'] === 'Active') { ?>
            <a class="block" href="block_user.php?id=<?= $u['user_id'] ?>">Block</a>
        <?php } else { ?>
            <a class="block" href="unblock_user.php?id=<?= $u['user_id'] ?>">Unblock</a>
        <?php } ?>
        <a class="delete"
           href="delete_user.php?id=<?= $u['user_id'] ?>"
           onclick="return confirm('Delete this user?')">Delete</a>
    </td>
</tr>
<?php } ?>
<?php } else { ?>
<tr>
    <td colspan="6">No users found.</td>
</tr>
<?php } ?>

</table>
</div>
</body>
</html>
