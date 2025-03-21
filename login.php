<?php
session_start();
include("database.php");

// Redirect if already logged in
if (isset($_SESSION['admin_id'])) {
    header("Location: dashboard.php");
    exit();
}

if (isset($_SESSION['id'])) {
    header("Location: index.php");
    exit();
}

// Database connection
$conn = new mysqli($host, $dbusername, $dbpassword, $dbname);
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$error = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    if (!empty($_POST['identifier']) && !empty($_POST['password'])) {
        $identifier = $_POST['identifier']; // Can be email or username
        $password = $_POST['password'];

        // Check admin credentials
        $stmt = $conn->prepare("SELECT id, admin_email, admin_password FROM admins WHERE admin_email = ?");
        $stmt->bind_param("s", $identifier);
        $stmt->execute();
        $stmt->store_result();
        $stmt->bind_result($id, $dbEmail, $dbPassword);

        if ($stmt->num_rows > 0) {
            $stmt->fetch();
            if (password_verify($password, $dbPassword)) {
                $_SESSION['admin_id'] = $id;
                $_SESSION['admin_email'] = $dbEmail;
                header("Location: dashboard.php");
                exit();
            } else {
                $error = "Invalid password!";
            }
        } else {
            $stmt->close();
            
            // Check user credentials using email or username
            $stmt = $conn->prepare("SELECT id, username, email, password FROM users WHERE email = ? OR username = ?");
            $stmt->bind_param("ss", $identifier, $identifier);
            $stmt->execute();
            $stmt->store_result();
            $stmt->bind_result($id, $dbUsername, $dbEmail, $dbPassword);

            if ($stmt->num_rows > 0) {
                $stmt->fetch();
                if (password_verify($password, $dbPassword)) {
                    $_SESSION['id'] = $id;
                    $_SESSION['username'] = $dbUsername;
                    $_SESSION['email'] = $dbEmail;
                    header("Location: index.php");
                    exit();
                } else {
                    $error = "Invalid password!";
                }
            } else {
                $error = "Invalid username or email!";
            }
        }
        $stmt->close();
    } else {
        $error = "Username/Email and password are required.";
    }
}
$conn->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login</title>
    <link rel="stylesheet" href="login.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.2/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="img/fashion.webp" alt="Welcome Image">
        </div>
        <div class="right-panel">
            <h2>Sign In</h2>
            <form action="login.php" method="POST">
                <?php if (!empty($error)) echo "<p class='error'>$error</p>"; ?>
                
                <div class="input-group">
                    <i class="fa-solid fa-user"></i>
                    <input type="text" name="identifier" placeholder="Username or Email" required>
                </div>

                <div class="input-group">
                    <i class="fa-solid fa-lock"></i>
                    <input type="password" name="password" placeholder="Password" required>
                </div>

                <div class="options">
                    <label><input type="checkbox"> Remember me</label>
                    <a href="#">Forgot password?</a>
                </div>

                <button type="submit">Sign In</button>

                <p class="register">New here? <a href="register.php">Create an Account</a></p>
            </form>
        </div>
    </div>
</body>
</html>