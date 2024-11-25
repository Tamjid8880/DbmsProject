<?php

session_start();
include 'connection.php'; // Include the correct connection file

// Handle login form submission
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'];
    $password = $_POST['password'];

    // Query to check admin credentials
    $query = "SELECT * FROM admins WHERE username = '$username'";
    $result = mysqli_query($connect, $query); // This uses $conn for the connection

    if ($result && mysqli_num_rows($result) > 0) {
        $admin = mysqli_fetch_assoc($result);

        // Verify password (ensure you use a hashed password)
        if (password_verify($password, $admin['password'])) {
            // Correct credentials, set session
            $_SESSION['admin_id'] = $admin['id'];
            header('Location:admin_dashboard.php'); // Redirect to the dashboard
            exit();

        } else {
            $error = "Invalid password.";
        }
    } else {
        $error = "Admin not found.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Login</title>
</head>
<body>
    <h2>Admin Login</h2>
    <?php if (isset($error)) { echo "<p style='color:red;'>$error</p>"; } ?>
    <form method="POST">
        <input type="text" name="username" placeholder="Username" required><br>
        <input type="password" name="password" placeholder="Password" required><br>
        <button type="submit">Login</button>
    </form>
</body>
</html>
