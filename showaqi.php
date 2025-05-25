<?php
session_start();

// Check if cities are selected
if (!isset($_SESSION['selectedCities']) || count($_SESSION['selectedCities']) !== 10) {
    echo "<h3 style='color:red; text-align:center;'>Access denied or invalid selection. Please go back and select exactly 10 cities.</h3>";
    exit;
}

$selectedCities = $_SESSION['selectedCities'];

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "aqi";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Prepare SQL with placeholders
$placeholders = implode(',', array_fill(0, count($selectedCities), '?'));
$stmt = $conn->prepare("SELECT id, city, country, aqi FROM info WHERE city IN ($placeholders)");
$stmt->bind_param(str_repeat('s', count($selectedCities)), ...$selectedCities);
$stmt->execute();
$result = $stmt->get_result();

$data = [];
while ($row = $result->fetch_assoc()) {
    $data[] = $row;
}
$stmt->close();
$conn->close();

// Get background color from cookie if available
$bgColor = isset($_COOKIE['bg_color']) ? $_COOKIE['bg_color'] : '#ffffff';
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Selected AQI Info</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
            background-color: <?php echo htmlspecialchars($bgColor); ?>;
            margin: 0;
            padding: 0;
        }
        header {
            display: flex;
            justify-content: flex-end;
            align-items: center;
            padding: 10px 20px;
            background-color: #f0f0f0;
            gap: 10px;
        }
        table {
            margin: 40px auto;
            border-collapse: collapse;
            width: 80%;
            background-color: #ffffff; /* Table stays white */
        }
        table, th, td {
            border: 1px solid #555;
        }
        th, td {
            padding: 10px;
        }
        th {
            background-color: #f2f2f2;
        }
        button {
            padding: 6px 12px;
            border-radius: 4px;
            border: none;
            cursor: pointer;
        }
        .logout-btn {
            background-color: #d9534f;
            color: white;
        }
    </style>
</head>
<body>

<header>
    <a href="22-47371-2.html" target="_blank"><button>22-47371-2</button></a>
    <a href="22-47848-2.html" target="_blank"><button>22-47848-2</button></a>
    <form action="logout.php" method="post" style="margin: 0;">
        <button type="submit" class="logout-btn">Logout</button>
    </form>
</header>

<h2>AQI Information for Selected Cities</h2>

<?php if (count($data) > 0): ?>
    <table>
        <tr>
            <th>ID</th>
            <th>City</th>
            <th>Country</th>
            <th>AQI</th>
        </tr>
        <?php foreach ($data as $row): ?>
            <tr>
                <td><?php echo htmlspecialchars($row['id']); ?></td>
                <td><?php echo htmlspecialchars($row['city']); ?></td>
                <td><?php echo htmlspecialchars($row['country']); ?></td>
                <td><?php echo htmlspecialchars($row['aqi']); ?></td>
            </tr>
        <?php endforeach; ?>
    </table>
<?php else: ?>
    <p>No data found for the selected cities.</p>
<?php endif; ?>

</body>
</html>
