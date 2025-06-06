<?php
    session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>FDC-MTR</title>
</head>
<body>
    <form action="<?= $_SESSION["form_action"] ?>" method="post">
        <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
        <div>
            <h3>OTP Verification</h3>
        </div>
        <div>
            <p>Enter the OTP that was sent to your email.</p>
        </div>
        <div>
            <input type="number" name="otp" id="otp"    >
        </div>
        <div><a href="/IAS-FDC-MTR/pages/signup.php">Go back to signup</a></div>
        <div>
            <button type="submit">VERIFY</button>
        </div>

    </form>
    <form action="/IAS-FDC-MTR/scripts/signup.php?action=resend" method="post">
        <input type="hidden" name="email" value="<?= $_SESSION['email'] ?>">
        <input type="hidden" name="password" value="<?= $_SESSION['password'] ?>">
        <input type="hidden" name="passwordConf" value="<?= $_SESSION['passwordConf'] ?>">
        <button type="submit">Resend OTP</button>
    </form>
</body>
</html>
