<?php

/**
 * Description of CartController
 * Контроллер Корзина
 * @author Alexei
 */
class CartController {

    /**
     * Action для добавления товара в корзину<br/>
     * @param integer $id <p>id товара</p>
     */
    public function actionAdd($id) {

        // Добавляем товар в корзину
        Cart::addProduct($id);

        // Возвращаем пользователя на страницу с которой он пришел
        $referrer = $_SERVER["HTTP_REFERER"];
        header("Location: $referrer");
    }

    /**
     * Action для удаления товара из корзины
     * @param integer $id <p>id товара</p>
     */
    public function actionDelete($id) {

        // Удаляем заданный товар из корзины
        Cart::deleteProduct($id);

        // Возвращаем пользователя в корзину
        header("Location: /cart");
    }

    /**
     * Action для страницы "Корзина"
     */
    public function actionIndex() {

        //Список категорий в левом меню   
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        // Получим идентификаторы и количество товаров в корзине
        $productsInCart = Cart::getProducts();

        // Если в корзине есть товары
        if ($productsInCart) {

            // Получаем массив с идентификаторами товаров
            $productsIds = array_keys($productsInCart);

            // Получаем массив с полной информацией о товарах
            $products = Product::getProductsByIds($productsIds);

            // Получаем общую стоимость товаров
            $totalPrice = Cart::getTotalPrice($products);
        }

        // Подключение вида
        require_once (ROOT . '/views/cart/index.php');
        return true;
    }

    /**
     * Action для страницы "Оформление покупки"
     */
    public function actionCheckout() {

        //Список категорий в левом меню   
        $categories = Category::getCategoriesList();

        // Получием данные из корзины      
        $productsInCart = Cart::getProducts();

        // Проверка наличия товаров в корзине
        if ($productsInCart == false) {
            //если товаров нет, возвращение на главную страницу
            header("Location: /");
        }

        // Находим общую стоимость
        $productsIds = array_keys($productsInCart);
        $products = Product::getProductsByIds($productsIds);
        $totalPrice = Cart::getTotalPrice($products);

        // Количество товаров
        $totalQuantity = Cart::countItems();

        //Статус успешного оформления заказа
        $result = false;

        //Если фома отправлена, считываем данные формы   
        if (isset($_POST['submit'])) {

            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkName($userName)) {
                $errors[] = 'Неправильное имя';
            }

            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Неправильный телефон';
            }

            // Если ошибок нет, сохраняем заказ в базе данных
            if ($errors == false) {

                //Собираем информацию о заказе
                $productsInCart = Cart::getProducts();

                //Проверка гость ли пользователь
                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }

                //Сохраняем заказ в базе данных
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);

                //Если заказ сохранен, оповещаем администратора о новом заказе 
                if ($result) {

                    $adminEmail = '***@mail.ru';
                    $message = 'новый заказ';
                    $subject = 'новый заказ';
                    //mail($subject, $message, $adminEmail);
                    //очищаем корзину
                    Cart::clear();
                }
            } else {
                //форма заполнена корректно - нет, получаем итоги из корзины
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }
        } else {
            //форма не отправлена, получаем данные из корзины

            $productsInCart = Cart::getProducts();

            // Проверка наличия товаров в корзине
            if ($productsInCart == false) {
                //если товаров нет, возвращение на главную страницу
                header("Location: /");
            } else {
                //если товары есть в корзине, получаем итоги из корзины
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();

                $userName = false;
                $userPhone = false;
                $userComment = false;

                //Если пользователь гость, то значения формы пустые
                if (User::isGuest()) {
                    
                } else {
                    //если авторизован, получаем информацию о пользователе из БД
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    //подставляем в форму
                    $userName = $user['name'];
                }
            }
        }

        // Подключаем вид
        require_once (ROOT . '/views/cart/checkout.php');
        return true;
    }
}
