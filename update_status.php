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

$status = $_POST['id_status']; 
$note = isset($_POST['note']) ? $_POST['note'] : '';
$id_application = $_POST['id_application']; 

$sql = "UPDATE `applications` SET `id_status` = ?, `note` = ? WHERE `id_application` = ?";
$stmt = $conn->prepare($sql);

if ($stmt === false) {
    die("Ошибка подготовки запроса: " . $conn->error);
}

$stmt->bind_param("isi", $status, $note, $id_application);

if ($stmt->execute()) {
    header("Location: admin_profile.php?message=Статус заявки успешно обновлен.");
    exit();
} else {
    header("Location: admin_profile.php?error=Ошибка обновления статуса: " . urlencode($stmt->error));
    exit();
}

$stmt->close();
$conn->close();
?>