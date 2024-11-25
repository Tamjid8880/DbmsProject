<?php
session_start();
include 'connection.php';

// Check if the admin is logged in
if (!isset($_SESSION['admin_id'])) {
    header('Location: admin_login.php');
    exit();
}

// Fetch vote counts for each group
$resultsQuery = "SELECT groups.name, COUNT(votes.id) as vote_count 
                 FROM groups 
                 LEFT JOIN votes ON groups.id = votes.group_id 
                 WHERE groups.is_approved = 1 
                 GROUP BY groups.id";
$resultsResult = mysqli_query($connect, $resultsQuery);

// Prepare data for the chart
$groupNames = [];
$voteCounts = [];

while ($row = mysqli_fetch_assoc($resultsResult)) {
    $groupNames[] = $row['name'];
    $voteCounts[] = $row['vote_count'];
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Vote Results</title>
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
</head>
<body>
    <h2>Vote Results</h2>
    <canvas id="voteChart" width="400" height="400"></canvas>
    <script>
        const ctx = document.getElementById('voteChart').getContext('2d');
        const voteChart = new Chart(ctx, {
            type: 'pie',
            data: {
                labels: <?php echo json_encode($groupNames); ?>,
                datasets: [{
                    label: 'Votes',
                    data: <?php echo json_encode($voteCounts); ?>,
                    backgroundColor: [
                        'rgba(75, 192, 192, 0.2)',
                        'rgba(54, 162, 235, 0.2)',
                        'rgba(255, 206, 86, 0.2)',
                        'rgba(231, 76, 60, 0.2)',
                        'rgba(155, 89, 182, 0.2)'
                    ],
                    borderColor: [
                        'rgba(75, 192, 192, 1)',
                        'rgba(54, 162, 235, 1)',
                        'rgba(255, 206, 86, 1)',
                        'rgba(231, 76, 60, 1)',
                        'rgba(155, 89, 182, 1)'
                    ],
                    borderWidth: 1
                }]
            },
            options: {
                responsive: true,
                plugins: {
                    legend: {
                        position: 'top',
                    }
                }
            }
        });
    </script>
</body>
</html>
