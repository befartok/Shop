<?php

/**
 * Контроллер CabinetController
 * Кабинет пользователя
 */
class CabinetController {

    /**
     * Action для страницы "Кабинет пользователя"
     */
    public function actionIndex() {

        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();

        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);

        // Подключаем вид
        require_once(ROOT . '/views/cabinet/index.php');
        return true;
    }

    /**
     * Action для страницы "Редактирование данных пользователя"
     */
    public function actionEdit() {

        // Получаем идентификатор пользователя из сессии
        $userId = User::checkLogged();

        // Получаем информацию о пользователе из БД
        $user = User::getUserById($userId);

        // Заполняем переменные для полей формы
        $name = $user['name'];
        $password = $user['password'];

        // Флаг результата
        $result = false;

        // Если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $name = $_POST['name'];
            $password = $_POST['password'];

            // Флаг ошибок
            $errors = false;

            //валидация полей
            if (!User::checkName($name)) {
                $errors[] = 'Имя не должно быть короче 2-х символов';
            }

            if (!User::checkPassword($password)) {
                $errors[] = 'Пароль не должен быть короче 6-ти символов';
            }

            // Если ошибок нет, сохраняем изменения профиля
            if ($errors == false) {
                $result = User::edit($userId, $name, $password);
            }
        }

        // Подключение вида
        require_once(ROOT . '/views/cabinet/edit.php');
        return true;
    }
}
