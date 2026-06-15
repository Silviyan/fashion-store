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
$message = "";

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");

$subcategories = mysqli_query($conn, "
    SELECT subcategories.*, categories.name AS category_name
    FROM subcategories
    LEFT JOIN categories ON subcategories.category_id = categories.id
    ORDER BY categories.name ASC, subcategories.name ASC
");

$product_query = "SELECT * FROM products WHERE id = $id";
$product_result = mysqli_query($conn, $product_query);
$product = mysqli_fetch_assoc($product_result);

if (!$product) {
    header("Location: products.php");
    exit;
}

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = (int)$_POST['category_id'];
    $subcategory_id = (int)$_POST['subcategory_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $stock = (int)$_POST['stock'];

    $update_query = "
        UPDATE products
        SET
            category_id = $category_id,
            subcategory_id = $subcategory_id,
            name = '$name',
            description = '$description',
            price = $price,
            image = '$image',
            stock = $stock
        WHERE id = $id
    ";

    if (mysqli_query($conn, $update_query)) {
        header("Location: products.php");
        exit;
    } else {
        $message = "Грешка при редакция на продукта.";
    }
}

require_once '../includes/header.php';
?>

<div class="container my-5">

    <h1 class="mb-4">Редакция на продукт</h1>

    <?php if (!empty($message)) { ?>

        <div class="alert alert-danger">
            <?php echo $message; ?>
        </div>

    <?php } ?>

    <form method="POST" class="col-md-8">

        <div class="mb-3">
            <label class="form-label">Категория</label>

            <select name="category_id" class="form-select" required>

                <?php while ($category = mysqli_fetch_assoc($categories)) { ?>

                    <option
                        value="<?php echo $category['id']; ?>"
                        <?php if ($category['id'] == $product['category_id']) echo "selected"; ?>
                    >
                        <?php echo $category['name']; ?>
                    </option>

                <?php } ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Подкатегория</label>

            <select name="subcategory_id" class="form-select" required>

                <?php while ($subcategory = mysqli_fetch_assoc($subcategories)) { ?>

                    <option
                        value="<?php echo $subcategory['id']; ?>"
                        <?php if ($subcategory['id'] == $product['subcategory_id']) echo "selected"; ?>
                    >
                        <?php echo $subcategory['category_name']; ?> - <?php echo $subcategory['name']; ?>
                    </option>

                <?php } ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Име на продукт</label>

            <input
                type="text"
                name="name"
                class="form-control"
                value="<?php echo $product['name']; ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Описание</label>

            <textarea
                name="description"
                class="form-control"
                rows="4"
                required
            ><?php echo $product['description']; ?></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Цена</label>

            <input
                type="number"
                step="0.01"
                name="price"
                class="form-control"
                value="<?php echo $product['price']; ?>"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Име на снимка</label>

            <input
                type="text"
                name="image"
                class="form-control"
                value="<?php echo $product['image']; ?>"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Наличност</label>

            <input
                type="number"
                name="stock"
                class="form-control"
                value="<?php echo $product['stock']; ?>"
                required
            >
        </div>

        <button type="submit" class="btn btn-warning">
            Запази промените
        </button>

        <a href="products.php" class="btn btn-secondary">
            Назад
        </a>

    </form>

</div>

<?php
require_once '../includes/footer.php';
?>