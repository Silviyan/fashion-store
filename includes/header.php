<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

$cart_count = 0;
$cart_items = [];
$cart_total = 0;

if (isset($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $cart_count += $quantity;
    }
}

if (isset($_SESSION['cart']) && !empty($_SESSION['cart'])) {
    require_once $_SERVER['DOCUMENT_ROOT'] . '/fashion-store/includes/db.php';

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;

        $query = "SELECT * FROM products WHERE id = $product_id";
        $result = mysqli_query($conn, $query);

        if ($product = mysqli_fetch_assoc($result)) {
            $subtotal = $product['price'] * $quantity;
            $cart_total += $subtotal;

            $cart_items[] = [
                'id' => $product['id'],
                'name' => $product['name'],
                'price' => $product['price'],
                'quantity' => $quantity,
                'image' => $product['image']
            ];
        }
    }
}
?>

<!DOCTYPE html>
<html lang="bg">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Fashion Store</title>

    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.7/dist/css/bootstrap.min.css" rel="stylesheet">
    <link rel="stylesheet" href="/fashion-store/assets/css/style.css">
</head>

<body>

<nav class="navbar navbar-expand-lg navbar-dark bg-dark">
    <div class="container">

        <a class="navbar-brand" href="/fashion-store/index.php">
            Fashion Store
        </a>

        <button
            class="navbar-toggler"
            type="button"
            data-bs-toggle="collapse"
            data-bs-target="#navbarNav"
        >
            <span class="navbar-toggler-icon"></span>
        </button>

        <div class="collapse navbar-collapse" id="navbarNav">

            <ul class="navbar-nav ms-auto align-items-lg-center">

                <li class="nav-item">
                    <a class="nav-link" href="/fashion-store/index.php">
                        Начало
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fashion-store/shop.php">
                        Магазин
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fashion-store/about.php">
                        За нас
                    </a>
                </li>

                <li class="nav-item">
                    <a class="nav-link" href="/fashion-store/contact.php">
                        Контакти
                    </a>
                </li>

                <?php if (isset($_SESSION['user_id'])) { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/fashion-store/my_orders.php">
                            Моите поръчки
                        </a>
                    </li>

                    <?php if (
                        isset($_SESSION['user_role']) &&
                        $_SESSION['user_role'] === 'admin'
                    ) { ?>

                        <li class="nav-item">
                            <a class="nav-link" href="/fashion-store/admin/dashboard.php">
                                Админ панел
                            </a>
                        </li>

                    <?php } ?>

                    <li class="nav-item">
                        <span class="nav-link">
                            Здравей, <?php echo $_SESSION['user_name']; ?>
                        </span>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/fashion-store/logout.php">
                            Изход
                        </a>
                    </li>

                <?php } else { ?>

                    <li class="nav-item">
                        <a class="nav-link" href="/fashion-store/login.php">
                            Вход
                        </a>
                    </li>

                    <li class="nav-item">
                        <a class="nav-link" href="/fashion-store/register.php">
                            Регистрация
                        </a>
                    </li>

                <?php } ?>

                <li class="nav-item dropdown ms-lg-3">
                    <a
                        class="nav-link dropdown-toggle cart-menu"
                        href="#"
                        role="button"
                        data-bs-toggle="dropdown"
                    >
                        🛒
                        <span class="badge bg-success">
                            <?php echo $cart_count; ?>
                        </span>
                    </a>

                    <div
                        class="dropdown-menu dropdown-menu-end cart-dropdown p-3"
                        style="width:340px; max-height:420px; overflow-y:auto;"
                    >

                        <h6 class="mb-3">Количка</h6>

                        <div id="cart-dropdown-content">

                            <?php if (empty($cart_items)) { ?>

                                <p class="text-muted mb-2">
                                    Количката е празна.
                                </p>

                                <a href="/fashion-store/shop.php" class="btn btn-success btn-sm w-100">
                                    Към магазина
                                </a>

                            <?php } else { ?>

                                <?php foreach ($cart_items as $item) { ?>

                                    <div class="d-flex align-items-center justify-content-between mb-3">

                                        <div class="d-flex align-items-center">

                                            <?php if (!empty($item['image'])) { ?>

                                                <img
                                                    src="/fashion-store/assets/images/<?php echo $item['image']; ?>"
                                                    alt="<?php echo $item['name']; ?>"
                                                    style="width:60px; height:60px; min-width:60px; max-width:60px; object-fit:cover; border-radius:8px;"
                                                >

                                            <?php } ?>

                                            <div class="ms-3">

                                                <div style="font-weight:600; font-size:0.9rem;">
                                                    <?php echo $item['name']; ?>
                                                </div>

                                                <small>
                                                    <?php echo $item['quantity']; ?> x
                                                    <?php echo number_format($item['price'], 2); ?> €
                                                </small>

                                            </div>

                                        </div>

                                        <a
                                            href="#"
                                            class="btn btn-sm btn-outline-danger ms-2 ajax-remove-from-cart"
                                            data-id="<?php echo $item['id']; ?>"
                                            title="Премахни"
                                        >
                                            ✕
                                        </a>

                                    </div>

                                <?php } ?>

                                <hr>

                                <div class="d-flex justify-content-between mb-3">
                                    <strong>Общо:</strong>
                                    <strong><?php echo number_format($cart_total, 2); ?> €</strong>
                                </div>

                                <a href="/fashion-store/cart.php" class="btn btn-success btn-sm w-100 mb-2">
                                    Към количката
                                </a>

                                <a href="/fashion-store/checkout.php" class="btn btn-primary btn-sm w-100">
                                    Поръчай
                                </a>

                            <?php } ?>

                        </div>

                    </div>
                </li>

            </ul>

        </div>

    </div>
</nav>