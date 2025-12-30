<?php
$success_message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {

    include "db.php";

    // Collect form data safely
    $owner_name   = $_POST['owner_name'] ?? '';
    $pet_name     = $_POST['pet_name'] ?? '';
    $date_missing = $_POST['date_missing'] ?? '';
    $breed        = $_POST['breed'] ?? '';
    $age          = $_POST['age'] ?? '';
    $address      = $_POST['address'] ?? '';
    $description  = $_POST['description'] ?? '';
    $contact      = $_POST['contact'] ?? '';

    // Handle image upload
    $imageName = "";
    if (!empty($_FILES['pet_image']['name'])) {
        $imageName = time() . "_" . $_FILES['pet_image']['name'];
        if (!move_uploaded_file($_FILES['pet_image']['tmp_name'], "uploads/" . $imageName)) {
            $success_message = "Error uploading image.";
        }
    }

    // Insert into DB if no upload errors
    if ($success_message == "") {
        $sql = "INSERT INTO missing_pets
        (owner_name, pet_name, date_missing, breed, age, address, description, contact, image, status)
        VALUES
        ('$owner_name','$pet_name','$date_missing','$breed','$age','$address','$description','$contact','$imageName','Pending')";

        if (mysqli_query($conn, $sql)) {
            $success_message = "Pet reported successfully! Thank you for reporting.";
        } else {
            $success_message = "Database error: " . mysqli_error($conn);
        }
    }

    mysqli_close($conn);
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
<meta charset="UTF-8">
<title>Report Missing Pet</title>
<meta name="viewport" content="width=device-width, initial-scale=1">
<style>
body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 50px; }
form { background: #fff; padding: 30px; border-radius: 10px; width: 100%; max-width: 600px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
input, select, textarea, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; }
button { background: #ff6f61; color: #fff; border: none; cursor: pointer; }
button:hover { background: #ff4c3b; }
.back { text-align: center; margin-top: 15px; }
.back a { color: #ff6f61; text-decoration: none; font-weight: 600; }
.success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
</style>
</head>
<body>
<form action="" method="POST" enctype="multipart/form-data">
    <center>
        <h2>Report Missing Pet</h2>
    </center>

    <!-- Show success message here -->
    <?php if($success_message != ""): ?>
        <div class="success"><?php echo $success_message; ?></div>
    <?php endif; ?>

    <div class="back">
        <a href="index.html">← Back to Home</a>
    </div>
</form>
</body>
</html>
