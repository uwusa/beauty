<?php
session_start();
$host = 'mysql-8.0';
$db = 'db_salon';
$user = 'root';
$pass = '';
$conn = new mysqli($host, $user, $pass, $db);
if ($conn->connect_error) {
    die("Ошибка подключения: " . $conn->connect_error);
}

if (!isset($_SESSION['login']) || empty($_SESSION['login'])) {
    die("Ошибка: пользователь не авторизован.");
}

if (isset($_GET['id'])) {
    $id = intval($_GET['id']); 

    $stmt = $conn->prepare("DELETE FROM `applications` WHERE `id_application` = ?");
    $stmt->bind_param("i", $id);

    if ($stmt->execute()) {
        $_SESSION['message'] = "Заявка успешно удалена.";

    } else {

        $_SESSION['message'] = "Ошибка при удалении заявки: " . $stmt->error;
    }

    $stmt->close();
} else {
    $_SESSION['message'] = "Идентификатор не указан.";
}

$conn->close();

header("Location: admin_profile.php");
exit();
?>