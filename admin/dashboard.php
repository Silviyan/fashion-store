<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$products_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM products");
$products_count = mysqli_fetch_assoc($products_result)['total'];

$users_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM users");
$users_count = mysqli_fetch_assoc($users_result)['total'];

$orders_result = mysqli_query($conn, "SELECT COUNT(*) AS total FROM orders");
$orders_count = mysqli_fetch_assoc($orders_result)['total'];

$revenue_result = mysqli_query($conn, "SELECT SUM(total_price) AS total FROM orders WHERE status != 'cancelled'");
$revenue = mysqli_fetch_assoc($revenue_result)['total'];

if ($revenue == null) {
    $revenue = 0;
}
?>

<div class="container my-5">

    <h1 class="mb-3">Административен панел</h1>

    <p class="text-muted">
        Добре дошли, <?php echo $_SESSION['user_name']; ?>.
    </p>

    <div class="row mt-4 mb-5">

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center">
                <h5>Продукти</h5>
                <h2><?php echo $products_count; ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center">
                <h5>Потребители</h5>
                <h2><?php echo $users_count; ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center">
                <h5>Поръчки</h5>
                <h2><?php echo $orders_count; ?></h2>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 text-center">
                <h5>Оборот</h5>
                <h2><?php echo number_format($revenue, 2); ?> €</h2>
            </div>
        </div>

    </div>

    <div class="row mt-4">

        <div class="col-md-4 mb-4">
            <div class="card p-3 h-100">
                <h3>Продукти</h3>
                <p>Добавяне, редакция и изтриване на продукти.</p>

                <a href="products.php" class="btn btn-primary mt-auto">
                    Управление
                </a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-3 h-100">
                <h3>Поръчки</h3>
                <p>Преглед и управление на клиентски поръчки.</p>

                <a href="orders.php" class="btn btn-primary mt-auto">
                    Управление
                </a>
            </div>
        </div>

        <div class="col-md-4 mb-4">
            <div class="card p-3 h-100">
                <h3>Потребители</h3>
                <p>Преглед на регистрираните потребители.</p>

                <a href="users.php" class="btn btn-primary mt-auto">
                    Управление
                </a>
            </div>
        </div>

    </div>

</div>

<?php
require_once '../includes/footer.php';
?>