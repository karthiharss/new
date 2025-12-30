<?php
include "db.php";

/* AJAX MATCH HANDLER */
if (isset($_POST['ajax_match'])) {
    $id = (int) $_POST['ajax_match'];

    mysqli_query($conn, "UPDATE found_pets SET matched = 1 WHERE id = $id");

    echo "matched";
    exit;
}
?>


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

/* FETCH FOUND PETS */
$found = $conn->query("
    SELECT id, species, breed, location, contact, image, created_at, matched 
    FROM found_pets 
    ORDER BY created_at DESC
");
?><!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Admin – Found Pets</title>
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
    border-bottom:1px solid #eee;
    text-align:left;
}
th{background:#fafafa}

img{
    width:60px;
    height:60px;
    object-fit:cover;
    border-radius:10px;
}

/* STATUS */
.status{
    padding:6px 12px;
    border-radius:20px;
    font-size:12px;
    font-weight:600;
}
.matched{background:#e6fffa;color:#009688}
.not-matched{background:#fff3e0;color:#ff9800}

/* ACTIONS */
.actions a{
    margin-right:10px;
    text-decoration:none;
    font-size:13px;
    color:#ff6f61;
    font-weight:600;
}
.actions a:hover{text-decoration:underline}
</style>
</head>
<body>
<h2>Found Pets</h2>

<table>
<tr>
    <th>Pet Image</th>
    <th>Species / Breed</th>
    <th>Found Location</th>
    <th>Date Found</th>
    <th>Contact</th>
    <th>Status</th>
    <th>Actions</th>
</tr>

<?php if ($found && $found->num_rows > 0) { ?>
    <?php while($f = $found->fetch_assoc()){ ?>
    <tr>
        <td>
            <img src="uploads/<?= htmlspecialchars($f['image']) ?>"
                 onerror="this.src='assets/no-pet.png'">
        </td>

        <td>
            <?= htmlspecialchars($f['species']) ?><br>
            <small><?= htmlspecialchars($f['breed']) ?></small>
        </td>

        <td><?= htmlspecialchars($f['location']) ?></td>

        <td><?= date("d M Y", strtotime($f['created_at'])) ?></td>

        <td><?= htmlspecialchars($f['contact']) ?></td>

        <td>
            <?php if ($f['matched'] == 1) { ?>
                <span class="status matched">Matched</span>
            <?php } else { ?>
                <span class="status not-matched">Pending</span>
            <?php } ?>
        </td>

        <td class="actions">
            <?php if ($f['matched'] == 0): ?>
                <a href="admin_found_pets.php?match_id=<?= $f['id'] ?>"
                    class="match"
                    onclick="return confirm('Mark this pet as matched?')">
                    
                </a>
            <?php else: ?>
                —
            <?php endif; ?>

            <a href="match_pet.php?id=<?= $f['id'] ?>">Match</a>
            <a href="delete_found_pet.php?id=<?= $f['id'] ?>"
               onclick="return confirm('Delete this report?')">Delete</a>
        </td>
    </tr>
    <?php } ?>
<?php } else { ?>
    <tr>
        <td colspan="7">No found pets reported.</td>
    </tr>
<?php } ?>
</table>
</body>
</html>

