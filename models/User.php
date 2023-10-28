<?php

/**
 * Description of User
 *
 * @author Alexei
 */
class User {

    static $log = 'init';
    //static $logSessionUser;

    public static function register($name, $email, $password) {
        // Соединение с БД        
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'INSERT INTO user (name, email, password) VALUES (:name, :email, :password)';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    public static function checkName($name) {

        if (strlen($name) >= 2) {
            return true;
        }
        return false;
    }

    public static function checkPassword($password) {

        if (strlen($password) >= 6) {
            return true;
        }
        return false;
    }

    public static function checkEmail($email) {

        if (filter_var($email, FILTER_VALIDATE_EMAIL)) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет телефон: не меньше, чем 10 символов
     * @param string $phone <p>Телефон</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkPhone($phone) {
        if (strlen($phone) >= 10) {
            return true;
        }
        return false;
    }

    /**
     * Проверяет не занят ли email другим пользователем
     * @param type $email <p>E-mail</p>
     * @return boolean <p>Результат выполнения метода</p>
     */
    public static function checkEmailExists($email) {

        // Соединение с БД        
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT COUNT(*) FROM user WHERE email = :email';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_STR);
        $result->execute();

        if ($result->fetchColumn())
            return true;
        return false;
    }

    public static function edit($id, $name, $password) {

        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'UPDATE user SET name = :name, password = :password WHERE id = :id';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);
        $result->bindParam(':name', $name, PDO::PARAM_STR);
        $result->bindParam(':password', $password, PDO::PARAM_STR);
        return $result->execute();
    }

    /**
     * Проверяем существует ли пользователь с заданными $email и $password
     * @param string $email <p>E-mail</p>
     * @param string $password <p>Пароль</p>
     * @return mixed : integer user id or false
     */
    public static function checkUserData($email, $password) {

        $result = false;
        $sql = false;
        $user = false;

        // Соединение с БД
        self::setLog('testcheckUserData1 $email= ' . $email . '$password= ' . $password);

        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE email = :email AND password = :password';

        // Получение результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':email', $email, PDO::PARAM_INT);
        $result->bindParam(':password', $password, PDO::PARAM_INT);
        $result->execute();

        // Обращаемся к записи
        $user = $result->fetch();

        self::setLog(' $user[id]= ' . $user['id']);

        if ($user) {
            // Если запись существует, возвращаем id пользователя
            self::setLog('checkIfUser-ok');

            return $user['id'];
        }
        return false;
    }

    public static function setLog($var) {
        self::$log = self::$log . $var;
    }

    public static function getLog() {
        echo self::$log;
    }

    public static function auth($userId) {
        // Записываем идентификатор пользователя в сессию

        $_SESSION["user"] = $userId;

//        User::setLog('testFromAuth-Ok-$userId=' . $userId);
//        self::$logSessionUser = $_SESSION["user"];
//        self::setLog(' $logSessionUser144= ' . self::$logSessionUser);
    }

    /**
     * Возвращает идентификатор пользователя, если он авторизирован.<br/>
     * Иначе перенаправляет на страницу входа
     * @return string <p>Идентификатор пользователя</p>
     */
    public static function checkLogged() {
        // Если сессия есть, вернем идентификатор пользователя
        //self::setLog(' checkLogged ');

        if (isset($_SESSION['user'])) {

            return $_SESSION['user'];
        }
        header("Location: /user/login");
    }

    /**
     * Возвращает пользователя с указанным id
     * @param integer $id <p>id пользователя</p>
     * @return array <p>Массив с информацией о пользователе</p>
     */
    public static function getUserById($id) {
        // Соединение с БД
        $db = Db::getConnection();

        // Текст запроса к БД
        $sql = 'SELECT * FROM user WHERE id = :id';

        // Получение и возврат результатов. Используется подготовленный запрос
        $result = $db->prepare($sql);
        $result->bindParam(':id', $id, PDO::PARAM_INT);

        // Указываем, что хотим получить данные в виде массива
        $result->setFetchMode(PDO::FETCH_ASSOC);
        $result->execute();

        return $result->fetch();
    }

    public static function isGuest() {

        if (isset($_SESSION['user'])) {
            return false;
        } return true;
    }
}
