<?php
// Connect to MySQL database
$conn = mysqli_connect("localhost", "root", "", "proj");
if (!$conn) {
    die("Connection failed: " . mysqli_connect_error());
}

if(isset($_FILES['image'])){
    $image_name = $_FILES['image']['name'];
    $image_tmp = $_FILES['image']['tmp_name'];

    // Auto-rename to avoid overwriting: prepend timestamp
    $new_image_name = time() . "_" . $image_name;
    $folder = "images/" . $new_image_name;

    // Generate hash of uploaded image
    $uploaded_hash = md5_file($image_tmp);

    // Check if image already exists in the database
    $found = false;
    $result = mysqli_query($conn, "SELECT * FROM pet_images");
    while($row = mysqli_fetch_assoc($result)){
        $existing_hash = md5_file($row['image_path']);
        if($uploaded_hash === $existing_hash){
            $found = true;
            echo "This pet image already exists in the database!";
            break;
        }
    }

    // If not found, move the uploaded file and insert into database
    if(!$found){
        if(move_uploaded_file($image_tmp, $folder)){
            // Optional: store pet_name extracted from file name
            $pet_name_parts = explode("_", pathinfo($image_name, PATHINFO_FILENAME));
            $pet_name = $pet_name_parts[0]; // e.g., Abyssinian, Beagle, etc.

            $sql = "INSERT INTO petimages (pet_name, image_path) VALUES ('$pet_name', '$folder')";
            if(mysqli_query($conn, $sql)){
                echo "New pet image added to the database!";
            } else {
                echo "Database insert failed: " . mysqli_error($conn);
            }
        } else {
            echo "Failed to upload image.";
        }
    }
}
?>
