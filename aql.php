<?php
session_start();

// Redirect if form is submitted and valid
if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (isset($_POST['cities'])) {
        $selectedCities = $_POST['cities'];

        if (count($selectedCities) == 10) {
            $_SESSION['selectedCities'] = $selectedCities;
            header("Location: showaqi.php");
            exit;
        } else {
            $errorMessage = "Please select exactly 10 cities.";
        }
    } else {
        $errorMessage = "No cities selected. Please select at least 10 cities.";
    }
}

// Database connection to fetch cities
$host = "localhost";
$username = "root";
$password = "";
$dbname = "aqi";

$conn = new mysqli($host, $username, $password, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$cities = [];
$sql = "SELECT city FROM info";
$result = $conn->query($sql);
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $cities[] = $row['city'];
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <title>Select Cities</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            text-align: center;
        }
        .container {
            width: 60%;
            margin: auto;
        }
        .checkboxes {
            display: flex;
            flex-wrap: wrap;
            justify-content: center;
        }
        .checkboxes label {
            width: 150px;
            margin: 5px;
            text-align: left;
        }
        .error {
            color: red;
            font-weight: bold;
        }
    </style>
    <script>
        function validateForm() {
            const selected = document.querySelectorAll('input[name="cities[]"]:checked');
            if (selected.length !== 10) {
                document.getElementById('error-message').innerText = "Please select exactly 10 cities.";
                return false;
            }
            return true;
        }
    </script>
</head>
<body>

<h2>Select Exactly 10 Cities</h2>

<form method="POST" action="showaqi.php" onsubmit="return validateForm()">
    <div class="container">
        <div class="checkboxes">
            <?php foreach ($cities as $city): ?>
                <label>
                    <input type="checkbox" name="cities[]" value="<?php echo htmlspecialchars($city); ?>">
                    <?php echo htmlspecialchars($city); ?>
                </label>
            <?php endforeach; ?>
        </div>
        <br>
        <div id="error-message" class="error">
            <?php if (!empty($errorMessage)) echo $errorMessage; ?>
        </div>
        <br>
        <input type="submit" value="Submit">
    </div>
</form>

</body>
</html>
