<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['form_data'])) {
    $data = $_SESSION['form_data'];

    // DB connection
    $conn = new mysqli('localhost', 'root', '', 'aqi');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO userinfo (name, email, password, dob, age, country, gender, terms, cookies, bg_color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissssss",
        $data['name'],
        $data['email'],
        $data['password'],
        $data['dob'],
        $data['age'],
        $data['country'],
        $data['gender'],
        $data['terms'],
        $data['cookies'],
        $data['color']
    );

    if ($stmt->execute()) {
        // Set cookie
        setcookie("user_color", $data['color'], time() + 86400, "/");
        unset($_SESSION['form_data']);
        header("Location: index.php");
        exit();
    } else {
        echo "Error: " . $stmt->error;
    }

    $stmt->close();
    $conn->close();

} else {
    echo "Invalid request.";
}
?>
