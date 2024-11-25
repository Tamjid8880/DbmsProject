<?php
// Test Password Verification
$enteredPassword = '1234'; // The password you're testing
$storedHashedPassword = '$2y$10$NVBCbLK1fkOxihgosP6sBu1DMUGUF0iH94hYNECDh4L'; // Replace this with the actual hash from the database

// Check if the entered password matches the stored hashed password
if (password_verify($enteredPassword, $storedHashedPassword)) {
    echo "Password is correct!";
} else {
    echo "Invalid password.";
}
?>
