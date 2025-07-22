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

$stmt = $conn->prepare("SELECT `id_user`, `name`, `phone` FROM `user` WHERE `login` = ?");
$stmt->bind_param("s", $login);
$stmt->execute();
$stmt->bind_result($id_user, $name, $phone);
if (!$stmt->fetch()) {
    die("Пользователь не найден.");
}
$stmt->close();

$stmt = $conn->prepare("SELECT `applications`.`datetime`, `type`.`name_type`,
                        `method`.`name_method`, `status`.`name_status`,
                        `applications`.`note`, `applications`.`else`
                        FROM `applications`
                        JOIN `method` ON `applications`.`id_method` = `method`.`id_method`
                        JOIN `type` ON `applications`.`id_type` = `type`.`id_type`
                        JOIN `status` ON `applications`.`id_status` = `status`.`id_status`
                        WHERE `applications`.`id_user` = ?");
$stmt->bind_param("i", $id_user);
$stmt->execute();
$result = $stmt->get_result();
?>

<!doctype html>
<html lang="ru">

<head>
    <title>История заявок</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<style>
    .status-in-progress {
        color: orange;
    }

    .status-completed {
        color: green;
    }

    .status-canceled {
        color: red;
    }
</style>

<body class="bg-light">
    <header class="bg-white fs-3 d-flex align-items-center justify-content-around">
        <div class="logo">
            <a href="user_profile.php"><img src="css/logo.png" width="300"></a>
        </div>
        <div class="justify-content-end">
            <nav class="nav pt-1">
                <a class="nav-link link-secondary" href="user_profile.php" aria-current="page">Подача заявки</a>
                <a class="nav-link link-secondary" href="applications.php">Список заявок</a>
                <a class="nav-link link-secondary" href="logout.php">Выйти</a>
            </nav>
        </div>
    </header>

    <section id="formTable" class="bg-light">
        <div class="container w-100 align-items-center justify-content-center d-flex flex-column">
            <h2 class="pb-3">История заявок пользователя: <?php echo htmlspecialchars($name ?? 'Неизвестно'); ?></h2>
            <table class="table table-hover text-center">
                <thead>
                    <tr>
                        <th>Дата и время получения услуги</th>
                        <th>Тип услуги</th>
                        <th>Другой тип услуги</th>
                        <th>Способ оплаты</th>
                        <th>Статус заявки</th>
                        <th>Сообщение от администратора</th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $colorArray = array('В работе' => '#FBAA6A', 'Выполнено' => 'green', 'Отменено' => 'red');
                    if ($result->num_rows > 0) {
                        while ($row = $result->fetch_assoc()) {
                            echo "<tr>";
                            echo "<td data-label='Дата и время получения услуги'>" . htmlspecialchars($row['datetime'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Тип услуги'>" . htmlspecialchars($row['name_type'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Другой тип услуги'>" . htmlspecialchars($row['else'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Способ оплаты'>" . htmlspecialchars($row['name_method'] ?? 'Не указано') . "</td>";
                            echo "<td data-label='Статус' style='color:" . $colorArray[$row['name_status']] . "; font-weight:bold'>" . htmlspecialchars($row['name_status']) .  "</td>";
                            echo "<td data-label='Сообщение от администратора'>" . htmlspecialchars($row['note'] ?? 'Не указано') . "</td>";
                            echo "</tr>";
                        }
                    } else {
                        echo "<tr><td colspan='6'>Нет заявок.</td></tr>";
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