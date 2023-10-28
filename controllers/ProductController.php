<?php


//class ProductController {
//
//    public function actionView($id) {
//        //public function actionView() {
//        require_once (ROOT . '/views/product/view.php');
//        return true;
//    }
//}
//
//неработает:

class ProductController {

    public function actionView($productId) {

        // Список категорий для левого меню
        $categories = array();
        $categories = Category::getCategoriesList();

        // Получаем инфомрацию о товаре
        $products = array();
        $product = Product::getProductById($productId);

        require_once (ROOT . '/views/product/view.php');
        return true;
    }
}
