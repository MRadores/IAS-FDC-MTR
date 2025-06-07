<?php

declare(strict_types = 1);
session_start();


function passDifferent(string $password, string $passwordConf): bool {
    return $password !== $passwordConf;
}



//database


function initialRegister(object $conn, bool $existing, string $email, string $hashed_pass): void {
    if (!$existing) {
        $query = "INSERT INTO users(email, password) VALUES(:email, :password);";
    } else {
        $query = "UPDATE users SET password = :password WHERE email = :email;";
    }
    $stmt = $conn->prepare($query);
    $stmt->execute([
        ":email" => $email,
        ":password" => $hashed_pass
    ]);
}






if ($_SERVER["REQUEST_METHOD"] === "POST") {
    try {

        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;
        $passwordConf = $_POST["passwordConf"] ?? null;
        $action = $_GET["action"] ?? null;

        require_once __DIR__ ."/db.php";
        require_once __DIR__ ."/global-functions.php";

        //error handlers
        $errors = [];
        if (emptyInputs([$email, $password, $passwordConf])) {
            $errors["Empty_Inputs"] = "Fill in all fields.";
        } elseif (invalidEmail($email)) {
            $errors["Invalid_Email"] = "Invalid email.";
        } elseif (emailExisting($conn, $email)) {
            $errors["Exisiting_Email"] = "Email already exists.";
        } elseif (passDifferent($password, $passwordConf)) {
            $errors["Password_different"] = "Passwords don\'t match.";
        }

        if ($errors) {
            foreach ($errors as $error) {
                echo "<script>alert('$error'); window.location.href = '/IAS-FDC-MTR/pages/signup.php'</script>";
            }
            // header("Location: /IAS-FDC-MTR/pages/signup.php");
            die();
        }

        $otp = (string) generateOTP();
        $hashed_otp = password_hash($otp, PASSWORD_DEFAULT);
        $message = "Your OTP is $otp. \nThis OTP will expire after 3 minutes.";
        $subject = "SIGNUP VERIFICATION";
        $verification_type = "signup";
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $existing = (bool) getUser($conn, $email);
        initialRegister($conn, $existing, $email, $hashed_pass);
        if (getUserOtp($conn, $email)) {
            if (interval($conn, $email)) {
                storeOtp($conn, $email, $hashed_otp, $verification_type, $action);
                sendEmail($email, $message, $subject);
                echo "We\'ve sent a new OTP to $email";
            } else {
                echo "<script>alert('Please wait a moment before resending OTP.'); window.location.href = '/IAS-FDC-MTR/pages/verify.php';</script>";
                die();
            }
        } else {
            storeOtp($conn, $email, $hashed_otp, $verification_type, $action);
            sendEmail($email, $message, $subject);
            echo "We\'ve sent an OTP to $email";
        }
        $_SESSION["form_action"] = "/IAS-FDC-MTR/scripts/verify.php";
        $_SESSION["email"] = $email;
        $_SESSION["password"] = $password;
        $_SESSION["passwordConf"] = $passwordConf;

        header("Location: /IAS-FDC-MTR/pages/verify.php");
        exit();

    } catch (\PDOException $e) {
        echo "Connection Error: " . $e->getMessage();
        die();
    }
} else {
    echo "<script>alert('Invalid request method.')</script>";
    die();
}
