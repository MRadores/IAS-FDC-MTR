<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>FDC-MTR</title>
</head>
<body>
    <form action="/IAS-FDC-MTR/scripts/login.php" method="post">
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Enter email">
        </div>

        <div>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter password">
        </div>
        <div><a href="/IAS-FDC-MTR/pages/forgot-password.php">forgot password?</a></div>

        <br>
        <div>
            <button type="submit">Login</button>
        </div>
        <div><p>Doesn't have an account? <a href="/IAS-FDC-MTR/pages/signup.php">Signup</a></p></div>
    </form>
</body>
</html>
