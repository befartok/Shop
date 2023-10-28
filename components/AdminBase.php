<?php

/**
 * Description of AdminBase
 *
 * @author Alexei
 */
abstract class AdminBase {

    //проверка прав доступа админа
    public static function checkAdmin() {
        
        //проверка авторизации пользователя
        $userId = User::checkLogged();
        
        //получение id пользователя
        $user = User::getUserById($userId);
        
        //проверка пользователя на роль администратора
        if ($user['role']=='admin') {
            return true;
        }
        //иначе завершение работы с сообщением о закрытом доступе
        die('Acsess denied');
    }
}
