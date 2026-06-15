<?php
session_start();
require_once 'includes/db.php';

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit;
}

$product_id = (int)$_GET['id'];
$quantity_to_add = isset($_GET['quantity']) ? (int)$_GET['quantity'] : 1;

if ($quantity_to_add < 1) {
    if (isset($_GET['ajax'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Моля, въведете количество поне 1.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header("Location: shop.php");
    exit;
}

$product_query = "SELECT * FROM products WHERE id = $product_id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if (!$product || $product['stock'] <= 0) {
    if (isset($_GET['ajax'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Продуктът е изчерпан.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header("Location: shop.php");
    exit;
}

if (!isset($_SESSION['cart'])) {
    $_SESSION['cart'] = [];
}

$current_quantity = isset($_SESSION['cart'][$product_id]) ? $_SESSION['cart'][$product_id] : 0;
$new_quantity = $current_quantity + $quantity_to_add;

if ($new_quantity > $product['stock']) {
    if (isset($_GET['ajax'])) {
        echo json_encode([
            'success' => false,
            'message' => 'Наличността е само ' . $product['stock'] . ' бр. В количката вече имате ' . $current_quantity . ' бр.'
        ], JSON_UNESCAPED_UNICODE);
        exit;
    }

    header("Location: shop.php");
    exit;
}

$_SESSION['cart'][$product_id] = $new_quantity;

$cart_count = 0;
$cart_total = 0;
$cart_html = "";

foreach ($_SESSION['cart'] as $id => $quantity) {
    $id = (int)$id;
    $quantity = (int)$quantity;

    $cart_count += $quantity;

    $query = "SELECT * FROM products WHERE id = $id";
    $result = mysqli_query($conn, $query);

    if ($cart_product = mysqli_fetch_assoc($result)) {
        $cart_total += $cart_product['price'] * $quantity;

        $cart_html .= '
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div class="d-flex align-items-center">
                    <img
                        src="/fashion-store/assets/images/' . $cart_product['image'] . '"
                        style="width:60px; height:60px; min-width:60px; max-width:60px; object-fit:cover; border-radius:8px;"
                    >

                    <div class="ms-3">
                        <div style="font-weight:600; font-size:0.9rem;">
                            ' . $cart_product['name'] . '
                        </div>

                        <small>
                            ' . $quantity . ' x ' . number_format($cart_product['price'], 2) . ' €
                        </small>
                    </div>
                </div>

                <a
                    href="#"
                    class="btn btn-sm btn-outline-danger ms-2 ajax-remove-from-cart"
                    data-id="' . $cart_product['id'] . '"
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

if (isset($_GET['ajax'])) {
    echo json_encode([
        'success' => true,
        'cart_count' => $cart_count,
        'cart_html' => $cart_html
    ], JSON_UNESCAPED_UNICODE);

    exit;
}

if (isset($_SERVER['HTTP_REFERER'])) {
    header("Location: " . $_SERVER['HTTP_REFERER']);
    exit;
}

header("Location: shop.php");
exit;
?>