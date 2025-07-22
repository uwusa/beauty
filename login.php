<!doctype html>
<html lang="ru">

<head>
    <title>Авторизация</title>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no" />
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous" />
    <link rel="stylesheet" href="css/style.css" type="text/css">
</head>

<body class="bg-light">
    <div class="container-fluid d-flex h-100 align-items-center w-100 p-5 text-center">
        <div class="col d-none d-xl-flex justify-content-end mx-auto me-5">
            <img src="media/2.png" alt="photo" width="700" style="pointer-events: none;">
        </div>
        <div class="col d-flex">
            <form action="entry.php" method="post" class="d-flex flex-column mx-auto w-75 mv-100">
                <h2 class="pb-3">Авторизация</h2>
                
                <!-- Вывод сообщения об ошибке -->
                <?php
                session_start();
                if (isset($_SESSION['error'])) {
                    echo '<div class="alert alert-danger" role="alert">' . $_SESSION['error'] . '</div>';
                    unset($_SESSION['error']); // Удаляем сообщение после отображения
                }
                ?>

                <div class="input-group mb-3">
                    <span class="input-group-text" id="basic-adon1">@</span>
                    <input type="text" name="login" class="form-control w-25" placeholder="Введите логин"
                        aria-label="Username" aria-describedby="basic-adon1" required>
                </div>

                <div class="form-floating pb-3">
                    <input type="password" name="pwd" id="pwd" placeholder="Пароль" class="form-control" required>
                    <label for="pwd">Введите пароль</label>
                </div>
                <button type="submit" class="btn btn-primary fw-semibold w-100 text-uppercase">Войти</button>
                <p class="text-secondary pt-3">Нет аккаунта? <a href="index.php" class="fw-semibold text-primary">Зарегистрироваться</a></p>
            </form>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.min.js"
        integrity="sha384-BBtl+eGJRgqQAUMxJ7pMwbEyER4l1g+O15P+16Ep7Q9Q+zqX6gSbd85u4mG4QzX+" crossorigin="anonymous">
    </script>
</body>

</html>