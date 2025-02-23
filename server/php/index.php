<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $username = $_POST['username'] ?? '';
    $password = $_POST['password'] ?? '';

  // example
    if ($username && $password) {
        $_SESSION['username'] = $username;
        header('Location: app.php');
        exit();
    }
}
?>

<!DOCTYPE html>
<html lang="ru">
<head>
    <meta charset="UTF-8">
    <title>Login</title>
</head>
<body>
    <h1>Login</h1>
    <form method="POST">
        <input type="text" name="username" placeholder="Логин" required>
        <input type="password" name="password" placeholder="Пароль" required>
        <button type="submit">Login</button>
    </form>
</body>
</html>

