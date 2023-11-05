<?php

/**
 * Контроллер AdminOrderController
 * Управление заказами в админпанели
 */
class AdminOrderController extends AdminBase {

    /**
     * Action для страницы "Управление заказами"
     */
    public function actionIndex() {
        // Проверка доступа
        self::checkAdmin();

        // Получаем список заказов
        $ordersList = Order::getOrdersList();

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/index.php');
        return true;
    }

    /**
     * Action для страницы "Редактировать категорию"
     */
    public function actionUpdate($id) {
        // Проверка доступа
        self::checkAdmin();

        // Получаем данные о конкретном заказе
        $order = Order::getOrderById($id);

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена, получаем данные из формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];
            $date = $_POST['date'];
            $status = $_POST['status'];

            // Сохраняем изменения
            Order::updateOrderById($id, $userName, $userPhone, $userComment, $date, $status);

            // Перенаправляем пользователя на страницу управлениями заказами
            header("Location: /admin/order/view/$id");
        }

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/update.php');
        return true;
    }

    /**
     * Action для страницы "Удалить заказ"
     */
    public function actionDelete($id) {

        // Проверка доступа
        self::checkAdmin();

        // Обработка формы
        if (isset($_POST['submit'])) {
            // Если форма отправлена, удаляем категорию
            Order::deleteOrderById($id);

            // Перенаправляем пользователя на страницу управлениями товарами
            header("Location: /admin/order");
        }

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/delete.php');
        return true;
    }

    /**
     * Action для страницы "Просмотр заказа"
     */
    public function actionView($id) {
        
        //проверка доступа
        self::checkAdmin();

        //получение данных о заказе
        $order = Order::getOrderById($id);

        //получение массива с идентификаторами и количеством товаров
        $productsQuantity = json_decode($order['products'], true);

        //получение массива с идентификаторами товаров
        $productsIds = array_keys($productsQuantity);

        //получение списка товаров и заказов
        $products = Product::getProductsByIds($productsIds);

        // Подключаем вид
        require_once(ROOT . '/views/admin_order/view.php');
        return true;
    }
}
