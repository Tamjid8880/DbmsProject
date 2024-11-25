<?php
// connection.php: Database connection file

$connect = mysqli_connect("localhost", "root", "", "online-voting-system");

// Check if the connection was successful
if (!$connect) {
    die("Connection failed: " . mysqli_connect_error());
}
?>
