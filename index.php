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
?>

<!DOCTYPE html>
    <html lang="en">
    <head>
        <meta charset="UTF-8">
        <meta http-equiv="X-UA-Compatible" content="IE=edge">
        <meta name="viewport" content="width=device-width, initial-scale=1.0">
        <title>RFSC - Website</title>
        <link rel="stylesheet" href="style.css">
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

        <section class="home">
            <div class="container">
                <div class="content">
                    <p>Shop Collection</p>
                    <h1>Find Your Favorite <br> <span>clothing Collection</span></h1>
                    <p class="last-p">caught in the moment!</p>
                    <a href="shop.html" class="btn">Show Collection</a>
                </div>
            </div>
        </section>

        <section class="banner">
            <div class="boxs container">
                <div class="box">
                    <img src="img/b-1.jpg" alt="">
                    <div class="text">
                        <span>Your Looks</span>
                        <h5>Must Haves</h5>
                        <a href="shop.html" class="btn">Discover Now</a>
                    </div>
                </div>

                <div class="box">
                    <img src="img/b-2.jpg" alt="">
                    <div class="text">
                        <span>Your Looks</span>
                        <h5>Must Haves</h5>
                        <a href="shop.html" class="btn">Discover Now</a>
                    </div>
                </div>

                <div class="box">
                    <img src="img/b-3.jpg" alt="">
                    <div class="text">
                        <span>Your Looks</span>
                        <h5>Must Haves</h5>
                        <a href="shop.html" class="btn">Discover Now</a>
                    </div>
                </div>
            </div>
        </section>
        
        <section class="products">
            <div class="container">
                <div class="top-sec">
                    <h3>Featured Products</h3>
                </div>

                <div class="items">
                    <div class="item">
                        <img src="img/3.jpg" alt="">
                        <div class="product-desc">
                            <a href="#" class="title-prod">T-Shirt</a>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/8.jpg" alt="">
                        <div class="product-desc">
                            <a href="#" class="title-prod">T-Shirt</a>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/1.jpg" alt="">
                        <div class="product-desc">
                            <a href="#" class="title-prod">T-Shirt</a>
                        </div>
                    </div>

                    <div class="item">
                        <img src="img/5.jpg" alt="">
                        <div class="product-desc">
                            <a href="#" class="title-prod">T-Shirt</a>
                        </div>
                    </div>
                </div>
            </div>
        </section>
        
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
                        <a href="index.php">Home</a>
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