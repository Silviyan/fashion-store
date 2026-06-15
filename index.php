<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$query = "
    SELECT
        products.*,
        SUM(order_items.quantity) AS sold_count
    FROM products
    INNER JOIN order_items ON products.id = order_items.product_id
    GROUP BY products.id
    ORDER BY sold_count DESC
    LIMIT 6
";

$result = mysqli_query($conn, $query);
?>

<section class="hero text-center">

    <div class="container">

        <h1 class="display-3 fw-bold mb-4">
            НОВА КОЛЕКЦИЯ 2026
        </h1>

        <p class="lead mb-4">
            Модерни дрехи, обувки и аксесоари за всеки стил.
        </p>

        <a href="shop.php" class="btn btn-success btn-lg px-5">
            Пазарувай сега
        </a>

    </div>

</section>

<section class="container my-5">

    <div class="d-flex justify-content-between align-items-center mb-4">

        <h2 class="mb-0">
            Най-продавани продукти
        </h2>

        <a href="shop.php" class="btn btn-outline-dark">
            Виж всички
        </a>

    </div>

    <div class="row">

        <?php if (mysqli_num_rows($result) == 0) { ?>

            <div class="col-12">
                <div class="alert alert-info">
                    Все още няма продадени продукти.
                </div>
            </div>

        <?php } ?>

        <?php while ($product = mysqli_fetch_assoc($result)) { ?>

            <div class="col-md-4 mb-4">

                <div class="card product-card h-100">

                    <?php if (!empty($product['image'])) { ?>

                        <img
                            src="assets/images/<?php echo $product['image']; ?>"
                            class="card-img-top product-img"
                            alt="<?php echo $product['name']; ?>"
                        >

                    <?php } ?>

                    <div class="card-body d-flex flex-column">

                        <h3 class="card-title product-title">
                            <?php echo $product['name']; ?>
                        </h3>

                        <p class="card-text product-description">
                            <?php echo $product['description']; ?>
                        </p>

                        <p class="product-price">
                            <?php echo number_format($product['price'], 2); ?> €
                        </p>

                        <p class="text-muted">
                            Наличност: <?php echo $product['stock']; ?> бр.
                        </p>

                        <p class="text-success fw-bold">
                            Продадени: <?php echo $product['sold_count']; ?> бр.
                        </p>

                        <a
                            href="product.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-success mt-auto"
                        >
                            Виж повече
                        </a>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

</section>

<?php
require_once 'includes/footer.php';
?>