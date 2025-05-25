<?php
session_start();

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_SESSION['form_data'])) {
    $data = $_SESSION['form_data'];

    // Server-side password match check for safety
    if ($data['password'] !== $data['confirm_password']) {
        echo "Error: Password and Confirm Password do not match.";
        exit();
    }

    // DB connection
    $conn = new mysqli('localhost', 'root', '', 'aqi');
    if ($conn->connect_error) {
        die("Connection failed: " . $conn->connect_error);
    }

    // Store plain text password (NOT RECOMMENDED FOR REAL USE)
    $plain_password = $data['password'];

    // Prepare and bind
    $stmt = $conn->prepare("INSERT INTO userinfo (name, email, password, dob, age, country, gender, terms, cookies, bg_color) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
    $stmt->bind_param("sssissssss",
        $data['name'],
        $data['email'],
        $plain_password,   // Store the plain text password
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
        $_SESSION['register_message'] = "Registration successful! Please log in.";
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
