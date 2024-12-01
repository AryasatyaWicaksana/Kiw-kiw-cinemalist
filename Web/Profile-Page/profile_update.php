<?php
require_once '../service/database.php';
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (!isset($_POST["profilePicture"]) || empty($_POST["profilePicture"])) {
        echo json_encode(['message' => 'Profile picture is required']);
        http_response_code(400);
        exit();
    }

    $profilePicture = $_POST["profilePicture"];

    $query = "UPDATE user_list SET profile_picture = $1 WHERE id = $2";
    $result = pg_query_params($dbconn, $query, [$profilePicture, $_SESSION['user_id']]);

    if ($result) {
        $_SESSION['user_profile'] = $profilePicture;
        echo json_encode(['message' => 'Profile picture updated successfully']);
        exit();
    } else {
        echo json_encode(['message' => 'Failed to update profile picture']);
        http_response_code(500);
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Profile</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container my-5 position-relative">
        <a href="edit_profile.php" class="back-button position-absolute top-0 end-0 mt-2 me-3">X</a>
        <h1 class="text-center mb-4">Profile</h1>

        <div class="text-center mb-4">
        <img id="current-profile" src="../Assets/profile-img/<?= $_SESSION['user_profile'] ?>" alt="Profile Photo" class="img-fluid rounded-circle" style="max-width: 150px;">
        </div>
        <div class="text-center mb-4">
            <label class="fs-4">Choose Profile Picture:</label>
        </div>

        <div class="images-container d-flex justify-content-center flex-wrap gap-3 my-5">
            <img src="../Assets/profile-img/blank-profile-picture.png" alt="Blank" data-image="blank-profile-picture.png" class="profile-img-thumbnail">
            <img src="../Assets/profile-img/man.png" alt="Man" data-image="man.png" class="profile-img-thumbnail">
            <img src="../Assets/profile-img/woman.png" alt="Woman" data-image="woman.png" class="profile-img-thumbnail">
        </div>
    </div>

    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>