<?php

/**
 * Контроллер SiteController
 */
class SiteController {

    /**
     * Action для главной страницы
     */
    public function actionIndex() {

        // Список категорий для левого меню
        $categories = Category::getCategoriesList();

        // Список последних товаров
        $latestProducts = Product::getLatestProducts(6);

        // Список товаров для слайдера
        $sliderProducts = Product::getRecommendedProducts();

        // Подключаем вид
        require_once (ROOT . '/views/site/index.php');
        return true;
    }

    /**
     * Action для страницы "О магазине"
     */
    public function actionAbout() {

        // Подключаем вид
        require_once (ROOT . '/views/site/about.php');
        return true;
    }

    /**
     * Action для страницы "Контакты"
     */
    public function actionContact() {

        // Переменные для формы
        $userEmail = false;
        $userText = false;
        $result = false;

        // Если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $userEmail = $_POST['userEmail'];
            $userText = $_POST['userText'];

            // Флаг ошибок
            $errors = false;

            // Валидация полей
            if (!User::checkEmail($userEmail)) {
                $errors[] = 'Неправильный email';
            }

            // Если ошибок нет, отправляем письмо администратору 
            if ($errors == false) {

                $adminEmail = 'test@test.ru';
                $message = "Текст: {$userText}. От {$userEmail}";
                $subject = 'Тема письма';
                //$result = mail($adminEmail, $subject, $message);
                $result = true;
            }
        }

        // Подключаем вид
        require_once(ROOT . '/views/site/contact.php');
        return true;
    }
}
