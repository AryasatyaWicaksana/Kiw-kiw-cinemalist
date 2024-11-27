<?php 
    require_once 'env_reader.php';

    $host =  $_ENV['DB_HOST'];
    $port =  $_ENV['DB_PORT'];
    $dbname =  $_ENV['DB_NAME'];
    $user =  $_ENV['DB_USER'];
    $pass =  $_ENV['DB_PASS'];

    try {
        $dbconn = pg_connect("host=$host port=$port dbname=$dbname user=$user password=$pass");
        if (!$dbconn) {
            throw new Exception("Koneksi gagal: " . pg_last_error());
        }
    } catch (Exception $e) {
        die($e->getMessage());
    }
?>