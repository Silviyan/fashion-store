<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

if (!isset($_GET['id'])) {
    header("Location: shop.php");
    exit;
}

$id = (int)$_GET['id'];
$rating_message = "";

$query = "SELECT * FROM products WHERE id = $id";
$result = mysqli_query($conn, $query);
$product = mysqli_fetch_assoc($result);

if (!$product) {
    echo "<div class='container my-5'><h2>Продуктът не е намерен.</h2></div>";
    require_once 'includes/footer.php';
    exit;
}

if (isset($_POST['submit_rating']) && isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];
    $rating = (int)$_POST['rating'];

    if ($rating >= 1 && $rating <= 5) {
        $check_rating_query = "
            SELECT *
            FROM product_ratings
            WHERE product_id = $id AND user_id = $user_id
        ";

        $check_rating_result = mysqli_query($conn, $check_rating_query);

        if (mysqli_num_rows($check_rating_result) > 0) {
            $update_rating_query = "
                UPDATE product_ratings
                SET rating = $rating
                WHERE product_id = $id AND user_id = $user_id
            ";

            mysqli_query($conn, $update_rating_query);

            $rating_message = "Оценката Ви беше обновена успешно.";
        } else {
            $insert_rating_query = "
                INSERT INTO product_ratings (product_id, user_id, rating)
                VALUES ($id, $user_id, $rating)
            ";

            mysqli_query($conn, $insert_rating_query);

            $rating_message = "Благодарим Ви за оценката.";
        }
    }
}

$rating_query = "
    SELECT AVG(rating) AS average_rating, COUNT(*) AS rating_count
    FROM product_ratings
    WHERE product_id = $id
";

$rating_result = mysqli_query($conn, $rating_query);
$rating_data = mysqli_fetch_assoc($rating_result);

$average_rating = $rating_data['average_rating'] ? round($rating_data['average_rating'], 1) : 0;
$rating_count = (int)$rating_data['rating_count'];

$user_rating = 0;

if (isset($_SESSION['user_id'])) {
    $user_id = (int)$_SESSION['user_id'];

    $user_rating_query = "
        SELECT rating
        FROM product_ratings
        WHERE product_id = $id AND user_id = $user_id
    ";

    $user_rating_result = mysqli_query($conn, $user_rating_query);

    if ($user_rating_row = mysqli_fetch_assoc($user_rating_result)) {
        $user_rating = (int)$user_rating_row['rating'];
    }
}
?>

<style>
    .rating-stars {
        display: inline-flex;
        flex-direction: row-reverse;
        gap: 5px;
    }

    .rating-stars input {
        display: none;
    }

    .rating-stars label {
        font-size: 32px;
        color: #ccc;
        cursor: pointer;
        transition: color 0.2s;
    }

    .rating-stars input:checked ~ label,
    .rating-stars label:hover,
    .rating-stars label:hover ~ label {
        color: #ffc107;
    }
</style>

<div class="container my-5">
    <div class="row">

        <div class="col-md-6">
            <img
                src="assets/images/<?php echo $product['image']; ?>"
                class="img-fluid product-detail-img"
                alt="<?php echo $product['name']; ?>"
            >
        </div>

        <div class="col-md-6">
            <h1><?php echo $product['name']; ?></h1>

            <div class="mb-3">

                <?php if ($rating_count > 0) { ?>

                    <div class="text-warning fs-4">
                        <?php
                        for ($i = 1; $i <= 5; $i++) {
                            if ($i <= round($average_rating)) {
                                echo "★";
                            } else {
                                echo "☆";
                            }
                        }
                        ?>
                    </div>

                    <p class="text-muted mb-0">
                        Средна оценка:
                        <?php echo $average_rating; ?> / 5
                        (<?php echo $rating_count; ?> оценки)
                    </p>

                <?php } else { ?>

                    <p class="text-muted">
                        Все още няма оценки за този продукт.
                    </p>

                <?php } ?>

            </div>

            <p class="lead">
                <?php echo $product['description']; ?>
            </p>

            <h3 class="text-success">
                <?php echo number_format($product['price'], 2); ?> €
            </h3>

            <?php if ($product['stock'] > 0) { ?>

                <p>
                    Наличност: <?php echo $product['stock']; ?> бр.
                </p>

                <div class="mb-3">
                    <label class="form-label">
                        Количество
                    </label>

                    <input
                        type="number"
                        id="quantity"
                        class="form-control"
                        value="1"
                        min="1"
                        max="<?php echo $product['stock']; ?>"
                        style="max-width: 120px;"
                    >
                </div>

                <a
                    href="add_to_cart.php?id=<?php echo $product['id']; ?>"
                    class="btn btn-primary btn-lg ajax-add-to-cart"
                    data-id="<?php echo $product['id']; ?>"
                >
                    Добави в количката
                </a>

            <?php } else { ?>

                <p class="text-danger fw-bold">
                    Изчерпано
                </p>

                <button class="btn btn-secondary btn-lg" disabled>
                    Изчерпано
                </button>

            <?php } ?>

            <hr class="my-4">

            <h5>Оценете този продукт</h5>

            <?php if (!empty($rating_message)) { ?>

                <div class="alert alert-success">
                    <?php echo $rating_message; ?>
                </div>

            <?php } ?>

            <?php if (isset($_SESSION['user_id'])) { ?>

                <form method="POST" class="mt-2">

                    <div class="rating-stars mb-3">

                        <input
                            type="radio"
                            id="star5"
                            name="rating"
                            value="5"
                            <?php if ($user_rating == 5) echo 'checked'; ?>
                            required
                        >
                        <label for="star5">★</label>

                        <input
                            type="radio"
                            id="star4"
                            name="rating"
                            value="4"
                            <?php if ($user_rating == 4) echo 'checked'; ?>
                        >
                        <label for="star4">★</label>

                        <input
                            type="radio"
                            id="star3"
                            name="rating"
                            value="3"
                            <?php if ($user_rating == 3) echo 'checked'; ?>
                        >
                        <label for="star3">★</label>

                        <input
                            type="radio"
                            id="star2"
                            name="rating"
                            value="2"
                            <?php if ($user_rating == 2) echo 'checked'; ?>
                        >
                        <label for="star2">★</label>

                        <input
                            type="radio"
                            id="star1"
                            name="rating"
                            value="1"
                            <?php if ($user_rating == 1) echo 'checked'; ?>
                        >
                        <label for="star1">★</label>

                    </div>

                    <button type="submit" name="submit_rating" class="btn btn-warning">
                        Запази оценката
                    </button>

                </form>

            <?php } else { ?>

                <p class="text-muted">
                    Трябва да влезете в профила си, за да оцените продукта.
                </p>

                <a href="login.php" class="btn btn-outline-primary btn-sm">
                    Вход
                </a>

            <?php } ?>

        </div>

    </div>
</div>

<?php
require_once 'includes/footer.php';
?>