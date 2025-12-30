<?php
session_start();
if (!isset($_SESSION['admin'])) {
    header("Location: admin_login.php");
    exit();
}

$conn = new mysqli("localhost","root","","proj");
if ($conn->connect_error) die("DB Error");

/* FILTERS */
$where = "1";

if (!empty($_GET['location'])) {
    $loc = $conn->real_escape_string($_GET['location']);
    $where .= " AND address LIKE '%$loc%'";
}

if (!empty($_GET['type'])) {
    $type = $conn->real_escape_string($_GET['type']);
    $where .= " AND species='$type'";
}

if (!empty($_GET['date'])) {
    $date = $_GET['date'];
    $where .= " AND DATE(created_at)='$date'";
}

$missing = $conn->query("SELECT * FROM missing_pets WHERE $where ORDER BY created_at DESC");
?>
<!DOCTYPE html>
<html>
<head>
<title>Admin – Missing Pets</title>
<style>
body{font-family:Poppins;background:#f5f7fb;margin:0;padding:30px}
h2{margin-bottom:20px}

.filter-box{
    background:#fff;
    padding:15px;
    border-radius:14px;
    margin-bottom:20px;
}
.filter-box input, .filter-box select{
    padding:10px;
    margin-right:10px;
    border-radius:8px;
    border:1px solid #ddd;
}

table{
    width:100%;
    background:#fff;
    border-radius:16px;
    border-collapse:collapse;
    overflow:hidden;
}
th,td{
    padding:14px;
    border-bottom:1px solid #eee;
    text-align:left;
}
th{background:#fafafa}

.pet-img{
    width:80px;
    height:80px;
    border-radius:50%;
    object-fit:cover;
    
}

.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
}
.active{background:#ffeaea;color:#f44336}
.resolved{background:#e8f7ee;color:#22a06b}

.actions a{
    margin-right:8px;
    text-decoration:none;
    padding:6px 10px;
    border-radius:8px;
    font-size:12px;
}
.view{background:#2196f3;color:white}
.found{background:#4caf50;color:white}
.delete{background:#f44336;color:white}
</style>
</head>
<body>
<h2>🐕 Missing Pets</h2>

<form class="filter-box" method="GET">
    <input type="text" name="location" placeholder="Search by location">
    
    <select name="type">
        <option value="">Pet Type</option>
        <option value="Dog">Dog</option>
        <option value="Cat">Cat</option>
    </select>

    <input type="date" name="date">
    <button type="submit">Filter</button>
</form>
<table>
<tr>
    <th>Pet</th>
    <th>Name</th>
    <th>Breed</th>
    <th>Last Seen</th>
    <th>Date</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php while($m = $missing->fetch_assoc()){ ?>
<tr>
    <td>
        <img class="pet-img" 
     src="uploads/<?= htmlspecialchars($m['image']) ?>" 
     alt="Pet Image"
     onerror="this.src='assets/no-pet.png'">

    </td>
    <td><?= htmlspecialchars($m['pet_name']) ?></td>
    <td><?= htmlspecialchars($m['breed']) ?></td>
    <td><?= htmlspecialchars($m['address']) ?></td>
    <td><?= date("d M Y", strtotime($m['created_at'])) ?></td>
    <td>
        <span class="status <?= $m['status']=='Resolved'?'resolved':'active' ?>">
            <?= $m['status'] ?>
        </span>
    </td>
    <td class="actions">
        <a class="found" href="mark_found.php?id=<?= $m['id'] ?>">Mark Found</a>
        <a class="delete" href="delete_pet.php?id=<?= $m['id'] ?>"
           onclick="return confirm('Delete this report?')">Delete</a>
    </td>
</tr>
<?php } ?>
</table>
