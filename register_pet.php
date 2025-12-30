<?php
// Database connection
$conn = new mysqli("localhost", "root", "", "proj");

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Get form data
$pet_name = $_POST['pet_name'];
$pet_type = $_POST['pet_type'];
$breed = $_POST['breed'];
$age = $_POST['age'];
$gender = $_POST['gender'];
$location = $_POST['location'];
$description = $_POST['description'];
$pet_status = $_POST['pet_status'];
$health_status = $_POST['health_status'];
$vaccinated = $_POST['vaccinated'];
$owner_name = $_POST['owner_name'];
$contact_number = $_POST['contact_number'];
$email = $_POST['email'];


// Image upload
$image_name = "";
if (!empty($_FILES['image']['name'])) {
    $image_name = time() . "_" . $_FILES['image']['name'];
    move_uploaded_file($_FILES['image']['tmp_name'], "uploads/" . $image_name);
}

// Insert query
$sql = "INSERT INTO pet_registration
(pet_name, pet_type, breed, age, gender, location, description, image,
 pet_status, health_status, vaccinated, owner_name, contact_number, email)
VALUES
('$pet_name', '$pet_type', '$breed', '$age', '$gender', '$location', '$description', '$image_name',
 '$pet_status', '$health_status', '$vaccinated', '$owner_name', '$contact_number', '$email')";


if ($conn->query($sql) === TRUE) {
    echo "<script>alert('Pet registered successfully!'); window.location.href='index.html';</script>";
} else {
    echo "Error: " . $conn->error;
}

$conn->close();
?>
