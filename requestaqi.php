<?php
session_start();

// Database connection
$host = "localhost";
$username = "root";
$password = "";
$dbname = "aqi";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch city names
$sql = "SELECT DISTINCT city FROM info ORDER BY city ASC";
$result = $conn->query($sql);

$cities = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['city'];
    }
}
$conn->close();

// Handle form submission
$error = "";
if ($_SERVER["REQUEST_METHOD"] === "POST") {
    if (!isset($_POST['cities']) || count($_POST['cities']) !== 10) {
        $error = "Please select exactly 10 cities.";
    } else {
        $_SESSION['selectedCities'] = $_POST['cities'];
        header("Location: showaqi.php");
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select 10 Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
            background-color: #f9f9f9;
            text-align: center;
        }
        form {
            display: inline-block;
            text-align: left;
            margin-top: 20px;
        }
        .checkbox-list {
            columns: 2;
            -webkit-columns: 2;
            -moz-columns: 2;
        }
        .checkbox-list label {
            display: block;
            margin: 5px 0;
        }
        .error {
            color: red;
            font-weight: bold;
        }
        .submit-btn {
            margin-top: 20px;
            padding: 10px 25px;
            font-size: 16px;
            cursor: pointer;
        }
    </style>
</head>
<body>

<h2>Select Exactly 10 Cities</h2>

<?php if ($error): ?>
    <p class="error"><?php echo $error; ?></p>
<?php endif; ?>

<form method="POST" action="">
    <div class="checkbox-list">
        <?php foreach ($cities as $city): ?>
            <label>
                <input type="checkbox" name="cities[]" value="<?php echo htmlspecialchars($city); ?>">
                <?php echo htmlspecialchars($city); ?>
            </label>
        <?php endforeach; ?>
    </div>
    <button type="submit" class="submit-btn">Submit</button>
</form>

</body>
</html>
