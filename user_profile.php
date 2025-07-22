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

$stmt = $conn->prepare("SELECT `name`, `phone` FROM `user` WHERE `login` = ?");
$stmt->bind_param("s", $login);
$stmt->execute();

$stmt->bind_result($name, $phone);
if (!$stmt->fetch()) {
    die("Пользователь не найден.");
}
$stmt->close();
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
<script type="text/javascript">
    function toggleOtherTextboxVisible() {
        var check = document.getElementById('OtherCheckBox');
        if (check.checked) {
            document.getElementById('OtherTextBox').style.display = 'block';
        } else
            document.getElementById('OtherTextBox').style.display = 'none';
    }
</script>

<body class="bg-light">
    <header class="bg-white fs-3 d-flex align-items-center justify-content-around">
        <div class="logo">
            <a href="user_profile.php"><img src="css/logo.png" width="250"></a>
        </div>
        <div class="justify-content-end">
            <nav class="nav pt-1">
                <a class="nav-link active link-secondary" href="user_profile.php?email=<?php echo urlencode($login); ?>"
                    aria-current="page">Подача заявки</a>
                <a class="nav-link link-secondary" href="applications.php?email=<?php echo urlencode($login); ?>">Список
                    заявок</a>
                <a class="nav-link link-secondary" href="logout.php?email=<?php echo urlencode($login); ?>">Выйти</a>
            </nav>
        </div>
    </header>

    <section id="formSend">
        <div class="container-fluid d-flex h-100 justify-content-center align-items-center p-5 text-center">
            <form action="submit_application.php" method="post" class="w-100 w-md-20 p-5 needs-validation" novalidate>
                <h2 class="pb-3">Подача заявки</h2>
                <!-- Вывод сообщения об ошибке -->
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']); // Удаляем сообщение после отображения
                }
                ?>

                <div class="form-floating mb-3">
                    <input type="tel" name="phone" id="phone" placeholder="+7(999)999-99-99" class="form-control"
                        pattern="^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$"
                        title="Формат: +7(953)260-54-67" required>
                    <label for="phone">Номер телефона</label>
                    <div id="nameHelpBlock" class="valid-feedback"></div>
                    <div class="invalid-feedback text-start">
                        Формат: +7(953)260-54-67
                    </div>
                </div>

                <div class="form-floating pb-3">
                    <input type="datetime-local" name="datetime" id="data" placeholder="Дата" class="form-control"
                        required>
                    <label for="data">Дата</label>
                </div>

                <div class="pb-3" style="font-size:11pt;">
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="type[]" type="checkbox" id="inlineCheckbox1" value="1">
                        <label class="form-check-label" for="inlineCheckbox1">Маникюр без покрытия</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="type[]" type="checkbox" id="inlineCheckbox2" value="2">
                        <label class="form-check-label" for="inlineCheckbox2">Снятие гель-лака</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="type[]" type="checkbox" id="inlineCheckbox3" value="3">
                        <label class="form-check-label" for="inlineCheckbox3">Маникюр+гель-лак</label>
                    </div>
                    <div class="form-check form-check-inline">
                        <input class="form-check-input" name="type[]" id="OtherCheckBox" type="checkbox" value="4"
                            onchange="toggleOtherTextboxVisible();">
                        <label class="form-check-label" for="OtherCheckBox">Иная услуга</label>
                    </div>
                </div>

                <input type="text" name="other_service" class="mb-3 form-control" id="OtherTextBox" style="display:none;"
                    placeholder="Введите иную услугу" />

                <select name="method"
                    onchange="document.getElementById('method').style.display = this.value === '2' ? 'block' : 'none';"
                    id="selectCash" class="form-select mb-3 p-3" required>
                    <option selected disabled>Выберите способ оплаты</option>
                    <option value="1">Наличные</option>
                    <option value="2">Банковская карта</option>
                    <div id="nameHelpBlock" class="valid-feedback"></div>
                    <div class="invalid-feedback">
                        Формат: +7(953)260-54-67
                    </div>
                </select>

                <div class="" id="method" style="display:none;">
                    <div class="d-flex">
                        <div class="form-floating pb-3">
                            <input type="text" id="card-number" name="card_number" placeholder="1234 5678 9012 3456"
                                pattern="[0-9\s]{13,19}" class="mb-3 form-control" maxlength="16">
                            <label for="card-number">Номер карты</label>
                        </div>
                        <div class="form-floating pb-3">
                            <input type="text" id="card-holder" name="card_holder" placeholder="Name Surname"
                                class="mb-3 form-control" style="text-transform: uppercase">
                            <label for="card-holder">Владелец карты</label>
                        </div>
                        <div class="form-floating pb-3">
                            <input type="month" id="expiry-date" name="expiry_date" placeholder="02"
                                class="mb-3 form-control">
                            <label for="expiry-date">Дата действия</label>
                        </div>
                        <div class="form-floating pb-3">
                            <input type="text" id="cvv" name="cvv" placeholder="***" maxlength="3"
                                class="mb-3 form-control">
                            <label for="cvv">CVV</label>
                        </div>
                    </div>
                </div>

                <button type="submit" class="btn btn-secondary fw-semibold w-100 text-uppercase">Отправить
                    заявку</button>
            </form>
        </div>

    </section>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
    <script>
        (() => {
            'use strict';

            // Получаем все формы на странице с классом 'needs-validation'
            const forms = document.querySelectorAll('.needs-validation');

            // Преобразуем полученную коллекцию форм в массив и для каждой формы выполняем следующее:
            Array.from(forms).forEach(form => {
                // Добавляем обработчик события 'submit' для формы
                form.addEventListener('submit', event => {
                    // Проверяем валидность формы с помощью встроенного метода checkValidity()
                    if (!form.checkValidity()) { // Если форма не проходит валидацию
                        event.preventDefault(); // Отменяем отправку формы
                        event.stopPropagation(); // Остановка всплытия события
                    }

                    // Добавляем класс 'was-validated' к форме для стилизации элементов с помощью CSS
                    form.classList.add('was-validated');
                }, false); // Установка фазы обработки события на захватывающую (false - это фаза всплытия)
            });
        })();
    </script>
</body>

</html>