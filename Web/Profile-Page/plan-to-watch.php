<?php
require_once '../service/database.php';
session_start();

if (isset($_SESSION['user_id']) === false) {
    header("Location: ../../index.php");
    exit();
}

$user_id = $_SESSION['user_id']; 

$query = "
    SELECT ml.movie_id, ml.title, ml.poster_path, ml.rating, ml.genre, ml.overview
    FROM movie_list ml
    JOIN movie_status ms ON ml.movie_id = ms.id_movie
    WHERE ms.id_user = $1 AND ms.plan_to_watch_status = true
";

$result = pg_query_params($dbconn, $query, [$user_id]);

if (!$result) {
    die("Error executing query: " . pg_last_error($dbconn));
}

$movies = pg_fetch_all($result);

if ($_SERVER["REQUEST_METHOD"] === "POST" && isset($_POST['movie_id'])) {
    $movie_id = $_POST['movie_id'];
    $user_id = $_SESSION['user_id'];

    $query = "UPDATE movie_status SET plan_to_watch_status = FALSE WHERE id_user = $1 AND id_movie = $2";
    $result = pg_query_params($dbconn, $query, [$user_id, $movie_id]);

    if ($result) {
        header("Location: " . $_SERVER['PHP_SELF']);
    } else {
        die("Error deleting movie: " . pg_last_error($dbconn));
    }
}
?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Plan to Watch List Movie Page</title>
    <link href="../../Bootstrap/css/bootstrap.min.css" rel="stylesheet">
    <link href='https://fonts.googleapis.com/css?family=Poppins' rel='stylesheet'>
    <link rel="stylesheet" href="style.css">
    <link rel="icon" href="../Assets/img/icon fix kiw kiw.png">
</head>
<body>
    <div class="container mt-5 position-relative">
        <a href="profile.php" class="back-button position-absolute top-0 end-0 mt-2 me-3" style="font-size: 25px;">X</a>
        <h1 class="text-center mb-4">Liked Movie List</h1>
        <div class="table-responsive"> <!-- Tambahkan div ini untuk responsivitas -->
            <table class="table table-striped table-dark table-bordered">
            <thead class="thead-dark">
                <tr>
                    <th>#</th>
                    <th>Image</th>
                    <th>Movie Title</th>
                    <th>Rating</th>
                    <th>Genre</th>
                    <th>Overview</th>
                    <th>Remove</th>
                </tr>
            </thead>
            <tbody>
            <?php if ($movies): ?>
                    <?php foreach ($movies as $index => $movie): ?>
                        <tr>
                            <td><?= $index + 1; ?></td>
                            <td><img src="https://image.tmdb.org/t/p/w500/<?= htmlspecialchars($movie['poster_path']); ?>" alt="Poster" style="width: 100px;"></td>
                            <td><?= htmlspecialchars($movie['title']); ?></td>
                            <td><?= htmlspecialchars($movie['rating']); ?></td>
                            <td><?= htmlspecialchars($movie['genre']); ?></td>
                            <td class="overview-cell"><?= htmlspecialchars($movie['overview']); ?></td>
                            <td>
                                <form method="post" action="like.php">
                                    <input type="hidden" name="movie_id" value="<?= $movie['movie_id']; ?>">
                                    <button type="submit" class="btn btn-danger btn-sm">Remove</button>
                                </form>
                            </td>
                        </tr>
                    <?php endforeach; ?>
                <?php else: ?>
                    <tr>
                        <td colspan="7" class="text-center">No Data Found</td>
                    </tr>
                <?php endif; ?>
            </tbody>
            </table>
        </div>
    </div>
    <script src="../../Bootstrap/js/bootstrap.bundle.min.js"></script>
    <script src="script.js"></script>
</body>
</html>