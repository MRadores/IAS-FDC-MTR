<?php

declare(strict_types=1);

session_start();

function invalidOtp(string $otp): bool {
    return !filter_var($otp, FILTER_VALIDATE_INT);
}

function wrongOtp(object $conn, string $email, ?string $otp): bool {
    $user_otp = getUserOtp($conn, $email);
    return !password_verify($otp, $user_otp['otp'] ?? '');
}



//database
function updateStatus(object $conn, string $email): void {
    $query = "UPDATE users SET status = 'verified' WHERE email = :email;";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email
    ]);
}

function deleteUserOtp($conn, $email): void {
    $query = "DELETE FROM otp_verifications WHERE email = :email;";
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email
    ]);
}

if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {
        $otp = $_POST["otp"] ??  null;
        $email = $_POST["email"] ??  null;

        require_once __DIR__ ."/db.php";
        require_once __DIR__ ."/global-functions.php";

        $errors = [];
        if (emptyInputs([$otp])) {
            $errors["Empty_Inputs"] = "Fill in all fields.";
        } elseif (invalidOtp($otp)) {
            $errors["Invalid_Otp"] = "Invalid OTP!";
        } elseif (wrongOtp($conn, $email, $otp)) {
            $errors["Wrong_Otp"] = "Incorrect OTP.";
        }

        if ($errors) {
            foreach ($errors as $error) {
                echo "<script>alert('$error'); window.location.href = '/IAS-FDC-MTR/pages/verify.php'</script>";
            }
            die();
        }

        updateStatus($conn, $email);
        deleteUserOtp($conn, $email);
        echo "<script>alert('Account successfully verified!'); window.location.href = '/IAS-FDC-MTR/index.php'</script>";
        die();

    } catch (\PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
        die();
    }
} else {
    echo "<script>Alert('Invalid request method.');</script>";
    die();
}
