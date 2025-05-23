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
        }
        table {
            margin: auto;
            border-collapse: collapse;
            width: 80%;
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
    </style>
</head>
<body>

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
