<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/water.css@2/out/water.css">
    <title>FDC-MTR</title>
</head>
<body>
    <form action="/IAS-FDC-MTR/scripts/signup.php" method="post">
        <div>
            <label for="email">Email</label>
            <input type="email" name="email" placeholder="Enter email">
        </div>
        <div>
            <label for="password">Password</label>
            <input type="password" name="password" placeholder="Enter password">
        </div>
        <div>
            <label for="passwordConf">Confirm Password</label>
            <input type="password" name="passwordConf" placeholder="Confirm password">
        </div>
        <div>
            <button type="submit">Signup</button>
        </div>
        <div><p>Already have an account? <a href="/IAS-FDC-MTR/index.php">Login</a></p></div>
    </form>
</body>
</html>
