<?php

/**
 * Description of AdminProductController
 *
 * @author Alexei
 */
class AdminProductController extends AdminBase {

    public function actionIndex() {
        //проверка доступа
        self::checkAdmin();

        //получение списка товаров
        $productList = Product::getProductsList();

        //подключение вида
        require_once (ROOT . '/views/admin_product/index.php');
        return true;
    }

    //удаление товара
    public function actionDelete($id) {

        //проверка доступа
        self::checkAdmin();

        //если форма отправлена, удаляем товар
        if (isset($_POST['submit'])) {
            Product::deleteProductById($id);

            //перенаправление на страницу управления товарами
            header("Location: /admin/product");
        }


        //подключение вида
        require_once (ROOT . '/views/admin_product/delete.php');
        return true;
    }

    public function actionCreate() {

        //проверка доступа
        self::checkAdmin();

        //получение списка категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();

        //если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            //флаг ошибок в форме
            $errors = false;

            //валидация полей
            if (!isset($options['name']) || empty($options['name'])) {
                $errors = "Заполните поля!";
            }

            //добавляем новый товар, если нет ошибок
            if ($errors == false) {

                $id = Product::createProduct($options);

                //проверка, загружалось ли  через форму изображение
                echo '<pre>';
                print_r($_FILES["image"]);
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    //перенос его в нужную папку с новым имеем
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            }

            //перенаправление на страницууправления товарами
            header("Location: /admin/product");
        }
        //подключение вида
        require_once(ROOT . '/views/admin_product/create.php');
        return true;
    }

    public function actionUpdate($id) {

        //проверка доступа
        self::checkAdmin();

        //получение списка категорий для выпадающего списка
        $categoriesList = Category::getCategoriesListAdmin();

        //получаем данные о конкретном товаре
        $product = Product::getProductById($id);

        //если форма отправлена, получаем данные из формы
        if (isset($_POST['submit'])) {

            $options['name'] = $_POST['name'];
            $options['code'] = $_POST['code'];
            $options['price'] = $_POST['price'];
            $options['category_id'] = $_POST['category_id'];
            $options['brand'] = $_POST['brand'];
            $options['availability'] = $_POST['availability'];
            $options['description'] = $_POST['description'];
            $options['is_new'] = $_POST['is_new'];
            $options['is_recommended'] = $_POST['is_recommended'];
            $options['status'] = $_POST['status'];

            //сохраняем изменения
            if (Product::updateProductById($id, $options)) {

                //если запись сохранена, проверка, загружалось ли  через форму изображение
                if (is_uploaded_file($_FILES["image"]["tmp_name"])) {
                    //перенос его в нужную папку с новым имеем
                    move_uploaded_file($_FILES["image"]["tmp_name"], $_SERVER['DOCUMENT_ROOT'] . "/upload/images/products/{$id}.jpg");
                }
            }

            //перенаправление на страницууправления товарами
            header("Location: /admin/product");
        }
        //подключение вида
        require_once(ROOT . '/views/admin_product/update.php');
        return true;
    }
}
