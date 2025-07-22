<?php
session_start();
$host = 'mysql-8.0';
$db = 'db_salon';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);

if ($conn->connect_error) {
    $_SESSION['error'] = "Ошибка подключения: " . $conn->connect_error;
    header("Location: index.php"); // Предполагается, что ваша страница регистрации называется register.php
    exit();
}

$error_message = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $name = trim($_POST['name']);
    $login = trim($_POST['login']);
    $email = trim($_POST['email']);
    $phone = trim($_POST['phone']);
    $pwd1 = trim($_POST['pwd1']);
    $pwd2 = trim($_POST['pwd2']);

    if (empty($name) || empty($login) || empty($email) || empty($phone) || empty($pwd1) || empty($pwd2)) {
        $error_message = "Все поля обязательны для заполнения.";
    } elseif ($pwd1 !== $pwd2) {
        $error_message = "Пароли не совпадают.";
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $error_message = "Неверный формат email.";
    } else {
        $stmt = $conn->prepare("SELECT `id_user` FROM `user` WHERE `email` = ?");
        if ($stmt === false) {
            $error_message = "Ошибка подготовки запроса: " . $conn->error;
        } else {
            $stmt->bind_param("s", $email);
            $stmt->execute();
            $stmt->store_result();
            if ($stmt->num_rows > 0) {
                $error_message = "Пользователь с таким email уже есть.";
            }
            $stmt->close(); 
        }

        if (empty($error_message)) {
            $stmt = $conn->prepare("SELECT `id_user` FROM `user` WHERE `login` = ?");
            if ($stmt === false) {
                $error_message = "Ошибка подготовки запроса: " . $conn->error;
            } else {
                $stmt->bind_param("s", $login);
                $stmt->execute();
                $stmt->store_result();
                if ($stmt->num_rows > 0) {
                    $error_message = "Пользователь с таким логином уже есть.";
                }
                $stmt->close(); 
            }
        }

        if (empty($error_message)) {
            $hashedpassword = password_hash($pwd1, PASSWORD_DEFAULT);

            $stmt = $conn->prepare("INSERT INTO `user` (`name`, `login`, `email`, `phone`, `pwd`) VALUES (?, ?, ?, ?, ?)");
            if ($stmt === false) {
                $error_message = "Ошибка подготовки запроса: " . $conn->error;
            } else {
                $stmt->bind_param("sssss", $name, $login, $email, $phone, $hashedpassword);

                if ($stmt->execute()) {
                    $id_user = $stmt->insert_id; 
                    $_SESSION['is_user'] = $id_user;
                    $_SESSION['login'] = $login;

                    header("Location: login.php");
                    exit();
                } else {
                    $error_message = "Ошибка регистрации: " . $stmt->error;
                }
                $stmt->close();
            }
        }
    }
}

// Если есть ошибка, сохраняем её в сессии и перенаправляем на страницу регистрации
if (!empty($error_message)) {
    $_SESSION['error'] = $error_message;
    header("Location: index.php");
    exit();
}

$conn->close();
?>