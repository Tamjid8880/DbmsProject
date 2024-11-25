<?php
session_start();
include 'connection.php'; // Ensure the database connection is included

// Check if the user is logged in as a voter
if (!isset($_SESSION['voter_id'])) {
    header('Location: voter_login.php');
    exit();
}

$voterId = $_SESSION['voter_id']; // Get the logged-in voter ID

// Fetch voter data to ensure they have the correct role (voter role is 1)
$voterQuery = "SELECT * FROM user WHERE id = $voterId AND role = 1";
$voterResult = mysqli_query($connect, $voterQuery);

// Check if the voter exists and is approved
if (mysqli_num_rows($voterResult) == 0) {
    echo "Voter not found or not approved.";
    exit();
}

$voter = mysqli_fetch_assoc($voterResult);

// Check if the voter has already voted
$voteCheckQuery = "SELECT * FROM votes WHERE voter_id = $voterId";
$voteCheckResult = mysqli_query($connect, $voteCheckQuery);
$hasVoted = mysqli_num_rows($voteCheckResult) > 0;

if ($hasVoted) {
    echo "You have already voted. Thank you!";
    exit();
}

// Handle vote submission
if ($_SERVER['REQUEST_METHOD'] === 'POST' && !$hasVoted) {
    // Retrieve the selected group ID from the form
    $selectedGroupId = $_POST['group_id'];

    // Insert the vote into the votes table
    $voteQuery = "INSERT INTO votes (voter_id, group_id) VALUES ($voterId, $selectedGroupId)";
    if (mysqli_query($connect, $voteQuery)) {
        header('Location: vote_success.php');
        exit();
    } else {
        echo "Failed to submit your vote. Please try again.";
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote Interface</title>
</head>
<body>
    <h2>Cast Your Vote</h2>

    <?php if ($hasVoted): ?>
        <p>You have already voted. Thank you!</p>
    <?php else: ?>
        <form method="POST">
            <label for="group_id">Choose a candidate/group:</label>
            <select name="group_id" id="group_id" required>
                <?php
                // Fetch approved groups for voting
                $approvedGroupsQuery = "SELECT * FROM groups WHERE is_approved = 1";
                $approvedGroupsResult = mysqli_query($connect, $approvedGroupsQuery);

                // Check if there are approved groups available
                if (mysqli_num_rows($approvedGroupsResult) > 0) {
                    while ($group = mysqli_fetch_assoc($approvedGroupsResult)) {
                        echo '<option value="' . $group['id'] . '">' . $group['name'] . '</option>';
                    }
                } else {
                    echo '<option value="">No approved groups available</option>';
                }
                ?>
            </select><br><br>
            <button type="submit">Submit Vote</button>
        </form>
    <?php endif; ?>
</body>
</html>
