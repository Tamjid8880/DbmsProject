<?php
session_start();
include 'connection.php'; // Include the connection file

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Get form data (mob is used instead of email)
    $mobile = isset($_POST['mob']) ? $_POST['mob'] : null;
    $password = isset($_POST['pass']) ? $_POST['pass'] : null;
    $role = isset($_POST['role']) ? $_POST['role'] : null;

    if (!$mobile || !$password) {
        die("Mobile and Password are required.");
    }

    // Check if the user is registered, approved, and has the correct role
    $stmt = $connect->prepare("SELECT * FROM user WHERE mobile = ? AND is_approved = 1 AND role = ?");
    $stmt->bind_param("si", $mobile, $role);
    $stmt->execute();
    $result = $stmt->get_result();

    if ($result->num_rows > 0) {
        // User found, fetch user data
        $user = $result->fetch_assoc();

        // Debugging: Print stored hashed password
        echo "Stored hashed password (from DB): " . $user['password'] . "<br>";
        echo "Entered password: " . $password . "<br>";
        echo (password_verify($password, $user['password']));
        echo ('hello');
        // Verify password using password_verify()
        if (password_verify($password, $user['password'])) {
            // Password matches, proceed with login
            $_SESSION['voter_id'] = $user['id'];
            $_SESSION['name'] = $user['name'];
            $_SESSION['role'] = $user['role'];
            $_SESSION['status'] = $user['status'];
            $_SESSION['is_approved'] = $user['is_approved'];
            $_SESSION['photo'] = $user['photo'];

            // Redirect to the dashboard for voting
            header("Location:../routes/dashboard.php");
            exit();
        } else {
            echo "Incorrect password. Please try again.";
        }
    } else {
        echo "Voter not found or not approved.";
    }

    $stmt->close();
    $connect->close();
}
?>
