<?php
    session_start();

    if(isset($_SESSION["is_login"]) == false) {
        header("location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="style.css">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="icon" href="../Assets/img/Logo kiw-kiw.png">
    <title>Kiw Kiw Cinema List</title>
</head>
<body> 
    <header>
        <div class="d-flex align-items-center">
            <img class="me-2" src="../Assets/img/Logo kiw-kiw.png" alt="Kiw Kiw Logo" class="logo-img">
            <h1 class="title">Kiw Kiw Cinema List</h1>
        </div>
        <div class="header-right">
            <form id="form">
                <input type="text" id="search" class="search" placeholder="Search...">
            </form>
            <a href="../Profile Page/profile.php" class="profile-btn">
                <img src="../Assets/img/blank-profile-picture.png" alt="Profile Picture" id="profile-picture">
            </a>
        </div>
    </header>
    
    <div id="tags"></div>
    <div id="myNav" class="overlay">
        <a href="javascript:void(0)" class="closebtn" onclick="closeNav()">&times;</a>
        <div class="overlay-content" id="overlay-content"></div>
        <a href="javascript:void(0)" class="arrow left-arrow" id="left-arrow">&#8656;</a> 
        <a href="javascript:void(0)" class="arrow right-arrow" id="right-arrow" >&#8658;</a>
    </div>

    <main id="main"></main>
    <div class="pagination">
        <div class="page" id="prev"></div>
        <div class="current" id="current"></div>
        <div class="page" id="next"></div>
    </div>
    <script src="script.js"></script>
</body>
</html>