<?php
    session_start();

    if(isset($_SESSION["is_login"]) == false) {
        header("location: ../../index.php");
        exit();
    }

    if (isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet" >
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/Logo kiw-kiw.png">
</head>
<body>
    <div class="container position-relative">
        <a href="../Dashboard/dashboard.php" class="back-button position-absolute top-0 end-0 mt-2 me-3" style="font-size: 25px;">X</a>
        <h1 class="text-center mt-3">Profile</h1>
        <div class="profile-photo text-center mb-3">
            <img src="https://cdn.pixabay.com/photo/2015/10/05/22/37/blank-profile-picture-973460_1280.png" alt="Profile Photo">
        </div>
        <div class="profile-item">
            <label>Name:</label>
            <span class="user-name"><?= $_SESSION["username"] ?></span>
        </div>
        <div class="profile-item">
            <label>My Collections</label>
            <div class="row mt-3">
                <!-- Product 1 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 1">
                        <h5 class="mt-2">Title 1</h5>
                        <p>Know More</p>
                    </div>
                </div>
                <!-- Product 2 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 2">
                        <h5 class="mt-2">Title 2</h5>
                        <p>Know More</p>
                    </div>
                </div>
                <!-- Product 3 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 3">
                        <h5 class="mt-2">Title 3</h5>
                        <p>Know More</p>
                    </div>
                </div>
                <!-- Product 4 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 4">
                        <h5 class="mt-2">Title 4</h5>
                        <p>Know More</p>
                    </div>
                </div>
                <!-- Product 5 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 5">
                        <h5 class="mt-2">Title 5</h5>
                        <p>Know More</p>
                    </div>
                </div>
                <!-- Product 6 -->
                <div class="col-md-4 mb-4">
                    <div class="product-card">
                        <img src="https://via.placeholder.com/300x200" alt="Product 6">
                        <h5 class="mt-2">Title 6</h5>
                        <p>Know More</p>
                    </div>
                </div>
            </div>
        </div>
        <form action="profile.php" method="POST" class="text-center">
            <button type="submit" class="logout-btn" name="logout">Logout</button>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>