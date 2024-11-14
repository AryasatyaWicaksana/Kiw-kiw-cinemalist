<!DOCTYPE html>
<html lang="en" style="scroll-padding-top: 50px; height: 50px;">
<head>
    <meta charset="UTF-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1.0" />
    <title>Landing Page</title>
    <link href="Bootstrap/css/bootstrap.min.css" rel="stylesheet"/>
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.11.3/font/bootstrap-icons.min.css">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="Web/Assets/img/Logo kiw-kiw.png">
</head>

<body data-bs-smooth-scroll="true">
    <header class="navbar navbar-expand-lg bg-dark py-2 sticky-top" style="box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5)">
        <div class="container-fluid">
            <div class="d-flex align-items-center">
                <img class="me-2" src="Web/Assets/img/Logo kiw-kiw.png" alt="Kiw Kiw Logo" style="height: 50px; margin-left: 20px;">
                <h1 class="navbar-brand text-white h4 mb-0">Kiw Kiw Cinema List</h1>
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
                        <a class="login nav-link mx-3 text-center" href="../Kiw-kiw-cinemalist/Web/Register-Login-Page/login.php">Login</a>
                    </li>
                </ul>
            </nav>
        </div>
    </header>
    
    <main class="py-5 mt-1 mb-2">
    <section class="mb-5">
        <div id="carouselExampleSlidesOnly" class="carousel slide container bg-dark rounded-4" data-bs-ride="carousel" 
            style="box-shadow: 0 15px 25px rgba(0, 0, 0, 0.5); max-height: 100vh;">
            <div class="carousel-inner">
                <div class="carousel-item active text-center" style="height: 85vh;">
                    <img src="Web/Assets/img/blank-profile-picture.png" class="object-fit-cover rounded-4 d-block w-100 h-100 img-fluid" alt="Slide 1">
                </div>
                <div class="carousel-item text-center" style="height: 85vh;">
                    <img src="Web/Assets/img/blank-profile-picture.png" class="object-fit-cover rounded-4 d-block w-100 h-100 img-fluid" alt="Slide 2">
                </div>
                <div class="carousel-item text-center" style="height: 85vh;">
                    <img src="Web/Assets/img/blank-profile-picture.png" class="object-fit-cover rounded-4 d-block w-100 h-100 img-fluid" alt="Slide 3">
                </div>
            </div>
        </div>
    </section>
        
    <section id="about" class="about-us container text-bg-dark p-5 rounded-4" style="box-shadow: 10px 20px 25px rgba(0, 0, 0, 0.7);">
        <h2 class="text-center mb-4">Kiw Kiw Cinema List</h2>
        <p>
            Kiw Kiw Cinema List is an interactive movie database platform designed for film enthusiasts to explore,
            curate, and manage their own movie lists. Users can easily create personalized watchlists, adding movies
            they've watched or plan to see. The website offers a user-friendly interface where visitors can search
            for a wide variety of films, view detailed information like genres, release dates, and cast, and organize
            their selections in a way that suits their preferences.
        </p>
        <p>
            In addition to building custom movie lists, users can browse an extensive database of movies available
            on the platform. Whether looking for popular titles, recent releases, or hidden gems, Kiw Kiw Cinema
            List provides a comprehensive and streamlined experience for discovering new movies. The platform is a
            perfect companion for anyone who loves to keep track of their viewing history and find their next
            favorite film.
        </p>
    </section>
    </main>

    <footer class="d-flex justify-content-between align-items-center text-bg-dark" style="box-shadow: 0 -15px 25px rgba(0, 0, 0, 0.5);">
        <p class="fs-5 pt-3 pe-5 mx-5">
            Copyright <br>
            Kiw Kiw Cinema List <br>
            No.9 Alumni St., Medan City, Noth Sumatera, Indonesia
        </p>
        <p class="fs-5 pt-3 pe-5 me-5">
            Follow Us On <br>
            <a class="icon bi bi-github text-white" href="https://github.com/Aryasatyawicaksana"> Github</a> <br>
            <a class="icon bi bi-instagram text-white" href="https://www.instagram.com/"> Instagram</a>
        </p>
    </footer>

    <script src="Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>