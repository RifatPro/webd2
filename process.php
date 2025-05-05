<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Document</title>
</head>
<body>
<?php
if ($_SERVER["REQUEST_METHOD"] == "POST") {

    // Sanitize and collect all values
    $name = htmlspecialchars(trim($_POST['name']));
    $email = htmlspecialchars(trim($_POST['email']));
    $password = $_POST['password']; // Display for demo; never show in production
    $confirmPassword = $_POST['confirm_password'];
    $dob = $_POST['dob'];
    $country = htmlspecialchars(trim($_POST['country']));
    $gender = isset($_POST['gender']) ? $_POST['gender'] : 'Not selected';
    $terms = isset($_POST['scales']) ? "Yes" : "No";
    $cookies = isset($_POST['horns']) ? "Yes" : "No";
    $color = isset($_POST['bg_color']) ? $_POST['bg_color'] : 'Not selected';

    // Calculate Age
    $age = 'N/A';
    if (!empty($dob)) {
        $birthDate = new DateTime($dob);
        $today = new DateTime();
        $age = $today->diff($birthDate)->y;
    }

    // Output all the data
    echo "<h2>Form Data Received (Left Top Section):</h2>";
    echo "<table border='1' cellpadding='8' cellspacing='0'>";
    echo "<tr><th>Field</th><th>Value</th></tr>";
    echo "<tr><td>Full Name</td><td>$name</td></tr>";
    echo "<tr><td>Email</td><td>$email</td></tr>";
    echo "<tr><td>Password</td><td>$password</td></tr>";
    echo "<tr><td>Confirm Password</td><td>$confirmPassword</td></tr>";
    echo "<tr><td>Date of Birth</td><td>$dob</td></tr>";
    echo "<tr><td>Age</td><td>$age</td></tr>";
    echo "<tr><td>Country</td><td>$country</td></tr>";
    echo "<tr><td>Gender</td><td>$gender</td></tr>";
    echo "<tr><td>Favorite Color</td><td><div style='width:30px; height:20px; background:$color; border:1px solid #000;'></div> ($color)</td></tr>";
    echo "<tr><td>Agreed to Terms</td><td>$terms</td></tr>";
    echo "<tr><td>Allowed Cookies</td><td>$cookies</td></tr>";
    echo "</table>";

} else {
    echo "<h2>Error</h2><p>Form was not submitted correctly.</p>";
}
?>

</body>
</html>