<?php


class AdminController extends AdminBase{

    public function actionIndex() {
        
        //проверка прав доступа
        self::checkAdmin();
        
        //подключение вида
        require_once (ROOT . '/views/admin/index.php');
        return true;
    }


}