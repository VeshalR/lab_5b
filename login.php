<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Collect the form data
    $matric = $_POST['matric'];
    $password = $_POST['password'];

    // Database configuration
    $servername = "localhost";
    $username = "root"; // Replace with your database username
    $password_db = ""; // Replace with your database password
    $dbname = "lab_5b"; // Replace with your database name

    // Create a connection
    $conn = new mysqli($servername, $username, $password_db, $dbname);

    // Check connection
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Fetch the user by matric
    $sql = "SELECT * FROM users WHERE matric = ?";
    $stmt = $conn->prepare($sql);
    $stmt->bind_param("s", $matric);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, now verify the password
        $user = $result->fetch_assoc();
        if (password_verify($password, $user['password'])) {
            // Password is correct, set session variables
            $_SESSION['user_id'] = $user['matric'];
            $_SESSION['user_name'] = $user['name'];
            $_SESSION['user_role'] = $user['role'];

            // Redirect to the display users page
            header("Location: display_users.php");
            exit();
        } else {
            // Invalid password, set error message to display
            $error_message = "<p>Invalid password, try <a href='login.php'>login again</a>.</p>";
        }
    } else {
        // User not found
        $error_message = "<p>User not found.</p>";
    }

    // Close the statement and connection
    $stmt->close();
    $conn->close();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title> <!-- Title inside the head section -->
    <link rel="stylesheet" href="styles/styles.css">
</head>
<body>
    <h1>Login</h1>
    <form action="login.php" method="POST">
        <label for="matric">Matric Number:</label>
        <input type="text" name="matric" id="matric" required>
        <br>
        <label for="password">Password:</label>
        <input type="password" name="password" id="password" required>
        <br>
        <button type="submit">Login</button>
        <br>
        <p>
        <a href="registration.html">Register</a> here if you have not.
        </p>
        <!-- Display error message if set -->
        <?php if (isset($error_message)) echo $error_message; ?>
    </form>
</body>
</html>
