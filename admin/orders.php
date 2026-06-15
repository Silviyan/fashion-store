<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = "
    SELECT orders.*, users.first_name, users.last_name, users.email
    FROM orders
    LEFT JOIN users ON orders.user_id = users.id
    ORDER BY orders.id DESC
";

$result = mysqli_query($conn, $query);
?>

<div class="container my-5">

    <h1 class="mb-4">Управление на поръчки</h1>

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Клиент</th>
                <th>Имейл</th>
                <th>Обща сума</th>
                <th>Статус</th>
                <th>Дата</th>
                <th>Действия</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($order = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td>
                        <?php echo $order['id']; ?>
                    </td>

                    <td>
                        <?php echo $order['first_name'] . " " . $order['last_name']; ?>
                    </td>

                    <td>
                        <?php echo $order['email']; ?>
                    </td>

                    <td>
                        <?php echo number_format($order['total_price'], 2); ?> €
                    </td>

                    <td>

                        <?php if ($order['status'] == 'pending') { ?>

                            <span class="badge bg-warning text-dark">
                                Очаква обработка
                            </span>

                        <?php } elseif ($order['status'] == 'processing') { ?>

                            <span class="badge bg-primary">
                                Обработва се
                            </span>

                        <?php } elseif ($order['status'] == 'completed') { ?>

                            <span class="badge bg-success">
                                Завършена
                            </span>

                        <?php } elseif ($order['status'] == 'cancelled') { ?>

                            <span class="badge bg-danger">
                                Отказана
                            </span>

                        <?php } ?>

                    </td>

                    <td>
                        <?php echo $order['created_at']; ?>
                    </td>

                    <td>

                        <a
                            href="order_details.php?id=<?php echo $order['id']; ?>"
                            class="btn btn-primary btn-sm"
                        >
                            Детайли
                        </a>

                        <a
                            href="edit_order.php?id=<?php echo $order['id']; ?>"
                            class="btn btn-warning btn-sm"
                        >
                            Статус
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