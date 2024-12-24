<?php
// Database configuration
$servername = "localhost";
$username = "root"; // Replace with your database username
$password = ""; // Replace with your database password
$dbname = "lab_5b"; // Replace with your database name

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Collect form data
$matric = $_POST['matric'];
$name = $_POST['name'];
$password_raw = $_POST['password'];
$role = $_POST['role'];

// Hash the password
$password_hashed = password_hash($password_raw, PASSWORD_BCRYPT);

// Prepare and bind the SQL statement to prevent SQL injection
$sql = "INSERT INTO users (matric, name, password, role) VALUES (?, ?, ?, ?)";
$stmt = $conn->prepare($sql);

// Bind the parameters to the statement
$stmt->bind_param("ssss", $matric, $name, $password_hashed, $role);

// Execute the statement and check if successful
if ($stmt->execute()) {
    // Redirect to the login page with success message
    header("Location: login.php?registered=1");
    exit();
} else {
    // Display error if registration fails
    echo "Error: " . $stmt->error;
}

// Close the statement and connection
$stmt->close();
$conn->close();
?>
