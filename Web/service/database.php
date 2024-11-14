<?php 
    require_once '../../vendor/autoload.php';
    use Dotenv\Dotenv;

    $dotenv = Dotenv::createImmutable(__DIR__ . '/../..');
    $dotenv->load();

    $host =  $_ENV['DB_HOST'];
    $port =  $_ENV['DB_PORT'];
    $dbname =  $_ENV['DB_NAME'];
    $user =  $_ENV['DB_USER'];
    $pass =  $_ENV['DB_PASS'];

    try {
        $dbconn = pg_connect("host = $host port = $port dbname = $dbname user = $user password = $pass");
    } catch (PDOException $e) {
        die("Koneksi gagal: " . preg_last_error());
    }
?>