<?php include ROOT . '/views/layouts/header_admin.php'; ?>

<section>
    <div class="container">
        <div class="row">
            <br/>

            <div class="breadcrumbs">
                <ol class="breadcrumb">
                    <li><a href="/admin">Админпанель</a></li>
                    <li class="active">Управление товарами</li>
                </ol>
            </div>

            <a href="/admin/product/create" class="btn btn-default back"><i class="fa fa-plus"></i>Добавить товар</a>

            <h4>Список товаров</h4>

            <br/>

            <table class="table-bordered table-striped table">
                <tr>
                    <th>ID товара</th>
                    <th>Артикул</th>
                    <th>Название товара</th>
                    <th>Цена</th>
                    <th></th>
                    <th></th>
                </tr>
                <?php foreach ($productList as $product): ?>
                    <tr>
                        <th><?php echo $product['id']; ?></th>
                        <th><?php echo $product['code']; ?></th>
                        <th><?php echo $product['name']; ?></th>
                        <th><?php echo $product['price']; ?></th>
                        <th><a href="/admin/product/update/<?php echo $product['id']; ?>" title = "Редактировать"><i class="fa fa-pencil-square"></i></a></th>
                        <th><a href="/admin/product/delete/<?php echo $product['id']; ?>" title = "Удалить"><i class="fa fa-times"></i></a></th>

                    </tr>

                <?php endforeach; ?>


            </table>

        </div>
    </div>
</section>

<?php include ROOT . '/views/layouts/footer_admin.php'; ?>

