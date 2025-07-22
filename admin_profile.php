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
$name = $phone = '';

// Получаем информацию о пользователе
$stmt = $conn->prepare("SELECT `id_user`, `name`, `phone` FROM `user` WHERE `login` = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->bind_result($id_user, $name, $phone);
if (!$stmt->fetch()) {
    die("Пользователь не найден.");
}
$stmt->close();

// Получаем статус для фильтрации
$status_filter = isset($_GET['status']) ? $_GET['status'] : 'all';
$status_condition = '';

if ($status_filter !== 'all') {
    $status_condition = "WHERE `status`.`name_status` = ?";
}

// Подготавливаем запрос для получения заявок
$stmt = $conn->prepare("SELECT `applications`.`id_application`, `applications`.`datetime`,
                        `type`.`name_type`, `user`.`name`,
                        `applications`.`phone`, `user`.`email`,
                        `user`.`login`, `method`.`name_method`,
                        `status`.`name_status`, `applications`.`note`,
                        `applications`.`card_number`, `applications`.`card_holder`,
                        `applications`.`expiry_date`,`applications`.`cvv`
                        FROM `applications`
                        JOIN `method` ON `applications`.`id_method` = `method`.`id_method`
                        JOIN `type` ON `applications`.`id_type` = `type`.`id_type`
                        JOIN `status` ON `applications`.`id_status` = `status`.`id_status`
                        JOIN `user` ON `applications`.`id_user` = `user`.`id_user`
                        $status_condition");

if ($status_filter !== 'all') {
    $stmt->bind_param("s", $status_filter);
}

if (!$stmt->execute()) {
    die("Ошибка выполнения запроса: " . $stmt->error);
}
$result = $stmt->get_result();
$stmt->close();
?>

<!doctype html>
<html lang="ru">

<head>
    <title>Список всех заявок пользователей</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" type="text/css">
    <style>
        .table{
            width: 100vh;
        }
    </style>
</head>

<body class="bg-light">
    <header class="bg-white fs-3 d-flex align-items-center justify-content-around gap-5">
        <div class="logo">
            <a href="admin_profile.php"><img src="css/logo.png" width="250"></a>
        </div>
        <div class="justify-content-end">
            <nav class="nav pt-1">
                <a class="nav-link link-secondary" href="admin_profile.php" aria-current="page">Список заявок</a>
                <a class="nav-link link-secondary" href="logout.php">Выйти</a>
            </nav>
        </div>
    </header>

    <section id="formTable" class="bg-light">
        <div class="container align-items-center justify-content-center d-flex flex-column">
            <h2 class="pb-3">История заявок всех пользователей:</h2>
            <form method="GET" class="pb-3">
                <select name="status" class="form-select" onchange="this.form.submit()">
                    <option value="all" <?= $status_filter === 'all' ? 'selected' : '' ?>>Все</option>
                    <option value="В работе" <?= $status_filter === 'В работе' ? 'selected' : '' ?>>В работе</option>
                    <option value="Выполнено" <?= $status_filter === 'Выполнено' ? 'selected' : '' ?>>Выполнены</option>
                    <option value="Отменено" <?= $status_filter === 'Отменено' ? 'selected' : '' ?>>Отменены</option>
                </select>
            </form>

            <table class="table table-hover text-center w-100">
                <thead>
                    <tr class="align-middle">
                        <th>ФИО</th>
                        <th>Номер телефона</th>
                        <th>Логин</th>
                        <th>Почта</th>
                        <th>Дата и время получения услуги</th>
                        <th>Тип услуги</th>
                        <th>Другой тип услуги</th>
                        <th>Способ оплаты</th>
                        <th>Номер карты</th>
                        <th>Владелец карты</th>
                        <th>Дата действия</th>
                        <th>Статус</th>
                        <th>Сообщение от администратора</th>
                        <th>Действие</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $colorArray = array('В работе' => '#FBAA6A', 'Выполнено' => 'green', 'Отменено' => 'red');
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            
                            echo "<tr class='align-middle'>";
                            echo "<td data-label='ФИО'>" . htmlspecialchars($row['name'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Номер телефона'>" . htmlspecialchars($row['phone'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Логин'>" . htmlspecialchars($row['login'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Почта'>" . htmlspecialchars($row['email'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Дата и время получения услуги'>" . htmlspecialchars($row['datetime'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Тип услуги'>" . htmlspecialchars($row['name_type'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Другое'>" . htmlspecialchars($row['else'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Способ оплаты'>" . htmlspecialchars($row['name_method'] ?? 'Не указано') . "</td>";

                            echo "<td data-label='Номер карты'>" . htmlspecialchars($row['card_number'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Владелец карты'>" . htmlspecialchars($row['card_holder'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Дата действия'>" . htmlspecialchars($row['expiry_date'] ?? 'Не указано') . "</td>";

                            echo "<td data-label='Статус' style='color:" . $colorArray[$row['name_status']] . "; font-weight:bold'>" . htmlspecialchars($row['name_status']) .  "</td>";
                            echo "<td data-label='Сообщение от администратора'>" . htmlspecialchars($row['note'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Действие'>";
                            echo "<a href='update_application.php?id=" . htmlspecialchars($row['id_application']) . "' class='btn btn-outline-secondary w-100'>Обновить</a>";
                            echo "<a href='delete_application.php?id=" . htmlspecialchars($row['id_application']) . "' class='btn btn-outline-danger w-100' onclick='return confirm(\"Вы уверены, что хотите удалить?\");'>Удалить</a>";
                            echo "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='14'>Нет заявок.</td></tr>";
                    }
                    ?>
                </tbody>
            </table>
        </div>
    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>