<?php
include 'connection.php'; // Include connection.php from the same api folder

// Initialize error variable
$error_message = "";

// Process the form submission if the form is submitted
if ($_SERVER['REQUEST_METHOD'] == 'POST') {
    // Retrieve form data with sanitization
    $name = mysqli_real_escape_string($connect, $_POST['name']);
    $mobile = mysqli_real_escape_string($connect, $_POST['mob']);
    $pass = $_POST['pass'];
    $cpass = $_POST['cpass'];
    $add = mysqli_real_escape_string($connect, $_POST['add']);
    $image = $_FILES['image']['name'];
    $tmp_name = $_FILES['image']['tmp_name'];
    $role = $_POST['role'];

    // Validate password and mobile number
    if ($cpass != $pass) {
        $error_message = "Passwords do not match!";
    } elseif (strlen($mobile) != 10) {
        $error_message = "Mobile number must be 10 digits only!";
    } else {
        // Hash the password for security
        echo("$pass");
        
        $hashed_password = password_hash($pass, PASSWORD_DEFAULT);
        echo("$hashed_password");

        // Validate the image file type
        $allowedTypes = ['image/jpeg', 'image/png', 'image/gif'];
        if (!in_array($_FILES['image']['type'], $allowedTypes)) {
            $error_message = "Invalid image type. Only JPG, PNG, GIF are allowed.";
        } else {
            // Ensure the uploads directory exists
            if (!is_dir("../uploads")) {
                mkdir("../uploads", 0777, true); // Create the directory if it doesn't exist
            }

            // Upload the user's image
            if (move_uploaded_file($tmp_name, "../uploads/$image")) {
                // Insert the new user into the user table with is_approved = 0 (not approved)
                $insert = mysqli_query($connect, "INSERT INTO user (name, mobile, password, address, photo, status, votes, role, is_approved) 
                    VALUES ('$name', '$mobile', '$hashed_password', '$add', '$image', 0, 0, '$role', 0)");

                if ($insert) {
                    echo '<script>
                            alert("Registration successful! Awaiting approval from admin.");
                             //window.location = "../";
                          </script>';
                } else {
                    $error_message = "Registration failed: " . mysqli_error($connect);
                }
            } else {
                $error_message = "Failed to upload image.";
            }
        }
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Registration Result</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            margin: 20px;
        }
        .error {
            color: red;
        }
        .form-container {
            max-width: 400px;
            margin: 0 auto;
        }
        .form-container input {
            display: block;
            width: 100%;
            padding: 10px;
            margin: 10px 0;
        }
    </style>
</head>
<body>
    <div class="form-container">
        <h2>Registration Result</h2>
        <?php
        // Display error message if there's an error
        if (!empty($error_message)) {
            echo "<p class='error'>$error_message</p>";
        } else {
            echo "<p>Registration successful. Please wait for admin approval.</p>";
        }
        ?>
    </div>
</body>
</html>
