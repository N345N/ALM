<?php
session_start();
include("database.php");

$usernameError = $passError = $emailError = $captchaError = $success = "";

if (isset($_SESSION['email'])) {
    header("Location: index.php");
    exit();
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    // Gets the input from the user
    $username = filter_input(INPUT_POST, "username", FILTER_SANITIZE_SPECIAL_CHARS);
    $email = filter_input(INPUT_POST, "email", FILTER_SANITIZE_EMAIL);
    $password = filter_input(INPUT_POST, "password", FILTER_SANITIZE_SPECIAL_CHARS);
    $repeatPassword = filter_input(INPUT_POST, "repeatPassword", FILTER_SANITIZE_SPECIAL_CHARS);

    // Validate reCAPTCHA
    if (isset($_POST['g-recaptcha-response'])) {
        $recaptchaResponse = $_POST['g-recaptcha-response'];
        $recaptchaSecretKey = '6LcJrfQpAAAAACSYXiDEyA_-I-H-yMmobvSC6__z'; // Replace with your reCAPTCHA secret key
        $recaptchaUrl = 'https://www.google.com/recaptcha/api/siteverify?secret=' . $recaptchaSecretKey . '&response=' . $recaptchaResponse;
        $recaptchaResponseData = json_decode(file_get_contents($recaptchaUrl));
        
        if (!$recaptchaResponseData->success) {
            $captchaError = "Please complete the reCAPTCHA";
        }
    } else {
        $captchaError = "Please complete the reCAPTCHA";
    }

    if (empty($username)) {
        $usernameError = "Please enter a Username <br>";
    } elseif (!preg_match("/^[a-zA-Z0-9]+$/", $username)) {
        $usernameError = "Username should only contain letters and numbers<br>";
    }

    if (empty($email)) {
        $emailError = "Please enter an email<br>";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $emailError = "Invalid email address<br>";
    }

    if (empty($password)) {
        $passError = "Please enter a password<br>";
    } elseif ($password !== $repeatPassword) {
        $passError = "Passwords do not match<br>";
    }
    
    if (empty($usernameError) && empty($emailError) && empty($passError) && empty($captchaError)) {
        // Check if Email already exists
        $checkQuery = "SELECT * FROM users WHERE email = ?";
        if ($stmt = mysqli_prepare($conn, $checkQuery)) {
            mysqli_stmt_bind_param($stmt, "s", $email);
            mysqli_stmt_execute($stmt);
            mysqli_stmt_store_result($stmt);

            if (mysqli_stmt_num_rows($stmt) > 0) {
                // Email already exists
                $emailError = "Email already exists<br>";
            } else {
                // Insert the new user to the users table
                $insertQuery = "INSERT INTO users (username, email, password) VALUES (?, ?, ?)";
                if ($stmt = mysqli_prepare($conn, $insertQuery)) {
                    $hash = password_hash($password, PASSWORD_DEFAULT);
                    mysqli_stmt_bind_param($stmt, "sss", $username, $email, $hash);
                    mysqli_stmt_execute($stmt);

                    // Redirect to login page after successful registration
                    header("Location: login.php");
                    exit();
                } else {
                    echo "Error: Could not prepare insert query: " . mysqli_error($conn);
                }
            }
            mysqli_stmt_close($stmt);
        } else {
            echo "Error: Could not prepare select query: " . mysqli_error($conn);
        }
    }
}
mysqli_close($conn);
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Register</title>
    <link rel="stylesheet" href="register.css">
    <script src="https://www.google.com/recaptcha/api.js" async defer></script>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css">
</head>
<body>
    <div class="container">
        <div class="left-panel">
            <img src="img/fashion.webp" alt="Welcome Image">
        </div>
        <div class="right-panel">
            <h2>Register</h2>
            <form action="register.php" method="POST">
                <div class="input-group">
                    <i class="fas fa-user"></i>
                    <input type="text" id="username" name="username" placeholder="Username" required>
                </div>
                <span style="color: red;"><?php echo $usernameError ?></span>

                <div class="input-group">
                    <i class="fas fa-envelope"></i>
                    <input type="email" id="email" name="email" placeholder="Email" required>
                </div>
                <span style="color: red;"><?php echo $emailError ?></span>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="password" name="password" placeholder="Password" required>
                </div>
                <span style="color: red;"><?php echo $passError ?></span>

                <div class="input-group">
                    <i class="fas fa-lock"></i>
                    <input type="password" id="repeatPassword" name="repeatPassword" placeholder="Repeat Password" required>
                </div>

                <div class="g-recaptcha" data-sitekey="6LcJrfQpAAAAABN0IZY2U0waYXokgh2V9z1xLdhM"></div>
                <span style="color: red;"><?php echo $captchaError ?></span>
                <button type="submit">Register</button>
                <span style="color: green;"><?php echo $success ?></span>
                <p class="register">Already have an account? <a href="login.php">Sign In</a></p>
            </form>
        </div>
    </div>
</body>
</html>