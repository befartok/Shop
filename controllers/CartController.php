<?php

/**
 * Description of CartController
 *
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
//        print_r(' $referrer= ');
//        var_dump($referrer);

        header("Location: $referrer");
        //return true;
    }

//    public function actionAddAjax($id) {
//        
//        User::setLog(' actionAddAjax ');
//        //Добавляем товар в корзину
//        echo Cart::addProduct($id);
//        return true;
//    }

    /**
     * Action для удаления товара из корзины
     */
    public function actionDelete($id) {
        // Удаляем заданный товар из корзины
        Cart::deleteProduct($id);

        // Возвращаем пользователя в корзину
        header("Location: /cart");
    }

    public function actionIndex() {

        //список категорий в левом меню   

        $categories = array();
        $categories = Category::getCategoriesList();

        $productsInCart = false;

        $productsInCart = Cart::getProducts();

        if ($productsInCart) {

            $productsIds = array_keys($productsInCart);
            $products = Product::getProductsByIds($productsIds);

            $totalPrice = Cart::getTotalPrice($products);
        }
        require_once (ROOT . '/views/cart/index.php');

        return true;
    }

    public function actionCheckout() {

        //список категорий в левом меню   
        $categories = array();
        $categories = Category::getCategoriesList();

        // Получием данные из корзины      
        $productsInCart = Cart::getProducts();

        // Находим общую стоимость
        $productsIds = array_keys($productsInCart);
        $products = Product::getProductsByIds($productsIds);
        $totalPrice = Cart::getTotalPrice($products);

        // Количество товаров
        $totalQuantity = Cart::countItems();

        //статус успешного оформления заказа
        $result = false;

        //фома отправлена     
        if (isset($_POST['submit'])) {

            //считываем данные формы
            $userName = $_POST['userName'];
            $userPhone = $_POST['userPhone'];
            $userComment = $_POST['userComment'];

            //валидация полей
            $errors = false;

            if (!User::checkName($userName)) {
                $errors[] = 'Неправильное имя';
            }

            if (!User::checkPhone($userPhone)) {
                $errors[] = 'Неправильный телефон';
            }

            //форма заполнена корректно?
            if ($errors == false) {
                //форма заполнена корректно - да
                //собираем информацию о заказе
                $productsInCart = Cart::getProducts();
                if (User::isGuest()) {
                    $userId = false;
                } else {
                    $userId = User::checkLogged();
                }

                //сохраняем заказ в базе данных
                $result = Order::save($userName, $userPhone, $userComment, $userId, $productsInCart);
                


                if ($result) {
                    //оповещаем администратора о новом заказе
                    $adminEmail = 'frwrd@mail.ru';
                    $message = 'новый заказ';
                    $subject = 'новый заказ';
                    //mail($subject, $message, $adminEmail);

                    print_r($adminEmail,$subject );
                    
                    //очищаем корзину
                    Cart::clear();
                }
            } else {
                //форма заполнена корректно - нет
                //получаем итоги из корзины
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();
            }
        } else {
            //форма не отправлена
            //получаем данные из корзины

            $productsInCart = Cart::getProducts();

            //в корзине есть товары?
            if ($productsInCart == false) {
                //товаров нет, отправляем пользователя на главную страницу
                header("Location: /");
            } else {
                //товары есть в корзине
                //получаем итоги из корзины
                $productsIds = array_keys($productsInCart);
                $products = Product::getProductsByIds($productsIds);
                $totalPrice = Cart::getTotalPrice($products);
                $totalQuantity = Cart::countItems();

                $userName = false;
                $userPhone = false;
                $userComment = false;

                //пользователь авторизован?
                if (User::isGuest()) {
                    //нет, значения формы пустые
                } else {
                    //да, авторизован, получаем информацию о пользователе из БД
                    $userId = User::checkLogged();
                    $user = User::getUserById($userId);
                    //подставляем в форму
                    $userName = $user['name'];
                }
            }
        }
        require_once (ROOT . '/views/cart/checkout.php');
        return true;
    }
}
