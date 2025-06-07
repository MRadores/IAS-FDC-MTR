<?php

declare(strict_types = 1);
use PHPMailer\PHPMailer\PHPMailer;
require_once __DIR__ . '/../vendor/autoload.php'; // Adjust path as needed


function sendEmail(string $email, string $message, string $subject): void{
    $mail = new PHPMailer();
    $mail->isSMTP();
    $mail->SMTPDebug=0;
    $mail->SMTPAuth=1;
    $mail->SMTPSecure="tls";
    $mail->Host="smtp.gmail.com";
    $mail->Port="587";
    $mail->addAddress($email);
    $mail->Username="radoresmikko344@gmail.com";
    $mail->Password="uxex xmyy blsn kpxh";
    $mail->setFrom("radoresmikko344@gmail.com", "FDC-MTR");
    $mail->Subject=$subject;
    $mail->msgHTML($message);
    $mail->Send();
}

function generateOTP(): int {
    return rand(100000, 999999);
}

function emptyInputs(array $inputs): bool {
    foreach ($inputs as $input) {
        if(empty($input)){
            return true;
        }
    }
    return false;
}

function invalidEmail(string $email): bool {
    return !filter_var($email, FILTER_VALIDATE_EMAIL);
}

function emailExisting(object $conn, string $email): bool {
    $user = getUser($conn, $email);
    if($user) {
        return $user["status"] === "verified";
    } else {
        return false;
    }
}

//database
function getUser(object $conn, string $email): array | bool {
    $query = "SELECT * FROM users WHERE email = :email;";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function getUserOtp(object $conn, string $email): array | bool {
    $query = "SELECT * FROM otp_verifications WHERE email = :email AND is_used = 0;";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}
function interval(object $conn, string $email): array | bool {
    $query = "SELECT * FROM otp_verifications WHERE email = :email AND expires_at < NOW();";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email
    ]);
    return $stmt->fetch(\PDO::FETCH_ASSOC);
}

function storeOtp(object $conn, string $email, string $otp, string $verification_type, string|null $action): void {
    if (!$action) {
        $query = "INSERT INTO otp_verifications(email, otp, verification_type, expires_at)
        VALUES(:email, :otp, :verification_type, NOW() + INTERVAL 3 minute);";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ":email" => $email,
            ":otp" => $otp,
            ":verification_type" => $verification_type
        ]);
    } elseif ($action === "resend") {
        $query = "UPDATE otp_verifications SET otp = :otp, expires_at = NOW() + INTERVAL 3 minute WHERE email = :email;";
        $stmt = $conn->prepare($query);
        $stmt->execute([
            ":otp" => $otp,
            ":email" => $email
        ]);
    }
}