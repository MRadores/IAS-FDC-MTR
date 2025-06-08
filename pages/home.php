<?php
session_start();
if ($_SESSION["signed_in"] !== true) {
    echo "<script>alert('User not signed in'); window.location.href = '/IAS-FDC-MTR/index.php'</script>";
}
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>FDC-MTR</title>
</head>
<body>
    <h1>WELCOME <?= $_SESSION["email"]?></h1>

    <form action="/IAS-FDC-MTR/scripts/signout.php" method="POST">
        <button type="submit">SIGNOUT</button>
    </form>
</body>
</html>
