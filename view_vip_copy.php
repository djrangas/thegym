<?php
session_start();

if (!isset($_SESSION['username']) || $_SESSION['role'] !== 'admin') {
    header('Location: index.php');
    exit();
}

$host = 'localhost';
$db = 'gym_base';
$user = 'root';
$pass = 'root';

$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    die('Connection failed: ' . $conn->connect_error);
}

$search = '';
$sql = "SELECT * FROM vip";

if (isset($_GET['search']) && !empty($_GET['search'])) {
    $search = $conn->real_escape_string($_GET['search']);
    $sql .= " WHERE name LIKE '%$search%' 
              OR age LIKE '%$search%' 
              OR address LIKE '%$search%' 
              OR phone LIKE '%$search%' 
              OR weight LIKE '%$search%'";
}

$sql .= " ORDER BY id ASC";

$result = $conn->query($sql);
$userCount = $result->num_rows;

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>View VIP Users</title>
    <link rel="stylesheet" href="view_users.css">
</head>
<body>
    <div class="container">
        <h1>VIP User Registration Reports</h1>
        
        <p>Total VIP Users: <?php echo $userCount; ?></p>
        
        <form method="get" class="search-form">
            <input type="text" name="search" placeholder="Search by username, phone, etc." value="<?php echo htmlspecialchars($search); ?>">
            <button type="submit">Search</button>
        </form>
        
        <table>
            <thead>
                <tr>
                    <th>ID</th>
                    <th>Username</th>
                    <th>Trainer</th>
                    <th>Months</th>
                    <th>Start</th>
                    <th>End</th>
                </tr>
            </thead>
            <tbody>
                <?php 
                $counter = 1;
                if ($result->num_rows > 0): ?>
                    <?php while ($row = $result->fetch_assoc()): ?>
                        <tr>
                            <td><?php echo $counter++; ?></td> 
                            <td><?php echo htmlspecialchars($row['name']); ?></td>
                            <td><?php echo htmlspecialchars($row['command']); ?></td>
                            <td><?php echo htmlspecialchars($row['select_option3']); ?></td>
                            <td><?php echo htmlspecialchars($row['start']); ?></td>
                            <td><?php echo htmlspecialchars($row['end']); ?></td>
                        </tr>
                    <?php endwhile; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="15">No results found.</td>
                    </tr>
                <?php endif; ?>
            </tbody>
        </table>
        
        <p><a href="./index.html" class="back-link">Home</a></p>
    </div>
</body>
</html>

<?php $conn->close(); ?>