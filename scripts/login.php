<?php

declare(strict_types = 1);


session_start();

function wrongPass(object $conn, string $email, string $password) {
    $user = getUser($conn, $email);
    return !password_verify($password, $user["password"] ?? '');
}




if($_SERVER["REQUEST_METHOD"] === "POST"){
    try {
        $email = $_POST["email"] ?? null;
        $password = $_POST["password"] ?? null;
        $action = $_POST["action"] ?? '';

        require_once __DIR__ ."/global-functions.php";
        require_once __DIR__ ."/db.php";

        //error handlers
        $errors = [];
        if(emptyInputs([$email, $password])){
            $errors["Input_Empty"] = "Fill in all fields!";
        } elseif(invalidEmail($email)){
            $errors["Invalid_Email"] = "Please enter a valid email.";
        } elseif (!emailExisting($conn, $email) || wrongPass($conn, $email, $password) {
            $errors["Wrong_credentials"] = "Wrong login credentials";
        }

        if($errors){
            foreach($errors as $error){
                echo "<script>alert('" . $error . "'); window.location.href='/IAS-FDC-MTR/index.php'</script>";
            }
            die();
        }

        $otp = (string) generateOTP();
        $hashed_otp = password_hash($otp, PASSWORD_DEFAULT);
        $message = "Your OTP is $otp. \nThis OTP will expire after 3 minutes.";
        $subject = "LOGIN VERIFICATION";
        $verification_type = "login";
        $hashed_pass = password_hash($password, PASSWORD_DEFAULT);
        $existing = (bool) getUser($conn, $email);
        // initialRegister($conn, $existing, $email, $hashed_pass);
        if (getUserOtp($conn, $email)) {
            if (interval($conn, $email)) {
                storeOtp($conn, $email, $hashed_otp, $verification_type, $action);
                sendEmail($email, $message, $subject);
                // echo "<script>alert('We\'ve sent a new OTP to $email'); windows.location.href = '/IAS-FDC-MTR/pages/verify.php'</script>";
            } else {
                echo "<script>alert('Please wait a moment before resending OTP.'); window.location.href = '/IAS-FDC-MTR/pages/verify.php';</script>";
                die();
            }
        } else {
            storeOtp($conn, $email, $hashed_otp, $verification_type, $action);
            sendEmail($email, $message, $subject);
        }

        $_SESSION["signed_in"] = true;
        $_SESSION["form_action"] = "/IAS-FDC-MTR/scripts/verify.php?auth=login";
        $_SESSION["email"] = $email;
        // header("location: home.php");
        // echo "<script>alert('Logged in successfully.'); window.location.href='/IAS-FDC-MTR/pages/home.php'</script>";
        echo "<script>alert('We\'ve sent a new OTP to $email'); window.location.href = '/IAS-FDC-MTR/pages/verify.php'</script>";
        exit();
    } catch (\PDOEXCEPTION $e) {
        echo "Database Error " . $e->getMessage();
        die();
    }
} else {
    echo "<script>window.location.href='index.php'</script>";
    die();
}
