<?php

/**
 * Контроллер AdminController
 * Главная страница в админпанели
 */
class AdminController extends AdminBase {

    /**
     * Action для стартовой страницы "Панель администратора"
     */
    public function actionIndex() {

        //проверка прав доступа
        self::checkAdmin();

        //подключение вида
        require_once (ROOT . '/views/admin/index.php');
        return true;
    }
}
