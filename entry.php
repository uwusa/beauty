<?php
session_start();
$host = 'mysql-8.0';
$db = 'db_salon';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    $_SESSION['error'] = "Ошибка подключения: " . $conn->connect_error;
    header("Location: login.php"); // Предполагается, что ваша страница авторизации называется login.php
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $login = trim($_POST['login']);
    $pwd = trim($_POST['pwd']);

    if (empty($login) || empty($pwd)) {
        $error_message = "Все поля обязательны для заполнения.";
    } else {
        $stmt = $conn->prepare("SELECT `pwd`, `id_user` FROM `user` WHERE `login` = ?");
        if ($stmt === false) {
            $error_message = "Ошибка подготовки запроса: " . $conn->error;
        } else {
            $stmt->bind_param("s", $login);
            $stmt->execute();
            $stmt->bind_result($hashedpassword, $id_user);
            $stmt->store_result();
           
            if ($stmt->num_rows === 0) {
                $error_message = "Неверный логин или пароль.";
            } else {
                $stmt->fetch();

                if (password_verify($pwd, $hashedpassword)) {
                    $_SESSION['id_user'] = $id_user;
                    $_SESSION['login'] = $login;

                    if ($id_user == 1) {
                        header("Location: admin_profile.php");
                    } else {
                        header("Location: user_profile.php"); 
                    }
                    exit();
                } else {
                    $error_message = "Неверный логин или пароль.";
                }
            }
            $stmt->close();
        }
    }
}

$conn->close();

// Если есть ошибка, сохраняем её в сессии и перенаправляем на страницу авторизации
if (!empty($error_message)) {
    $_SESSION['error'] = $error_message;
    header("Location: login.php");
    exit();
}
?>