<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$category = isset($_GET['category']) ? (int)$_GET['category'] : 0;
$subcategory = isset($_GET['subcategory']) ? (int)$_GET['subcategory'] : 0;
$search = isset($_GET['search']) ? mysqli_real_escape_string($conn, $_GET['search']) : '';
$sort = isset($_GET['sort']) ? $_GET['sort'] : 'newest';
$page = isset($_GET['page']) ? (int)$_GET['page'] : 1;

if ($page < 1) {
    $page = 1;
}

$products_per_page = 9;
$offset = ($page - 1) * $products_per_page;

$where = [];

if ($category > 0) {
    $where[] = "category_id = $category";
}

if ($subcategory > 0) {
    $where[] = "subcategory_id = $subcategory";
}

if (!empty($search)) {
    $where[] = "(name LIKE '%$search%' OR description LIKE '%$search%')";
}

$order_by = "id DESC";

if ($sort == 'price_asc') {
    $order_by = "price ASC";
} elseif ($sort == 'price_desc') {
    $order_by = "price DESC";
} elseif ($sort == 'name_asc') {
    $order_by = "name ASC";
}

$where_sql = "";

if (!empty($where)) {
    $where_sql = "WHERE " . implode(" AND ", $where);
}

$count_query = "SELECT COUNT(*) AS total FROM products $where_sql";
$count_result = mysqli_query($conn, $count_query);
$total_products = mysqli_fetch_assoc($count_result)['total'];

$total_pages = ceil($total_products / $products_per_page);

$query = "
    SELECT *
    FROM products
    $where_sql
    ORDER BY $order_by
    LIMIT $products_per_page OFFSET $offset
";

$result = mysqli_query($conn, $query);

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");

$subcategories = null;

if ($category > 0) {
    $subcategories = mysqli_query($conn, "
        SELECT *
        FROM subcategories
        WHERE category_id = $category
        ORDER BY name ASC
    ");
}

$query_params = "";

if (!empty($search)) {
    $query_params .= "&search=" . urlencode($search);
}

if ($category > 0) {
    $query_params .= "&category=" . $category;
}

if ($subcategory > 0) {
    $query_params .= "&subcategory=" . $subcategory;
}

if (!empty($sort)) {
    $query_params .= "&sort=" . urlencode($sort);
}
?>

<div class="container my-5">

    <h1 class="mb-4">Магазин</h1>

    <form method="get" action="shop.php" class="mb-4">
        <div class="row">

            <div class="col-md-6 mb-2">
                <input
                    type="text"
                    name="search"
                    class="form-control"
                    placeholder="Търси продукт..."
                    value="<?php echo htmlspecialchars($search); ?>"
                >
            </div>

            <div class="col-md-3 mb-2">
                <select name="sort" class="form-select">
                    <option value="newest" <?php if ($sort == 'newest') echo 'selected'; ?>>
                        Най-нови
                    </option>

                    <option value="price_asc" <?php if ($sort == 'price_asc') echo 'selected'; ?>>
                        Цена: ниска към висока
                    </option>

                    <option value="price_desc" <?php if ($sort == 'price_desc') echo 'selected'; ?>>
                        Цена: висока към ниска
                    </option>

                    <option value="name_asc" <?php if ($sort == 'name_asc') echo 'selected'; ?>>
                        Име: А-Я
                    </option>
                </select>
            </div>

            <?php if ($category > 0) { ?>
                <input
                    type="hidden"
                    name="category"
                    value="<?php echo $category; ?>"
                >
            <?php } ?>

            <?php if ($subcategory > 0) { ?>
                <input
                    type="hidden"
                    name="subcategory"
                    value="<?php echo $subcategory; ?>"
                >
            <?php } ?>

            <div class="col-md-3 mb-2">
                <button type="submit" class="btn btn-success w-100">
                    Приложи
                </button>
            </div>

        </div>
    </form>

    <div class="mb-3">

        <a href="shop.php" class="btn <?php echo $category == 0 ? 'btn-dark' : 'btn-outline-dark'; ?> me-2 mb-2">
            Всички
        </a>

        <?php while ($cat = mysqli_fetch_assoc($categories)) { ?>

            <a
                href="shop.php?category=<?php echo $cat['id']; ?>&sort=<?php echo $sort; ?>"
                class="btn <?php echo $category == $cat['id'] ? 'btn-dark' : 'btn-outline-primary'; ?> me-2 mb-2"
            >
                <?php echo $cat['name']; ?>
            </a>

        <?php } ?>

    </div>

    <?php if ($category > 0 && $subcategories && mysqli_num_rows($subcategories) > 0) { ?>

        <div class="mb-4">

            <a
                href="shop.php?category=<?php echo $category; ?>&sort=<?php echo $sort; ?>"
                class="btn <?php echo $subcategory == 0 ? 'btn-success' : 'btn-outline-success'; ?> me-2 mb-2"
            >
                Всички от категорията
            </a>

            <?php while ($subcat = mysqli_fetch_assoc($subcategories)) { ?>

                <a
                    href="shop.php?category=<?php echo $category; ?>&subcategory=<?php echo $subcat['id']; ?>&sort=<?php echo $sort; ?>"
                    class="btn <?php echo $subcategory == $subcat['id'] ? 'btn-success' : 'btn-outline-success'; ?> me-2 mb-2"
                >
                    <?php echo $subcat['name']; ?>
                </a>

            <?php } ?>

        </div>

    <?php } ?>

    <div class="row">

        <?php if (mysqli_num_rows($result) == 0) { ?>

            <div class="col-12">
                <div class="alert alert-warning">
                    Няма намерени продукти.
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

                        <h3 class="product-title">
                            <?php echo $product['name']; ?>
                        </h3>

                        <p class="product-description">
                            <?php echo $product['description']; ?>
                        </p>

                        <p class="product-price">
                            <?php echo number_format($product['price'], 2); ?> €
                        </p>

                        <?php if ($product['stock'] > 0) { ?>

                            <p>
                                Наличност: <?php echo $product['stock']; ?> бр.
                            </p>

                        <?php } else { ?>

                            <p class="text-danger fw-bold">
                                Изчерпано
                            </p>

                        <?php } ?>

                        <a
                            href="product.php?id=<?php echo $product['id']; ?>"
                            class="btn btn-outline-dark mt-auto mb-2"
                        >
                            Виж повече
                        </a>

                        <?php if ($product['stock'] > 0) { ?>

                            <a
                                href="add_to_cart.php?id=<?php echo $product['id']; ?>"
                                class="btn btn-success ajax-add-to-cart"
                                data-id="<?php echo $product['id']; ?>"
                            >
                                Добави в количката
                            </a>

                        <?php } else { ?>

                            <button class="btn btn-secondary" disabled>
                                Изчерпано
                            </button>

                        <?php } ?>

                    </div>

                </div>

            </div>

        <?php } ?>

    </div>

    <?php if ($total_pages > 1) { ?>

        <nav class="mt-4">
            <ul class="pagination justify-content-center">

                <?php if ($page > 1) { ?>

                    <li class="page-item">
                        <a
                            class="page-link"
                            href="shop.php?page=<?php echo $page - 1; ?><?php echo $query_params; ?>"
                        >
                            Назад
                        </a>
                    </li>

                <?php } ?>

                <?php for ($i = 1; $i <= $total_pages; $i++) { ?>

                    <li class="page-item <?php if ($i == $page) echo 'active'; ?>">
                        <a
                            class="page-link"
                            href="shop.php?page=<?php echo $i; ?><?php echo $query_params; ?>"
                        >
                            <?php echo $i; ?>
                        </a>
                    </li>

                <?php } ?>

                <?php if ($page < $total_pages) { ?>

                    <li class="page-item">
                        <a
                            class="page-link"
                            href="shop.php?page=<?php echo $page + 1; ?><?php echo $query_params; ?>"
                        >
                            Напред
                        </a>
                    </li>

                <?php } ?>

            </ul>
        </nav>

    <?php } ?>

</div>

<?php
require_once 'includes/footer.php';
?>