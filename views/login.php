<!DOCTYPE html>
<html lang="pt">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login - The Guardian</title>
    <link rel="icon" type="image/png" href="../assets/favicon.ico">
    <link rel="stylesheet" href="../assets/css/global.css">
    <link rel="stylesheet" href="../assets/css/login.css">
</head>
<body>
    <div class="login-container">
        <img src="../assets/images/logo.png" alt="Logo The Guardian" class="logo">
        <form action="../controllers/LoginController.php" method="post">
            <label for="email">E-mail:</label>
            <input type="email" name="email" required>

            <label for="password">Senha:</label>
            <input type="password" name="senha" required>

            <button type="submit">Login</button>
        </form>
    </div>
</body>
</html>