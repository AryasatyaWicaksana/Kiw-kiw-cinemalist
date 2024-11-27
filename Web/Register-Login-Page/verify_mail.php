<?php
session_start();
require_once '../service/env_reader.php';

// Default langkah
if (!isset($_SESSION['current_step'])) {
    $_SESSION['current_step'] = 1;
}

use PHPMailer\PHPMailer\PHPMailer;
use PHPMailer\PHPMailer\SMTP;
use PHPMailer\PHPMailer\Exception;

$email_name = $_ENV['EMAIL_NAME'];
$email_pass = $_ENV['EMAIL_PASS'];

// Fungsi untuk mengirim kode verifikasi
function sendVerificationEmail($email, $verification_code, $email_name, $email_pass) {
    $mail = new PHPMailer(true);
    try {
        $mail->isSMTP();
        $mail->Host = 'smtp.gmail.com';
        $mail->SMTPAuth = true;
        $mail->Username = $email_name;
        $mail->Password = $email_pass;
        $mail->SMTPSecure = PHPMailer::ENCRYPTION_STARTTLS;
        $mail->Port = 587;

        $mail->setFrom($email_name, 'KiwKiw Cinemalist');
        $mail->addAddress($email);

        $mail->isHTML(true);
        $mail->Subject = 'Verification Code for KiwKiw Cinemalist';
        $mail->Body = "<h3>Hello!</h3><br>
            <p>Your verification code is: <b>{$verification_code}</b><br>
            Use this code to verify your email for registration.<br><br>
            If you didn't request this, please ignore this email.<br><br>
            Best regards,<br>KiwKiw Cinemalist</p>";

        $mail->send();
    } catch (Exception $e) {
        throw new Exception("Gagal mengirim email verifikasi: {$mail->ErrorInfo}");
        return false;
    }
    return true;
}

// Langkah 2: Validasi Kode Verifikasi
if (isset($_POST['submit_code']) && $_SESSION['current_step'] === 2) {
    $code = trim($_POST['code']);
    if (time() > $_SESSION['verification_expiry']) {
        $register_message = "Kode verifikasi telah kedaluwarsa.";
        session_destroy();
    } elseif ($code === $_SESSION['verification_code']) {
        unset($_SESSION['verification_code']);
        $_SESSION['current_step'] = 3;
    } else {
        $register_message = "Kode verifikasi salah.";
    }
}
?>