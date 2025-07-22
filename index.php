<!doctype html>
<html lang="ru">

<head>
    <title>Регистрация</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body class="bg-light">
    <div class="container-fluid d-flex h-100 align-items-center justify-content-center">
        <div class="col d-none d-xl-flex justify-content-end mx-auto me-5">
            <img src="media/1.png" alt="" width="600" style="pointer-events: none;">
        </div>
        <div class="col d-flex">
            <form action="register.php" method="post" class="d-flex flex-column mx-auto w-75 needs-validation" novalidate>
                <h2 class="pb-3">Регистрация</h2>

                <!-- Вывод сообщения об ошибке -->
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']); // Удаляем сообщение после отображения
                }
                ?>

                <div class="form-floating mb-3">
                    <input type="text" name="name" id="name" placeholder="ФИО" class="form-control"
                        pattern="^[А-Яа-яЁё]+\s[А-Яа-яЁё]+\s[А-Яа-яЁё]+$" title="Формат: Иванов Иван Иванович" required>
                    <label for="name">ФИО</label>
                    <div id="nameHelpBlock" class="valid-feedback"></div>
                    <div class="invalid-feedback">
                        Формат: Иванов Иван Иванович
                    </div>
                </div>
                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-adon1">@</span>
                    <input type="text" name="login" class="form-control w-25" placeholder="Введите логин"
                        pattern="^[A-Za-zА-Яа-яЁё]{3,14}$"
                        title="Формат: Буквы на английском и русском, минимум 3, максимум 14" aria-label="Username"
                        aria-describedby="basic-adon1" required>
                    <div id="nameHelpBlock" class="valid-feedback">

                    </div>
                    <div class="invalid-feedback">
                    Буквы на английском и русском, минимум 3, максимум 14
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="email" name="email" id="email" placeholder="Email" class="form-control"
                        pattern="^[a-zA-Z0-9._%+-]+@[a-zA-Z0-9.-]+\.[a-zA-Z]{2,}$" title="Формат: example@domain.com" required>
                    <label for="email">Email</label>
                    <div id="nameHelpBlock" class="valid-feedback">

                    </div>
                    <div class="invalid-feedback">
                    Формат: example@domain.com
                    </div>
                </div>
                <div class="form-floating mb-3">
                    <input type="tel" name="phone" id="phone" placeholder="+7(999)999-99-99" class="form-control"
                        pattern="^(\+7|7|8)?[\s\-]?\(?[489][0-9]{2}\)?[\s\-]?[0-9]{3}[\s\-]?[0-9]{2}[\s\-]?[0-9]{2}$"
                        title="Формат: +7(953)260-54-67" required>
                    <label for="phone">Номер телефона</label>
                    <div id="nameHelpBlock" class="valid-feedback">

                    </div>
                    <div class="invalid-feedback">
                    Формат: +7(953)260-54-67
                    </div>
                </div>

                <div class="form-floating pb-3">
                    <input type="password" name="pwd1" id="pwd1" placeholder="Пароль" class="form-control"
                        pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$"
                        title="Пароль должен содержать минимум 6 символов, включая буквы и цифры" required>
                    <label for="pwd1">Пароль</label>
                    <div id="nameHelpBlock" class="valid-feedback">

                    </div>
                    <div class="invalid-feedback">
                    Пароль должен содержать минимум 6 символов, включая буквы и цифры
                    </div>
                </div>

                <div class="form-floating pb-3">
                    <input type="password" name="pwd2" id="pwd2" placeholder="Повторите пароль" class="form-control"
                        pattern="^(?=.*[A-Za-z])(?=.*\d)[A-Za-z\d]{6,}$"
                        title="Пароль должен содержать минимум 6 символов, включая буквы и цифры" required>
                    <label for="pwd2">Повторите пароль</label>
                    <div id="nameHelpBlock" class="valid-feedback">

                    </div>
                    <div class="invalid-feedback">
                    Пароль должен содержать минимум 6 символов, включая буквы и цифры
                    </div>
                </div>

                <div class="form-check pb-3">
                    <input class="form-check-input" type="checkbox" id="checkbox" required>
                    <label for="checkbox">Принимаю пользовательское соглашение</label>
                    <div class="invalid-feedback"></div>
                </div>

                <button type="submit" class="btn btn-primary fw-semibold w-100 text-uppercase">Зарегистрироваться</button>
                <p class="text-secondary pt-3">Уже есть аккаунт? <a class="fw-semibold text-primary" href="login.php">Войти</a></p>
            </form>
        </div>
    </div>

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-geWF76RCwLtnZ8qwWowPQNguL3RmwHVBC9FhGdlKrxdiJJigb/j/68SIy3Te4Bkz"
        crossorigin="anonymous"></script>
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