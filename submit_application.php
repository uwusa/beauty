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

$login = $_SESSION['login'];

$stmt = $conn->prepare("SELECT `id_user` FROM `user` WHERE `login` = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->bind_result($id_user);
$stmt->fetch();
$stmt->close();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $phone = trim($_POST['phone']);
    $datetime = $_POST['datetime'];
    $method = $_POST['method']; 
    $else = isset($_POST['other_service']) ? trim($_POST['other_service']) : '';
    $card_number = $_POST['card_number'];
    $card_holder = $_POST['card_holder'];
    $expiry_date = $_POST['expiry_date'];
    $cvv = $_POST['cvv'];

    if (empty($phone) || empty($datetime)) {
        die("Ошибка: Все поля обязательны для заполнения.");
    }

    $id_status = 1;

    $types = isset($_POST['type']) ? $_POST['type'] : [];
    if (!is_array($types)) {
        $types = [];
    }

    if (empty($types)) {
        die("Ошибка: Не выбраны услуги.");
    }

    if (in_array('Others', $types) && empty($else)) {
        die("Ошибка: Пожалуйста, укажите иную услугу.");
    }

    foreach ($types as $type) {
        $stmt = $conn->prepare("INSERT INTO `applications` (`phone`, `datetime`, `id_type`, `id_method`, `else`, `id_status`, `id_user`, `card_number`, `card_holder`, `expiry_date`, `cvv`) VALUES (?, ?, ?, ?, ?, ?, ?, ?, ?, ?, ?)");
        if ($stmt === false) {
            die("Ошибка подготовки запроса: " . $conn->error);
        }

        $stmt->bind_param("ssiisiissss", $phone, $datetime, $type, $method, $else, $id_status, $id_user, $card_number, $card_holder, $expiry_date, $cvv);
        if (!$stmt->execute()) {
            die("Ошибка при отправке заявки: " . $stmt->error);
        }
    }

    $stmt->close();
    echo "Заявки успешно отправлены!";
    header("Location: applications.php?login=" . urlencode($login));
    exit();
}

$conn->close();
?>