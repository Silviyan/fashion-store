<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

$user_id = (int)$_SESSION['user_id'];

$query = "
    SELECT *
    FROM orders
    WHERE user_id = $user_id
    ORDER BY id DESC
";

$result = mysqli_query($conn, $query);
?>

<div class="container my-5">

    <h1 class="mb-4">Моите поръчки</h1>

    <?php if (mysqli_num_rows($result) == 0) { ?>

        <p>Все още нямате направени поръчки.</p>

        <a href="shop.php" class="btn btn-primary">
            Към магазина
        </a>

    <?php } else { ?>

        <table class="table table-bordered table-striped">

            <thead>
                <tr>
                    <th>ID</th>
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
                                href="my_order_details.php?id=<?php echo $order['id']; ?>"
                                class="btn btn-primary btn-sm"
                            >
                                Детайли
                            </a>

                        </td>

                    </tr>

                <?php } ?>

            </tbody>

        </table>

    <?php } ?>

</div>

<?php
require_once 'includes/footer.php';
?>