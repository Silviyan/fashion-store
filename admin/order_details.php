<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

if (!isset($_GET['id'])) {
    header("Location: orders.php");
    exit;
}

$order_id = (int)$_GET['id'];

$order_query = "
    SELECT orders.*, users.first_name, users.last_name, users.email
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    WHERE orders.id = $order_id
";

$order_result = mysqli_query($conn, $order_query);
$order = mysqli_fetch_assoc($order_result);

if (!$order) {
    header("Location: orders.php");
    exit;
}

$status_text = "";

if ($order['status'] == 'pending') {
    $status_text = "Очаква обработка";
} elseif ($order['status'] == 'processing') {
    $status_text = "Обработва се";
} elseif ($order['status'] == 'completed') {
    $status_text = "Завършена";
} elseif ($order['status'] == 'cancelled') {
    $status_text = "Отказана";
}

$items_query = "
    SELECT order_items.*, products.name, products.image
    FROM order_items
    LEFT JOIN products ON order_items.product_id = products.id
    WHERE order_items.order_id = $order_id
";

$items_result = mysqli_query($conn, $items_query);
?>

<div class="container my-5">

    <h1 class="mb-4">
        Детайли за поръчка #<?php echo $order['id']; ?>
    </h1>

    <div class="card p-4 mb-4">

        <p>
            <strong>Клиент:</strong>
            <?php echo $order['first_name'] . " " . $order['last_name']; ?>
        </p>

        <p>
            <strong>Имейл:</strong>
            <?php echo $order['email']; ?>
        </p>

        <p>
            <strong>Телефон:</strong>
            <?php echo $order['phone']; ?>
        </p>

        <p>
            <strong>Град:</strong>
            <?php echo $order['city']; ?>
        </p>

        <p>
            <strong>Адрес:</strong>
            <?php echo $order['address']; ?>
        </p>

        <p>
            <strong>Пощенски код:</strong>
            <?php echo $order['postal_code']; ?>
        </p>

        <p>
            <strong>Метод на плащане:</strong>
            <?php echo $order['payment_method']; ?>
        </p>

        <?php if (!empty($order['note'])) { ?>

            <p>
                <strong>Бележка:</strong>
                <?php echo nl2br($order['note']); ?>
            </p>

        <?php } ?>

        <p>
            <strong>Статус:</strong>
            <?php echo $status_text; ?>
        </p>

        <p>
            <strong>Дата:</strong>
            <?php echo $order['created_at']; ?>
        </p>

        <p>
            <strong>Обща сума:</strong>
            <?php echo number_format($order['total_price'], 2); ?> €
        </p>

    </div>

    <h3 class="mb-3">Продукти в поръчката</h3>

    <table class="table table-bordered table-striped align-middle">

        <thead>
            <tr>
                <th>Снимка</th>
                <th>Продукт</th>
                <th>Количество</th>
                <th>Цена</th>
                <th>Общо</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($item = mysqli_fetch_assoc($items_result)) { ?>

                <tr>

                    <td>
                        <?php if (!empty($item['image'])) { ?>
                            <img
                                src="../assets/images/<?php echo $item['image']; ?>"
                                alt="<?php echo $item['name']; ?>"
                                style="width:70px; height:70px; object-fit:cover; border-radius:10px;"
                            >
                        <?php } ?>
                    </td>

                    <td>
                        <?php echo $item['name']; ?>
                    </td>

                    <td>
                        <?php echo $item['quantity']; ?>
                    </td>

                    <td>
                        <?php echo number_format($item['price'], 2); ?> €
                    </td>

                    <td>
                        <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                    </td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

    <a href="orders.php" class="btn btn-secondary">
        Назад
    </a>

</div>

<?php
require_once '../includes/footer.php';
?>