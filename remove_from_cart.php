<?php
session_start();
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    if (isset($_GET['ajax'])) {
        echo json_encode(['success' => false]);
        exit;
    }

    header("Location: cart.php");
    exit;
}

$product_id = (int)$_GET['id'];

if (isset($_SESSION['cart'][$product_id])) {
    unset($_SESSION['cart'][$product_id]);
}

if (!isset($_GET['ajax'])) {
    if (isset($_SERVER['HTTP_REFERER'])) {
        header("Location: " . $_SERVER['HTTP_REFERER']);
        exit;
    }

    header("Location: cart.php");
    exit;
}

$cart_count = 0;
$cart_total = 0;
$cart_html = "";

if (!empty($_SESSION['cart'])) {
    foreach ($_SESSION['cart'] as $id => $quantity) {
        $id = (int)$id;
        $quantity = (int)$quantity;

        $cart_count += $quantity;

        $query = "SELECT * FROM products WHERE id = $id";
        $result = mysqli_query($conn, $query);

        if ($product = mysqli_fetch_assoc($result)) {
            $cart_total += $product['price'] * $quantity;

            $cart_html .= '
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div class="d-flex align-items-center">
                        <img
                            src="/fashion-store/assets/images/' . $product['image'] . '"
                            style="width:60px; height:60px; min-width:60px; max-width:60px; object-fit:cover; border-radius:8px;"
                        >

                        <div class="ms-3">
                            <div style="font-weight:600; font-size:0.9rem;">
                                ' . $product['name'] . '
                            </div>

                            <small>
                                ' . $quantity . ' x ' . number_format($product['price'], 2) . ' €
                            </small>
                        </div>
                    </div>

                    <a
                        href="#"
                        class="btn btn-sm btn-outline-danger ms-2 ajax-remove-from-cart"
                        data-id="' . $product['id'] . '"
                        title="Премахни"
                    >
                        ✕
                    </a>
                </div>
            ';
        }
    }

    $cart_html .= '
        <hr>

        <div class="d-flex justify-content-between mb-3">
            <strong>Общо:</strong>
            <strong>' . number_format($cart_total, 2) . ' €</strong>
        </div>

        <a href="/fashion-store/cart.php" class="btn btn-success btn-sm w-100 mb-2">
            Към количката
        </a>

        <a href="/fashion-store/checkout.php" class="btn btn-primary btn-sm w-100">
            Поръчай
        </a>
    ';
} else {
    $cart_html = '
        <p class="text-muted mb-2">
            Количката е празна.
        </p>

        <a href="/fashion-store/shop.php" class="btn btn-success btn-sm w-100">
            Към магазина
        </a>
    ';
}

echo json_encode([
    'success' => true,
    'cart_count' => $cart_count,
    'cart_html' => $cart_html
], JSON_UNESCAPED_UNICODE);

exit;
?>