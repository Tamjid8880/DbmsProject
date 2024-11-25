<?php
session_start();
include 'connection.php'; // Include the connection file

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location:admin_login.php'); // Redirect if not logged in
    exit();
}

// Fetch unapproved voters
$votersQuery = "SELECT * FROM user WHERE is_approved = 0"; // Fetch only unapproved voters
$votersResult = mysqli_query($connect, $votersQuery);

// Handle error if query fails
if (!$votersResult) {
    die('Error executing query for voters: ' . mysqli_error($connect));
}

// Fetch unapproved groups
$groupsQuery = "SELECT * FROM groups WHERE is_approved = 0"; // Fetch only unapproved groups
$groupsResult = mysqli_query($connect, $groupsQuery);

// Handle error if query fails
if (!$groupsResult) {
    die('Error executing query for groups: ' . mysqli_error($connect));
}

// Handle voter approval (from URL parameters)
if (isset($_GET['approve_voter'])) {
    $voterId = $_GET['approve_voter'];
    // Update the voter to approved (is_approved = 1)
    mysqli_query($connect, "UPDATE user SET is_approved = 1 WHERE id = $voterId");
    header('Location:admin_dashboard.php'); // Redirect to refresh the page
    exit();
}

// Handle group approval (from URL parameters)
if (isset($_GET['approve_group'])) {
    $groupId = $_GET['approve_group'];
    // Update the group to approved (is_approved = 1)
    mysqli_query($connect, "UPDATE groups SET is_approved = 1 WHERE id = $groupId");
    header('Location: admin_dashboard.php'); // Redirect to refresh the page
    exit();
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Admin Dashboard</title>
</head>
<body>
    <h2>Admin Dashboard</h2>
    <a href="logout.php">Logout</a>

    <h3>Pending Voter Approvals</h3>
    <ul>
        <?php if (mysqli_num_rows($votersResult) > 0): ?>
            <?php while ($voter = mysqli_fetch_assoc($votersResult)) { ?>
                <li>
                    <?php echo $voter['name']; ?> - <?php echo $voter['email']; ?>
                    <a href="?approve_voter=<?php echo $voter['id']; ?>">Approve</a> <!-- Approval Link -->
                </li>
            <?php } ?>
        <?php else: ?>
            <li>No pending voters for approval.</li>
        <?php endif; ?>
    </ul>

    <h3>Pending Group Approvals</h3>
    <ul>
        <?php if (mysqli_num_rows($groupsResult) > 0): ?>
            <?php while ($group = mysqli_fetch_assoc($groupsResult)) { ?>
                <li>
                    <?php echo $group['name']; ?>
                    <a href="?approve_group=<?php echo $group['id']; ?>">Approve</a> <!-- Approval Link -->
                </li>
            <?php } ?>
        <?php else: ?>
            <li>No pending groups for approval.</li>
        <?php endif; ?>
    </ul>
</body>
</html>
