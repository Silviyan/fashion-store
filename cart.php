<?php
session_start();
require_once 'includes/db.php';
require_once 'includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Количка</h1>

    <?php if (empty($_SESSION['cart'])) { ?>

        <p>Количката е празна.</p>

        <a href="shop.php" class="btn btn-primary">
            Към магазина
        </a>

    <?php } else { ?>

        <table class="table table-bordered align-middle">
            <thead>
                <tr>
                    <th>Снимка</th>
                    <th>Продукт</th>
                    <th>Количество</th>
                    <th>Цена</th>
                    <th>Общо</th>
                    <th>Действие</th>
                </tr>
            </thead>

            <tbody>
                <?php
                $total = 0;

                foreach ($_SESSION['cart'] as $product_id => $quantity) {
                    $product_id = (int)$product_id;

                    $query = "SELECT * FROM products WHERE id = $product_id";
                    $result = mysqli_query($conn, $query);
                    $product = mysqli_fetch_assoc($result);

                    if ($product) {
                        $subtotal = $product['price'] * $quantity;
                        $total += $subtotal;
                ?>

                    <tr>
                        <td>
                            <?php if (!empty($product['image'])) { ?>
                                <img
                                    src="assets/images/<?php echo $product['image']; ?>"
                                    alt="<?php echo $product['name']; ?>"
                                    style="width:70px; height:70px; object-fit:cover; border-radius:10px;"
                                >
                            <?php } ?>
                        </td>

                        <td>
                            <?php echo $product['name']; ?>
                        </td>

                        <td>
                            <?php echo $quantity; ?>
                        </td>

                        <td>
                            <?php echo number_format($product['price'], 2); ?> €
                        </td>

                        <td>
                            <?php echo number_format($subtotal, 2); ?> €
                        </td>

                        <td>
                            <a
                                href="remove_from_cart.php?id=<?php echo $product['id']; ?>"
                                class="btn btn-danger btn-sm"
                            >
                                Премахни
                            </a>
                        </td>
                    </tr>

                <?php
                    }
                }
                ?>
            </tbody>
        </table>

        <h3 class="mb-3">
            Обща сума: <?php echo number_format($total, 2); ?> €
        </h3>

        <a href="shop.php" class="btn btn-secondary">
            Продължи пазаруването
        </a>

        <a href="checkout.php" class="btn btn-success">
            Поръчай
        </a>

    <?php } ?>
</div>

<?php
require_once 'includes/footer.php';
?>