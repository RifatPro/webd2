<?php
session_start();
require_once "database.php";

if ($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
  $email = $_POST["email"];
  $password = $_POST["password"];

  // 🔐 Hardcoded Admin Login
  $admin_email = "admin";
  $admin_password = "admin";

  if ($email === $admin_email && $password === $admin_password) {
    $_SESSION["admin"] = true;
    header("Location: admin_dashboard.php");
    exit();
  }

  // 👤 Regular User Login (Database)
  $sql = "SELECT * FROM userinfo WHERE email = ?";
  $stmt = mysqli_prepare($conn, $sql);
  mysqli_stmt_bind_param($stmt, "s", $email);
  mysqli_stmt_execute($stmt);
  $result = mysqli_stmt_get_result($stmt);
  $user = mysqli_fetch_array($result, MYSQLI_ASSOC);

  if ($user) {
    if ($password === $user["password"]) {  // Consider using hashing later
      $_SESSION["user"] = $user["email"];
      header("Location: requestaqi.php");
      exit();
    } else {
      $login_error = "Password does not match.";
    }
  } else {
    $login_error = "Email does not exist.";
  }
}
?>



<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="UTF-8" />
  <meta name="viewport" content="width=device-width, initial-scale=1.0" />
  <title>Form Layout</title>
  <link rel="stylesheet" href="style.css" />
</head>

<body>

  <header>
    <div class="header-left">
      <button class="btn-circle"></button>
      <p>AR COMPANY</p>
    </div>
    <div class="header-right">
      <a href="22-47371-2.html" target="_blank">
        <button>22-47371-2</button>
      </a>
      <a href="22-47848-2.html" target="_blank">
        <button>22-47848-2</button>
      </a>
    </div>
  </header>


  <main>
    <section class="container">


      <div class="box-container">
        <!-- Left Column (Contains Two Boxes) -->
        <div class="box-container-left">
          <div class="box-container-left-top">
            <form action="process.php" method="post" class="form-example" onsubmit="return val(event)">

              <div>
                <label for="name">Fullname:</label>
                <input type="text" name="name" id="name" required />
              </div>

              <div>
                <label for="email">Email:</label>
                <input type="text" name="email" id="email" required />
              </div>

              <div>
                <label for="password">Password:</label>
                <input type="password" name="password" id="password" required />
              </div>

              <div>
                <label for="confirm_password">Confirm Password:</label>
                <input type="password" name="confirm_password" id="confirm_password" required />
              </div>

              <div>
                <label for="dob">Date of Birth:</label>
                <input type="date" name="dob" id="dob" required />
              </div>

              <div>
                <label for="country">Country:</label>
                <input type="text" name="country" id="country" required />
              </div>

              <div class="color-picker">
                <label for="bg-color">Color:</label>
                <input type="color" id="bg-color" name="bg_color" />
              </div>

              <div class="gender">
                <label>Gender:</label>
                <input type="radio" id="male" name="gender" value="male" required />
                <label for="male">Male</label>

                <input type="radio" id="female" name="gender" value="female" required />
                <label for="female">Female</label>
              </div>

              <div class="checkbox-container">
                <input type="checkbox" id="scales" name="scales" checked />
                <label for="scales">Read all terms and conditions</label>

                <input type="checkbox" id="horns" name="horns" />
                <label for="horns">Allow cookies</label>
              </div>

              <div>
                <button type="submit">Submit</button>
              </div>
            </form>
          </div>

          <div class="box-container-left-bottom">
            <h3>Login Panel</h3>

            <?php if (isset($login_error)): ?>
              <div class="alert alert-danger"><?php echo $login_error; ?></div>
            <?php endif; ?>

            <form action="index.php" method="post">
              <div>
                <label for="login_email">Email:</label>
                <input type="text" name="email" id="login_email" required />
              </div>
              <div>
                <label for="login_password">Password:</label>
                <input type="password" name="password" id="login_password" required />
              </div>
              <div>
                <button type="submit" name="login">Login</button>
              </div>
            </form>

            <p>Not registered yet? <a href="index.php">Register Here</a></p>
          </div>



          
      </div>

      <!-- Right Box -->
          <div class="box-container-right">
            <table>
              <thead>
                <tr>
                  <th>ROW</th>
                  <th>Col</th>
                </tr>
              </thead>
              <tbody>
                <tr>
                  <td>Row 1</td>
                  <td>Col 1</td>
                </tr>
                <tr>
                  <td>Row 2</td>
                  <td>Col 2</td>
                </tr>
                <tr>
                  <td>Row 3</td>
                  <td>Col 3</td>
                </tr>
                <tr>
                  <td>Row 4</td>
                  <td>Col 4</td>
                </tr>
                <tr>
                  <td>Row 5</td>
                  <td>Col 5</td>
                </tr>
                <tr>
                  <td>Row 6</td>
                  <td>Col 6</td>
                </tr>
                <tr>
                  <td>Row 7</td>
                  <td>Col 7</td>
                </tr>
                <tr>
                  <td>Row 8</td>
                  <td>Col 8</td>
                </tr>
                <tr>
                  <td>Row 9</td>
                  <td>Col 9</td>
                </tr>
                <tr>
                  <td>Row 10</td>
                  <td>Col 10</td>
                </tr>
              </tbody>
            </table>

          </div>
    </section>

  </main>

  <script>
    function val(event) {
      const name = document.getElementById("name").value.trim();
      const email = document.getElementById("email").value.trim();
      const password = document.getElementById("password").value;
      const confirmPassword = document.getElementById("confirm_password").value;
      const dob = document.getElementById("dob").value;
      const country = document.getElementById("country").value.trim();
      const color = document.getElementById("bg-color").value;
      const genderInput = document.querySelector('input[name="gender"]:checked');

      const nameRegex = /^[A-Za-z\s]+$/;
      const emailRegex = /^[^\s@]+@[^\s@]+\.[^\s@]+$/;

      if (!nameRegex.test(name)) {
        alert("Name must contain only letters and spaces.");
        event.preventDefault();
        return false;
      }

      if (!emailRegex.test(email)) {
        alert("Please enter a valid email address.");
        event.preventDefault();
        return false;
      }

      if (password.length < 8) {
        alert("Password must be at least 8 characters long.");
        event.preventDefault();
        return false;
      }

      if (/\s/.test(password)) {
        alert("Password must not contain spaces.");
        event.preventDefault();
        return false;
      }

      if (password !== confirmPassword) {
        alert("Passwords do not match.");
        event.preventDefault();
        return false;
      }

      if (dob) {
        const dobDate = new Date(dob);
        const today = new Date();
        let age = today.getFullYear() - dobDate.getFullYear();
        const m = today.getMonth() - dobDate.getMonth();
        if (m < 0 || (m === 0 && today.getDate() < dobDate.getDate())) {
          age--;
        }
        if (age < 18) {
          alert("You must be at least 18 years old.");
          event.preventDefault();
          return false;
        }
      }

      if (country === "") {
        alert("Country is required.");
        event.preventDefault();
        return false;
      }

      if (!genderInput) {
        alert("Please select your gender.");
        event.preventDefault();
        return false;
      }

      // Optional: Confirm before submission
      alert("Form Submitted Successfully!");
      return true;
    }

  </script>
</body>

</html>