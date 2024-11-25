<?php
// The plain password you want to hash
$password = '12345'; // Replace this with the password you want to hash

// Hash the password using bcrypt (password_hash automatically uses bcrypt)
$hashed_password = password_hash($password, PASSWORD_DEFAULT);

// Output the hashed password (so you can copy it)
echo $hashed_password;
?>
