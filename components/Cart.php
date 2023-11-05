<?php

/**
 * Класс для работы с корзиной
 */
class Cart {

    /**
     * Добавление товаров в корзину(сессию)
     * @param int $id <p>id товара</p>
     * @return int <p>Количество товаров в корзине</p>
     */
    public static function addProduct($id) {

        // Пустой массив для товаров в корзине
        $productsInCart = array();

        // Если в корзине уже есть товары (они хранятся в сессии)
        if (isset($_SESSION['products'])) {
            // То заполним наш массив товарами
            $productsInCart = $_SESSION['products'];
        }

        // Проверяем есть ли уже такой товар в корзине 
        if (array_key_exists($id, $productsInCart)) {
            // Если такой товар есть в корзине, но был добавлен еще раз, увеличим количество на 1
            $productsInCart[$id]++;
        } else {
            // Если нет, добавляем id нового товара в корзину с количеством 1
            $productsInCart[$id] = 1;
        }

        // Записываем массив с товарами в сессию
        $_SESSION['products'] = $productsInCart;

        // Возвращаем количество товаров в корзине
        return self::countItems();
    }

    /**
     * Удаляет товар с указанным id из корзины
     * @param int $id <p>id товара</p>
     */
    public static function deleteProduct($id) {

        // Получаем массив с идентификаторами и количеством товаров в корзине
        $productsInCart = self::getProducts();

        // Удаляем из массива элемент с указанным id
        unset($productsInCart[$id]);

        // Записываем массив товаров с удаленным элементом в сессию
        $_SESSION['products'] = $productsInCart;
    }

    /**
     * Подсчет количество товаров в корзине 
     * @return int <p>Количество товаров в корзине</p>
     */
    public static function countItems() {

        // Проверка наличия товаров в корзине
        if (isset($_SESSION['products'])) {
            // Если массив с товарами есть, подсчитаем и вернем их количество
            $count = 0;
            foreach ($_SESSION['products'] as $id => $quantity) {
                $count = $count + $quantity;
            }
            return $count;
        } else {
            // Если товаров нет, вернем 0
            return 0;
        }
    }

    /**
     * Возвращает массив с идентификаторами и количеством товаров в корзине
     */
    public static function getProducts() {
        if (isset($_SESSION['products'])) {
            return $_SESSION['products'];
        }
        return false;
    }

    /**
     * Получаем общую стоимость переданных товаров
     * @param array $products <p>Массив с информацией о товарах</p>
     * @return int <p>Общая стоимость</p>
     */
    public static function getTotalPrice($products) {

        $productsInCart = self::getProducts();

        $total = 0;

        if ($productsInCart) {
            foreach ($products as $item) {
                $total += $item['price'] * $productsInCart[$item['id']];
            }
        }
        return $total;
    }

    /**
     * Очищение корзины
     */
    public static function clear() {
        if (isset($_SESSION['products'])) {
            unset($_SESSION['products']);
        }
    }
}
