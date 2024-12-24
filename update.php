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

    // Fetch user data
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();
    $user = $result->fetch_assoc();
}

// Handle form submission for update
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $matric = $_POST['matric'];
    $name = $_POST['name'];
    $role = $_POST['role'];

    // Update user data with new matric number, name, and role
    $sql = "UPDATE users SET matric = ?, name = ?, role = ? WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("ssss", $matric, $name, $role, $user['matric']); // Bind the old matric for updating

    if ($stmt->execute()) {
        header("Location: display_users.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Update User</title>
    <link rel="stylesheet" href="styles/styles3.css">
</head>
<body>
    <h1>Update User</h1>
    <form method="POST" action="">
    <label for="matric">Matric Number:</label>
    <input type="text" id="matric" name="matric" value="<?php echo $user['matric']; ?>" required><br><br>
    
    <label for="name">Name:</label>
    <input type="text" id="name" name="name" value="<?php echo $user['name']; ?>" required><br><br>

    <label for="role">Access Level:</label>
    <select id="role" name="role" required>
        <option value="lecturer" <?php echo $user['role'] === 'lecturer' ? 'selected' : ''; ?>>Lecturer</option>
        <option value="student" <?php echo $user['role'] === 'student' ? 'selected' : ''; ?>>Student</option>
    </select><br><br>

    <button type="submit">Update</button>
    <a href="display_users.php" class="cancel-button">Cancel</a>
</form>
</body>
</html>
