<?php
$success_message = "";
if(isset($_POST['submit'])) {

    // Database connection
    $conn = new mysqli("localhost", "root", "", "proj"); // replace with your DB
    if ($conn->connect_error) { die("Connection failed: " . $conn->connect_error); }

    // Collect form data
    $pet_name = $_POST['pet_name'] ?? '';
    $age = $_POST['age'] ?? '';
    $species = $_POST['species'] ?? '';
    $breed = $_POST['breed'] ?? '';
    $gender = $_POST['gender'] ?? '';
    $pet_condition = $_POST['pet_condition'] ?? '';
    $location = $_POST['location'] ?? '';
    $description = $_POST['description'] ?? '';
    $contact = $_POST['contact'] ?? '';

    // Handle image upload
    if (isset($_FILES['image']) && $_FILES['image']['error'] === 0) {
        $image = $_FILES['image']['name'];
        $target_dir = "uploads/";
        $target_file = $target_dir . basename($image);

        if (move_uploaded_file($_FILES['image']['tmp_name'], $target_file)) {
            // Insert into DB
            $sql = "INSERT INTO found_pets 
                    (pet_name, age, species, breed, gender, pet_condition, location, description, contact, image, status, created_at) 
                    VALUES 
                    ('$pet_name', '$age', '$species', '$breed', '$gender', '$pet_condition', '$location', '$description', '$contact', '$image', 'Pending', NOW())";

            if ($conn->query($sql) === TRUE) {
                $success_message = "Pet reported successfully! Thank you for reporting.";
            } else {
                $success_message = "Database error: " . $conn->error;
            }
        } else {
            $success_message = "Sorry, there was an error uploading the image.";
        }
    } else {
        $success_message = "Please select an image to upload.";
    }

    $conn->close();
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Report Found Pet</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <style>
        body { font-family: Arial, sans-serif; background: #f4f4f4; display: flex; justify-content: center; padding: 50px; }
        form { background: #fff; padding: 30px; border-radius: 10px; width: 100%; max-width: 600px; box-shadow: 0 0 15px rgba(0,0,0,0.1); }
        input, select, textarea, button { width: 100%; padding: 10px; margin: 8px 0; border-radius: 5px; border: 1px solid #ccc; font-size: 14px; }
        button { background: #ff6f61; color: #fff; border: none; cursor: pointer; }
        button:hover { background: #ff4c3b; }
        .back {
        text-align: center;
        margin-top: 15px;
    }

    .back a {
        color: #ff6f61;
        text-decoration: none;
        font-weight: 600;
    }
        .success { background: #d4edda; color: #155724; padding: 15px; border-radius: 5px; margin-bottom: 20px; text-align: center; }
    </style>
</head>
<body>
    <form action="" method="POST" enctype="multipart/form-data">
        <center><h2>Report Found Pet</h2></center>

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
