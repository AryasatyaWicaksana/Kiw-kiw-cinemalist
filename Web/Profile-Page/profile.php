<?php
    require_once '../service/database.php';
    session_start();

    if (!isset($_SESSION["is_login"])) {
        header("Location: ../../index.php");
        exit();
    }
    
    $user_id = $_SESSION['user_id'];

    function getMoviesByStatus($dbconn, $user_id, $status_column) {
        $query = "
            SELECT ml.poster_path
            FROM movie_list ml
            JOIN movie_status ms ON ml.movie_id = ms.id_movie
            WHERE ms.id_user = $1 AND ms.$status_column = TRUE
            LIMIT 10;
        ";
    
        $result = pg_query_params($dbconn, $query, [$user_id]);
        if (!$result) {
            die("Error executing query: " . pg_last_error($dbconn));
        }
        return pg_fetch_all($result);
    }

    $movies_like = getMoviesByStatus($dbconn, $user_id, 'like_status');
    $movies_complete = getMoviesByStatus($dbconn, $user_id, 'completed_status');
    $movies_ptw = getMoviesByStatus($dbconn, $user_id, 'plan_to_watch_status');

    if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['logout'])) {
        session_unset();
        session_destroy();
        header("Location: ../../index.php");
        exit();
    }
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profile Page</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">
    <link href="https://fonts.googleapis.com/css2?family=Poppins:wght@400&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/profile-img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container my-5 position-relative">
        <a href="../Dashboard/dashboard.php" class="back-button position-absolute top-0 end-0 mt-2 me-3">X</a>
        <h1 class="text-center mb-3">Profile</h1>

        <div class="text-center mb-4">
            <img src="../Assets/profile-img/<?= $_SESSION['user_profile'] ?>" alt="Profile Photo" class="img-fluid rounded-circle" style="max-width: 150px;">
        </div>
        <div class="text-center mb-4">
            <label class="fs-4">Username:</label>
            <span class="user-name fs-4"><?= htmlspecialchars($_SESSION["username"]); ?></span>
            <a href="edit_profile.php" class="btn text-center m-2 mb-3"><i class="bi bi-pencil"></i></a>
        </div>

        <?php
        $movie_sections = [
            ['title' => 'Liked Movies', 'data' => $movies_like, 'link' => 'like.php', 'btn_class' => 'btn-secondary'],
            ['title' => 'Completed Movies', 'data' => $movies_complete, 'link' => 'completed.php', 'btn_class' => 'btn-success'],
            ['title' => 'Plan to Watch Movies', 'data' => $movies_ptw, 'link' => 'plan-to-watch.php', 'btn_class' => 'btn-warning']
        ];

        foreach ($movie_sections as $section):
        ?>
        <section id="movieList" class="container text-bg-dark rounded-4 mb-5">
            <div class="d-flex justify-content-between">
                <h3 class="m-2"><?= htmlspecialchars($section['title']); ?></h3>
                <a href="<?= htmlspecialchars($section['link']); ?>" class="more-info btn <?= htmlspecialchars($section['btn_class']); ?> m-2">
                    More Info <i class="bi bi-arrow-right-circle ps-2"></i>
                </a>
            </div>
            <div id="newList" class="d-flex flex-nowrap overflow-x-auto gap-2 p-3">
                <?php if ($section['data']): ?>
                    <?php foreach ($section['data'] as $movie): ?>
                        <img src="https://image.tmdb.org/t/p/w500/<?= htmlspecialchars($movie['poster_path']); ?>" alt="Movie Poster" class="img-fluid" style="width: 150px;">
                    <?php endforeach; ?>
                <?php else: ?>
                    <p class="text-bg-dark">No movies found in this section.</p>
                <?php endif; ?>
            </div>
        </section>
        <?php endforeach; ?>

        <form method="POST" class="text-center">
            <button type="submit" name="logout" class="logout-btn btn btn-lg mb-3">Logout</button>
        </form>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
</body>
</html>