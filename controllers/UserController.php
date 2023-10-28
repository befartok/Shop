<?php

/**
 * Description of UserController
 *
 * @author Alexei
 */
class UserController {

    public function actionRegister() {

        $name = '';
        $email = '';
        $password = '';
        $result = false;

        if (isset($_POST['submit'])) {

            // Если форма отправлена 
            // Получаем данные из формы
            $name = $_POST['name'];
            $email = $_POST['email'];
            $password = $_POST['password'];

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

        // Обработка формы
        if (isset($_POST['submit'])) {  //не срабатывает!
            // Если форма отправлена 
            // Получаем данные из формы
            $email = $_POST['email'];
            $password = $_POST['password'];

            User::setLog(' testFromActionLogin2 ');

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkEmail($email)) {
                $errors[] = 'Неправильный email';
            }
            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }
            ////дает ошибку, но проходит валидацию пользователя проверить
            // Проверяем существует ли пользователь
            $userId = User::checkUserData($email, $password);

            User::setLog(' $userId86= ' . $userId);

            if ($userId == false) {
                // Если данные неправильные - показываем ошибку
                $errors[] = 'Неправильные данные для входа на сайт';
            } else if ($errors == false) {
                // Если данные правильные, запоминаем пользователя (сессия)
                User::auth($userId);

                User::setLog(' testFromActionLogin2-ok');

                // Перенаправляем пользователя в закрытую часть - кабинет 
                header("Location: /cabinet");
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/user/login.php');
        return true;
    }

    /**
     * Удаляем данные о пользователе из сессии
     */
    public function actionLogout() {
        // Стартуем сессию
        //session_start();

        // Удаляем информацию о пользователе из сессии
        unset($_SESSION["user"]);

        // Перенаправляем пользователя на главную страницу
        header("Location: /");
    }
}
