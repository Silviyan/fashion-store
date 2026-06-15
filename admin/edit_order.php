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
    header("Location: orders.php");
    exit;
}

$order_id = (int)$_GET['id'];

$order_query = "SELECT * FROM orders WHERE id = $order_id";
$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    header("Location: orders.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $old_status = $order['status'];
    $status = mysqli_real_escape_string($conn, $_POST['status']);

    if ($old_status != 'cancelled' && $status == 'cancelled') {
        $items_query = "
            SELECT product_id, quantity
            FROM order_items
            WHERE order_id = $order_id
        ";

        $items_result = mysqli_query($conn, $items_query);

        while ($item = mysqli_fetch_assoc($items_result)) {
            $product_id = (int)$item['product_id'];
            $quantity = (int)$item['quantity'];

            $stock_query = "
                UPDATE products
                SET stock = stock + $quantity
                WHERE id = $product_id
            ";

            mysqli_query($conn, $stock_query);
        }
    }

    $update_query = "
        UPDATE orders
        SET status = '$status'
        WHERE id = $order_id
    ";

    mysqli_query($conn, $update_query);

    header("Location: orders.php");
    exit;
}

require_once '../includes/header.php';
?>

<div class="container my-5">

    <h1 class="mb-4">
        Редакция на поръчка #<?php echo $order['id']; ?>
    </h1>

    <form method="POST" class="col-md-6">

        <div class="mb-3">
            <label class="form-label">
                Статус на поръчката
            </label>

            <select name="status" class="form-select" required>

                <option value="pending" <?php if ($order['status'] == 'pending') echo 'selected'; ?>>
                    Очаква обработка
                </option>

                <option value="processing" <?php if ($order['status'] == 'processing') echo 'selected'; ?>>
                    Обработва се
                </option>

                <option value="completed" <?php if ($order['status'] == 'completed') echo 'selected'; ?>>
                    Завършена
                </option>

                <option value="cancelled" <?php if ($order['status'] == 'cancelled') echo 'selected'; ?>>
                    Отказана
                </option>

            </select>
        </div>

        <button type="submit" class="btn btn-warning">
            Запази статуса
        </button>

        <a href="orders.php" class="btn btn-secondary">
            Назад
        </a>

    </form>

</div>

<?php
require_once '../includes/footer.php';
?>