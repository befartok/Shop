<?php

/**
 * Контроллер UserController
 */
class UserController {

    /**
     * Action для страницы "Регистрация"
     */
    public function actionRegister() {

        // Переменные для формы
        $name = '';
        $email = '';
        $password = '';
        $result = false;

        // Если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            //валидация полей
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный $email ';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            if (User::checkEmailExists($email)) {
                $errors[] = 'Такой email уже используется';
            }

            if ($errors == false) {
                $result = User::register($name, $email, $password);
            }
        }

        // Подключаем вид
        require_once (ROOT . '/views/user/register.php');
        return true;
    }

    /**
     * Action для страницы "Вход на сайт"
     */
    public function actionLogin() {
        // Переменные для формы
        $email = false;
        $password = false;

        User::setLog(' testFromActionLogin1 ');

        // Если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $email = $_POST['email'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            // Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);

            // Если данные неправильные - показываем ошибку
            if ($userId == false) {
                $errors[] = 'Неправильные данные для входа на сайт';
            } else if ($errors == false) {
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($userId);

                // Перенаправляем пользователя в кабинет 
                header("Location: /cabinet");
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * Action выхода из учетной записи
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout() {
        
        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);

        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }
}
