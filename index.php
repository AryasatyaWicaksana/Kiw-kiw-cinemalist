<?php
    session_start();

    if (isset($_SESSION["is_login"])) {
        header('location: Web/Dashboard/dashboard.php');
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en" style="scroll-padding-top: 50px; height: 50px;">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Landing Page</title>
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Web/Assets/img/icon fix kiw kiw.png">
</head>

<body data-bs-smooth-scroll="true">
    <header class="navbar navbar-expand-lg bg-dark py-2 sticky-top" style="box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5)">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img class="me-2" src="Web/Assets/img/logo navbar fix kiw kiw.png" alt="Kiw Kiw Logo">
            </div>
            <button class="navbar-toggler bg-white" type="button" data-bs-toggle="collapse" data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <nav class="collapse navbar-collapse" id="navbarSupportedContent">
                <ul class="navbar-nav ms-auto">
                    <li class="nav-item p-1">
                        <a class="nav-element nav-link text-white mx-3" href="#">Home</a>
                    </li>
                    <li class="nav-item p-1">
                        <a class="nav-element nav-link text-white" href="#about">About Us</a>
                    </li>
                    <li class="nav-item p-1">
                        <a class="login nav-link mx-3 text-center" href="Web/Register-Login-Page/login.php">Login</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="py-5 mt-1 mb-2">
    <section class="mb-5">
        <div id="carouselExampleSlidesOnly" class="carousel slide container bg-dark rounded-4" data-bs-ride="carousel" 
            style="box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5);">
            <div class="carousel-inner">
                <div class="carousel-item active" style="max-height: 85vh;">
                    <img src="Web/Assets/img/venom-let-there-be-carnage-4k-wallpaper-3840x2160-uhdpaper.com-203.1_a.jpg" class="object-fit-cover rounded-4 d-block img-fluid" alt="Slide 1">
                </div>
                <div class="carousel-item" style="max-height: 85vh;">
                    <img src="Web/Assets/img/terrifier-3-art-the-clown-4k-wallpaper-uhdpaper.com-399@2@b.jpg" class="object-fit-cover rounded-4 d-block img-fluid" alt="Slide 2">
                </div>
                <div class="carousel-item" style="max-height: 85vh;">
                    <img src="Web/Assets/img/gladiator-2-lucius-paul-mescal-4k-wallpaper-uhdpaper.com-347@3@b.jpg" class="object-fit-cover rounded-4 d-block img-fluid" alt="Slide 3">
                </div>
            </div>
        </div>
    </section>

    <section id="movieList" class="container text-bg-dark rounded-4 mb-5">
        <h3 class="ms-4 mt-5">Trending Movies</h3>
        <div id="newList" class="container"></div>
    </section>

    <section id="about" class="about-us container text-bg-dark p-5 rounded-4" style="box-shadow: 10px 20px 25px rgba(0, 0, 0, 0.7);">
        <h2 class="text-center mb-4">About Us</h2>
        <p>
            Kiw Kiw Cinemalist is an interactive movie database platform designed for film enthusiasts to explore,
            curate, and manage their own movie lists. Users can easily create personalized watchlists, adding movies
            they've watched or plan to see. The website offers a user-friendly interface where visitors can search
            for a wide variety of films, view detailed information like genres, release dates, and cast, and organize
            their selections in a way that suits their preferences.
        </p>
        <p>
            In addition to building custom movie lists, users can browse an extensive database of movies available
            on the platform. Whether looking for popular titles, recent releases, or hidden gems, Kiw Kiw Cinema
            List provides a comprehensive and streamlined experience for discovering new movies. Kiw Kiw Cinemalist is a
            perfect companion for anyone who loves to keep track of their viewing history and find their next
            favorite film.
        </p>
    </section>
    </main>

    <footer class="bg-dark text-white pt-2 pb-1 mt-5 shadow">
        <div class="container-fluid text-center">
            <div class="row mt-2">
                <div class="col-md-4">
                    <h3>Location</h3>
                    <p class="location">Medan City, North Sumatera, Indonesia</p>
                </div>
                <div class="col-md-4 mb-1">
                    <h3>Follow Us</h3>
                    <a href="https://github.com/Aryasatyawicaksana" class="text-white me-3"><i class="icon bi bi-github"></i></a>
                    <a href="https://www.instagram.com/" class="text-white"><i class="icon bi bi-instagram"></i></a>
                </div>
                <div class="col-md-4">
                    <h3>Contact</h3>
                    <p class="contact-us">kiwkiwcinemalist@gmail.com</p>
                </div>
            </div>
            <hr class="my-3">
            <p>&copy; 2024 Kiw Kiw Cinema List. All Rights Reserved.</p>
        </div>
    </footer>

    <script src="Web/service/api.js"></script>
    <script src="script.js"></script>
    <script src="Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>