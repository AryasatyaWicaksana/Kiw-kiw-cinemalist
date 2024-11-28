<?php
require_once '../service/database.php';
session_start();

if(isset($_SESSION["is_login"]) == false) {
    header("location: ../../index.php");
    exit();
}

// Periksa jika ada data POST untuk menyimpan film
if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"]) && $_POST["action"] === "save_movie") {
    header('Content-Type: application/json'); // Pastikan respons JSON
    try {
        if (isset($_POST["title"], $_POST["poster_path"], $_POST["rating"], $_POST["genre"], $_POST["overview"], $_POST["id"])) {
            // Ambil data dari POST
            $user_id = $_SESSION["user_id"];
            $title = $_POST["title"];
            $poster_path = $_POST["poster_path"];
            $rating = $_POST["rating"];
            $genre = $_POST["genre"];
            $overview = $_POST["overview"];
            $movie_id = $_POST["id"];
            
            // Query untuk menyimpan data
            $query = "INSERT INTO movie_list (user_id, movie_id, title, poster_path, rating, genre, overview, created_at) 
                      VALUES ($1, $2, $3, $4, $5, $6, $7, NOW())";
            $result = pg_query_params($dbconn, $query, [$user_id, $movie_id, $title, $poster_path, $rating, $genre, $overview]);

            // Kirim respons JSON
            if ($result) {
                echo json_encode(["success" => true, "message" => "Movie saved successfully"]);
            } else {
                echo json_encode(["success" => false, "message" => pg_last_error($dbconn)]);
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid input"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
    exit(); // Penting untuk menghentikan eksekusi
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
            <form id="rating-form">
                <select id="rating-select" class="search" style="width: 10.5rem;">
                <option value="" disabled selected>Sort by Rating</option>
                    <option value="0" style="text-align: center;">0</option>
                    <option value="1" style="text-align: center;">1</option>
                    <option value="2" style="text-align: center;">2</option>
                    <option value="3" style="text-align: center;">3</option>
                    <option value="4" style="text-align: center;">4</option>
                    <option value="5" style="text-align: center;">5</option>
                    <option value="6" style="text-align: center;">6</option>
                    <option value="7" style="text-align: center;">7</option>
                    <option value="8" style="text-align: center;">8</option>
                    <option value="9" style="text-align: center;">9</option>
                    <option value="10" style="text-align: center;">10</option>
                </select>
            </form>
            <form id="year-form">
                <input type="number" id="year-input" class="search" placeholder="Enter Year" min="1600" max="2100">
            </form>
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
    <script src="../service/api.js"></script>
    <script src="script.js"></script>
</body>
</html>