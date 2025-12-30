<?php
include "db.php";   

// 1. Get form data
$owner_name   = $_POST['owner_name'];
$pet_name     = $_POST['pet_name'];
$date_missing = $_POST['date_missing'];
$breed        = $_POST['breed'];
$age          = $_POST['age'];
$address      = $_POST['address'];
$description  = $_POST['description'];
$contact      = $_POST['contact'];

$image_name = "";
$matches = [];

// -------------------------------
// 2. IMAGE UPLOAD + SAVE IN DB
// -------------------------------
if (isset($_FILES['pet_image']) && $_FILES['pet_image']['error'] == 0) {

    $targetDir = "uploads/";
    if (!is_dir($targetDir)) { mkdir($targetDir, 0777, true); }

    $fileName = time() . "_" . basename($_FILES["pet_image"]["name"]);
    $targetFile = $targetDir . $fileName;

    if (move_uploaded_file($_FILES["pet_image"]["tmp_name"], $targetFile)) {
        
        $image_name = $fileName;

        // Insert image in database
        $imgData = addslashes(file_get_contents($targetFile));
        $sql_img = "INSERT INTO pet_images (image_name, image_data) VALUES ('$image_name', '$imgData')";
        mysqli_query($conn, $sql_img);

        // -------------------------------
        // 3. RUN PYTHON CNN MODEL
        // -------------------------------
        $python_cmd = "python app.py \"$targetFile\"";
        $output = shell_exec($python_cmd);

        // Decode JSON safely
        $result = json_decode($output, true);
        if (is_array($result) && isset($result["matches"])) {
            $matches = $result["matches"];
        }
    }
}

// -------------------------------
// 4. SAVE MISSING PET DETAILS
// -------------------------------
$sql2 = "INSERT INTO missing_pets 
(owner_name, pet_name, date_missing, breed, age, address, description, contact, image_name)
VALUES 
('$owner_name', '$pet_name', '$date_missing', '$breed', '$age', '$address', '$description', '$contact', '$image_name')";

mysqli_query($conn, $sql2);

?>
<!DOCTYPE html>
<html>
<head>
<title>Missing Pet Report Results</title>
<style>
body { font-family: Arial; padding: 20px; }
.img-box img { width: 180px; height: 180px; object-fit: cover; border-radius: 10px; margin: 8px; }
</style>
</head>

<body>

<h1>Missing Pet Report Submitted Successfully!</h1>

<h2>Uploaded Pet:</h2>
<div class="img-box">
    <img src="uploads/<?php echo $image_name; ?>">
</div>

<h2>Top Similar Pets:</h2>

<?php
if (empty($matches)) {
    echo "<p><b>No similar pets found.</b></p>";
} else {
    foreach ($matches as $m) {
        echo "<div class='img-box'>
                <img src='dataset/{$m['image']}'>
                <p>Similarity: {$m['score']}%</p>
              </div>";
    }
}
?>

</body>
</html>
