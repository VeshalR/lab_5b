<?php
// Database configuration
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "lab_5b";

// Create a connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Check if matric is provided
if (isset($_GET['matric'])) {
    $matric = $_GET['matric'];

    // Delete user
    $sql = "DELETE FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);

    if ($stmt->execute()) {
        header("Location: display_users.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}

$conn->close();
?>
