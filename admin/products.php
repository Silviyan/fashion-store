<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = "
    SELECT 
        products.*,
        categories.name AS category_name,
        subcategories.name AS subcategory_name
    FROM products
    LEFT JOIN categories ON products.category_id = categories.id
    LEFT JOIN subcategories ON products.subcategory_id = subcategories.id
    ORDER BY products.id DESC
";

$result = mysqli_query($conn, $query);
?>

<div class="container my-5">

    <h1 class="mb-4">Управление на продукти</h1>

    <a href="add_product.php" class="btn btn-success mb-3">
        Добави продукт
    </a>

    <table class="table table-bordered table-striped align-middle">

        <thead>
            <tr>
                <th>ID</th>
                <th>Снимка</th>
                <th>Име</th>
                <th>Категория</th>
                <th>Подкатегория</th>
                <th>Цена</th>
                <th>Наличност</th>
                <th>Действия</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($product = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td><?php echo $product['id']; ?></td>

                    <td>
                        <?php if (!empty($product['image'])) { ?>
                            <img
                                src="../assets/images/<?php echo $product['image']; ?>"
                                width="80"
                                style="border-radius: 8px; object-fit: cover;"
                            >
                        <?php } ?>
                    </td>

                    <td><?php echo $product['name']; ?></td>

                    <td>
                        <?php echo $product['category_name']; ?>
                    </td>

                    <td>
                        <?php echo $product['subcategory_name']; ?>
                    </td>

                    <td>
                        <?php echo number_format($product['price'], 2); ?> €
                    </td>

                    <td><?php echo $product['stock']; ?></td>

                    <td>
                        <a href="edit_product.php?id=<?php echo $product['id']; ?>" class="btn btn-warning btn-sm">
                            Редакция
                        </a>

                        <a
                            href="delete_product.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-danger btn-sm"
                            onclick="return confirm('Сигурни ли сте, че искате да изтриете този продукт?');"
                        >
                            Изтриване
                        </a>
                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

<?php
require_once '../includes/footer.php';
?>