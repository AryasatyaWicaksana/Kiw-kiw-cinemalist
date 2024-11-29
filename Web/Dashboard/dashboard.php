<?php
require_once '../service/database.php';
session_start();

if(isset($_SESSION["is_login"]) == false) {
        header("location: ../../index.php");
        exit();
}

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST["action"])) {
    header('Content-Type: application/json');
    try {
        if(isset($_POST["id"], $_POST["title"], $_POST["year"], $_POST["poster_path"], $_POST["rating"], $_POST["genre"], $_POST["overview"])){
            $user_id = $_SESSION["user_id"];
            $movie_id = $_POST["id"];
            $title = $_POST["title"];
            $year = $_POST["year"];
            $poster_path = $_POST["poster_path"];
            $rating = $_POST["rating"];
            $genre = $_POST["genre"];
            $overview = $_POST["overview"];
            $validActions = ["like_movie", "complete_movie", "plan_to_watch_movie"];
            if (in_array($_POST["action"], $validActions)) {
                $column = match ($_POST["action"]) {
                    "like_movie" => "like_status",
                    "complete_movie" => "completed_status",
                    "plan_to_watch_movie" => "plan_to_watch_status"
                };
            }

            $queryList = "SELECT movie_id FROM movie_list WHERE movie_id = $1";
            $listMovie = pg_query_params($dbconn, $queryList, [$movie_id]);
            if (pg_num_rows($listMovie) === 0) {
                $insertQuery = "INSERT INTO movie_list (movie_id, title, movie_year, poster_path, rating, genre, overview, created_at) 
                                VALUES ($1, $2, $3, $4, $5, $6, $7, NOW())";
                $insertResult = pg_query_params($dbconn, $insertQuery, [$movie_id, $title, $year, $poster_path, $rating, $genre, $overview]);
                
                if ($insertResult) {
                    echo json_encode(["success" => true, "message" => "saved successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => pg_last_error($dbconn)]);
                }
            }

            $queryStatus = "SELECT id_user, id_movie FROM movie_status WHERE id_user = $1 AND id_movie = $2";
            $statusMovie = pg_query_params($dbconn, $queryStatus, [$user_id, $movie_id]);
            if (pg_num_rows($statusMovie) > 0) {
                $updateQuery = "UPDATE movie_list SET $column = TRUE WHERE user_id = $1 AND movie_id = $2";
                $updateResult = pg_query_params($dbconn, $updateQuery, [$user_id, $movie_id]);
                if ($updateResult) {
                    echo json_encode(["success" => true, "message" => "updated successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => pg_last_error($dbconn)]);
                }
            } else {
                $insertQuery = "INSERT INTO movie_status (id_user, id_movie, $column) VALUES ($1, $2, TRUE)";
                $insertResult = pg_query_params($dbconn, $insertQuery, [$user_id, $movie_id]);
                if ($insertResult) {
                    echo json_encode(["success" => true, "message" => ucfirst(str_replace("_", " ", $column)) . " saved successfully"]);
                } else {
                    echo json_encode(["success" => false, "message" => pg_last_error($dbconn)]);
                }
            }
        } else {
            echo json_encode(["success" => false, "message" => "Invalid action"]);
        }
    } catch (Exception $e) {
        echo json_encode(["success" => false, "message" => $e->getMessage()]);
    }
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
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
    <title>Kiw Kiw Cinema List</title>
</head>
<body> 
    <header>
        <div class="d-flex align-items-center">
            <img class="me-2" src="../Assets/img/logo navbar fix kiw kiw.png" alt="Kiw Kiw Logo" class="logo-img">
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