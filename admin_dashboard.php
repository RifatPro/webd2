<?php
session_start();
require_once "database.php";

if (!isset($_SESSION["admin"])) {
    header("Location: index.php");
    exit();
}

// Handle ADD
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['add'])) {
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $country = $_POST['country'];
    $gender = $_POST['gender'];

    $sql = "INSERT INTO userinfo (name, email, password, dob, country, gender) VALUES (?, ?, ?, ?, ?, ?)";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssss", $name, $email, $password, $dob, $country, $gender);
    mysqli_stmt_execute($stmt);

    // 🚀 Redirect to prevent resubmission
    header("Location: admin_dashboard.php");
    exit();
}

// Handle UPDATE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['update'])) {
    $id = $_POST['id'];
    $name = $_POST['name'];
    $email = $_POST['email'];
    $password = $_POST['password'];
    $dob = $_POST['dob'];
    $country = $_POST['country'];
    $gender = $_POST['gender'];

    $sql = "UPDATE userinfo SET name=?, email=?, password=?, dob=?, country=?, gender=? WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "ssssssi", $name, $email, $password, $dob, $country, $gender, $id);
    mysqli_stmt_execute($stmt);
}

// Handle DELETE
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['delete'])) {
    $id = $_POST['id'];
    $sql = "DELETE FROM userinfo WHERE id=?";
    $stmt = mysqli_prepare($conn, $sql);
    mysqli_stmt_bind_param($stmt, "i", $id);
    mysqli_stmt_execute($stmt);
}

// Fetch users
$result = mysqli_query($conn, "SELECT * FROM userinfo");
?>

<!DOCTYPE html>
<html>

<head>
    <title>Admin Dashboard</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            padding: 40px;
        }

        table {
            border-collapse: collapse;
            width: 100%;
            margin-top: 20px;
        }
        .header-bar {
  display: flex;
  justify-content: space-between;
  align-items: center;
  margin-bottom: 20px;
}

.logout-button {
  padding: 6px 12px;
  background-color: #d9534f;
  color: white;
  border: none;
  border-radius: 4px;
  cursor: pointer;
}


        th,
        td {
            padding: 10px;
            border: 1px solid #ccc;
            text-align: center;
        }

        th {
            background-color: #f2f2f2;
        }

        input,
        select {
            width: 100%;
            padding: 4px;
        }

        .button {
            padding: 6px 12px;
            background: #007bff;
            color: white;
            border: none;
            cursor: pointer;
        }

        .delete {
            background: red;
        }

        .logout {
            float: right;
            background: #555;
        }

        .add-form {
            margin-bottom: 30px;
        }
    </style>
</head>

<body>
    <!-- <a class="button logout" href="logout.php">Logout</a> -->
    <div class="header-bar">
        <h2>Admin Dashboard</h2>
        <form action="logout.php" method="post">
            <button type="submit" class="logout-button">Logout</button>
        </form>
    </div>

    <!-- ADD USER FORM -->
    <div class="add-form">

        <form method="POST">
            <input type="text" id="searchInput" placeholder="Search users..."
                style="width: 300px; padding: 8px; margin-top: 20px; margin-bottom: 10px;">

            <h3>Add New User</h3>
            <table>
                <tr>
                    <td><input type="text" name="name" placeholder="Fullname" required></td>
                    <td><input type="email" name="email" placeholder="Email" required></td>
                    <td><input type="text" name="password" placeholder="Password" required></td>
                    <td><input type="date" name="dob" required></td>
                    <td><input type="text" name="country" placeholder="Country" required></td>
                    <td>
                        <select name="gender" required>
                            <option value="">Gender</option>
                            <option value="male">Male</option>
                            <option value="female">Female</option>
                        </select>
                    </td>
                    <td colspan="2"><button class="button" name="add" type="submit">Add User</button></td>
                </tr>
            </table>
        </form>
    </div>

    <!-- USER LIST TABLE -->
    <table>
        <thead>
            <tr>
                <th>ID</th>
                <th>Fullname</th>
                <th>Email</th>
                <th>Password</th>
                <th>DOB</th>
                <th>Country</th>
                <th>Gender</th>
                <th>Actions</th>
            </tr>
        </thead>
        <tbody>
            <?php while ($row = mysqli_fetch_assoc($result)): ?>
                <tr>
                    <form method="POST">
                        <td><?= $row['id'] ?><input type="hidden" name="id" value="<?= $row['id'] ?>"></td>
                        <td><input type="text" name="name" value="<?= htmlspecialchars($row['name']) ?>"></td>
                        <td><input type="email" name="email" value="<?= htmlspecialchars($row['email']) ?>"></td>
                        <td><input type="text" name="password" value="<?= htmlspecialchars($row['password']) ?>"></td>
                        <td><input type="date" name="dob" value="<?= $row['dob'] ?>"></td>
                        <td><input type="text" name="country" value="<?= htmlspecialchars($row['country']) ?>"></td>
                        <td>
                            <select name="gender">
                                <option value="male" <?= $row['gender'] === 'male' ? 'selected' : '' ?>>Male</option>
                                <option value="female" <?= $row['gender'] === 'female' ? 'selected' : '' ?>>Female</option>
                            </select>
                        </td>
                        <td>
                            <button class="button" name="update" type="submit">Save</button>
                            <button class="button delete" name="delete" type="submit"
                                onclick="return confirm('Are you sure you want to delete this user?')">Delete</button>
                        </td>
                    </form>
                </tr>
            <?php endwhile; ?>
        </tbody>
    </table>

    <script>
        document.getElementById("searchInput").addEventListener("input", function () {
            let filter = this.value.toLowerCase();
            let rows = document.querySelectorAll("table tbody tr");

            rows.forEach(function (row) {
                let matchFound = false;

                // Check all cells in the row
                row.querySelectorAll("td").forEach(function (cell) {
                    const input = cell.querySelector("input, select");
                    let text = "";

                    if (input) {
                        text = input.value.toLowerCase();
                    } else {
                        text = cell.textContent.toLowerCase();
                    }

                    if (text.includes(filter)) {
                        matchFound = true;
                    }
                });

                row.style.display = matchFound ? "" : "none";
            });
        });
    </script>


</body>

</html>