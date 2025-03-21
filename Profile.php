<?php
session_start();
include("database.php");

// Logout logic
if (isset($_GET['logout']) && $_GET['logout'] == 1) {
    // Unset user-specific session variables
    unset($_SESSION['id']);
    unset($_SESSION['email']);

    // If there are no other session variables, destroy the session
    if (empty($_SESSION)) {
        session_destroy();
    }

    // Redirect to the login page
    header("Location: login.php");
    exit();
}

// Check if the user is logged in
if (!isset($_SESSION['email'])) {
    // Redirect to the login page if no user is logged in
    header("Location: login.php");
    exit();
}

$loggedInEmail = $_SESSION['email'];

// Initialize variables
$username = "";
$email = "";

// Fetch user data from the database
$query = "SELECT username, email FROM users WHERE email = ?";
$stmt = $conn->prepare($query);
$stmt->bind_param("s", $loggedInEmail);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows > 0) {
    $user = $result->fetch_assoc();
    $username = $user['username'];
    $email = $user['email'];
}

$stmt->close();
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>RFSC - Website</title>
    <link rel="stylesheet" href="profile.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.4.0/css/all.min.css">
</head>
<body>
    <header>
        <nav>
            <a href="index.php" class="logo">RF<span>SC</span></a>
            <div class="nav-links">
                <a href="index.php">Home</a>
                <a href="shop.php">Shop</a>
                <a href="about.php">About</a>
          </div>

            <div class="right-nav">
                <a href="#"><i class="fa-solid fa-magnifying-glass"></i></a>
                <a href="#"><i class="fa-solid fa-cart-shopping"></i></a>
                <a href="Profile.php"><i class="fa-solid fa-user-alt"></i></a>
                <a href="Profile.php?logout=1"><i class="fa fa-sign-out"></i></a>
            </div>
        </nav>
    </header>

    <main>
        <div class="profile-container">
            <h2>User Profile</h2>
            <div class="user-box">
                <p><strong>Username:</strong> <?php echo htmlspecialchars($username); ?></p>
                <p><strong>Email:</strong> <?php echo htmlspecialchars($email); ?></p>
            </div>
        </div>
    </main>

    <footer>
        <div class="container">
            <div class="big-rows">
                <h6 class="logo">RF<span>SC</span></h6>
                <p>Our Purpose Is To Sustainable<br>Make The Pleasure And Benefits Of Fashion Accessible.
            </div>

            <div class="row">
                <h2 class="tittle">Service</h2>
                <div class="links">
                    <a href="#">09298577194</a>
                    <a href="#">Customerservice17@gmail.com</a>
                </div>
            </div>

            <div class="row">
                <h2 class="tittle">Useful Links</h2>
                <div class="links">
                    <a href="index.html">Home</a>
                    <a href="about.html">About</a>
                    <a href="shop.html">Products</a>
                </div>
            </div>

            <div class="row">
                <h2 class="tittle">Store Policy</h2>
                <div class="links">
                    <p>Our goal is to provide everyone with classic, fashionable, and cozy clothing.
                </div>
            </div>
        </div>
    </footer>
</body>
</html>