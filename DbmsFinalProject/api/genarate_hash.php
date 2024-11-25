<?php
// Generate a hash for the password 'admin123'
$password = 'admin123';
$hashedPassword = password_hash($password, PASSWORD_DEFAULT);

// Display the new hashed password
echo "Generated hash for 'admin123': " . $hashedPassword;
?>
