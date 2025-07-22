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

$id = isset($_GET['id']) ? intval($_GET['id']) : 0;

$stmt = $conn->prepare("SELECT `id_application`, `id_status` FROM `applications` WHERE `id_application` = ?");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Ошибка: Заявка не найдена.");
}

$row = $result->fetch_assoc();
$stmt->close();

$statuses = $conn->query("SELECT * FROM `status`");
?>

<!doctype html>
<html lang="ru">

<head>
    <title>Подача заявки</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>
<style>
/* SM breakpoint */
@media (min-width: 576px) {

    /* CUSTOM WIDTHS */
    .w-sm-10 {
        width: 10% !important;
    }

    .w-sm-15 {
        width: 15% !important;
    }

    .w-sm-20 {
        width: 50% !important;
    }
}

/* MD breakpoint*/
@media (min-width: 768px) {

    /* CUSTOM WIDTHS */
    .w-md-10 {
        width: 10% !important;
    }

    .w-md-15 {
        width: 15% !important;
    }

    .w-md-20 {
        width: 40% !important;
    }
}

/* LG breakpoint */
@media (min-width: 992px) {

    /* CUSTOM WIDTHS */
    .w-lg-10 {
        width: 10% !important;
    }

    .w-lg-15 {
        width: 15% !important;
    }

    .w-lg-20 {
        width: 20% !important;
    }
}
</style>
<body class="bg-light">
    <header class="bg-white fs-3 d-flex align-items-center justify-content-around gap-5">
        <div class="logo">
            <a href="admin_profile.php"><img src="css/logo.png" width="300"></a>
        </div>
        <div class="justify-content-end">
            <nav class="nav pt-1">
                <a class="nav-link link-secondary" href="admin_profile.php" aria-current="page">Список заявок</a>
                <a class="nav-link link-secondary" href="logout.php">Выйти</a>
            </nav>
        </div>
    </header>

    <section id="formTable" class="bg-light" style="height: 765px;">
        <div class="container-fluid d-flex h-75 w-75 justify-content-center align-items-center p-0 text-center">
            <form action="update_status.php" method="post" class="pt-2 w-100 w-md-20">
                <h2 class="pb-3">Смена статуса</h2>
                <input type="hidden" name="id_application" value="<?php echo htmlspecialchars($row['id_application']); ?>" />
                <select name="id_status" id="selectStatus" onchange="document.getElementById('textInput').style.display = this.value === '3' ? 'block' : 'none';"
                    class="form-select mb-3 p-3" aria-label="Default select example">
                    <option selected disabled>Выберите статус заявки</option>
                    <option value="1">В работе</option>
                    <option value="2">Выполнено</option>
                    <option value="3">Отменено</option>
                </select>
                <input type="text" class="mb-3 form-control" name="note" id="textInput" style="display:none;" placeholder="Введите причину отмены" />
                <button type="submit" class="btn btn-secondary fw-semibold w-100 text-uppercase">Изменить</button>
            </form>
        </div>
    </section>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>