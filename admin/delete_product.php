<?php
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: products.php");
    exit;
}

$id = (int)$_GET['id'];

/*
    Проверяваме дали продуктът участва в поръчки
*/
$check_query = "
    SELECT COUNT(*) AS total
    FROM order_items
    WHERE product_id = $id
";

$check_result = mysqli_query($conn, $check_query);
$check_data = mysqli_fetch_assoc($check_result);

if ($check_data['total'] > 0) {

    echo "
    <script>
        alert('Продуктът не може да бъде изтрит, защото вече присъства в направени поръчки.');
        window.location.href='products.php';
    </script>
    ";

    exit;
}

/*
    Изтриване на продукта
*/
$query = "DELETE FROM products WHERE id = $id";

mysqli_query($conn, $query);

header("Location: products.php");
exit;
?>