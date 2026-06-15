<?php
require_once '../includes/db.php';

if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$message = "";

$categories = mysqli_query($conn, "SELECT * FROM categories ORDER BY name ASC");

$subcategories = mysqli_query($conn, "
    SELECT subcategories.*, categories.name AS category_name
    FROM subcategories
    LEFT JOIN categories ON subcategories.category_id = categories.id
    ORDER BY categories.name ASC, subcategories.name ASC
");

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $category_id = (int)$_POST['category_id'];
    $subcategory_id = (int)$_POST['subcategory_id'];
    $name = mysqli_real_escape_string($conn, $_POST['name']);
    $description = mysqli_real_escape_string($conn, $_POST['description']);
    $price = (float)$_POST['price'];
    $image = mysqli_real_escape_string($conn, $_POST['image']);
    $stock = (int)$_POST['stock'];

    $query = "
        INSERT INTO products (category_id, subcategory_id, name, description, price, image, stock)
        VALUES ($category_id, $subcategory_id, '$name', '$description', $price, '$image', $stock)
    ";

    if (mysqli_query($conn, $query)) {
        header("Location: products.php");
        exit;
    } else {
        $message = "Грешка при добавяне на продукта.";
    }
}

require_once '../includes/header.php';
?>

<div class="container my-5">

    <h1 class="mb-4">Добавяне на продукт</h1>

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

                    <option value="<?php echo $category['id']; ?>">
                        <?php echo $category['name']; ?>
                    </option>

                <?php } ?>

            </select>
        </div>

        <div class="mb-3">
            <label class="form-label">Подкатегория</label>

            <select name="subcategory_id" class="form-select" required>

                <?php while ($subcategory = mysqli_fetch_assoc($subcategories)) { ?>

                    <option value="<?php echo $subcategory['id']; ?>">
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
            ></textarea>
        </div>

        <div class="mb-3">
            <label class="form-label">Цена</label>

            <input
                type="number"
                step="0.01"
                name="price"
                class="form-control"
                required
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Име на снимка</label>

            <input
                type="text"
                name="image"
                class="form-control"
                placeholder="example.jpg"
            >
        </div>

        <div class="mb-3">
            <label class="form-label">Наличност</label>

            <input
                type="number"
                name="stock"
                class="form-control"
                required
            >
        </div>

        <button type="submit" class="btn btn-success">
            Добави продукт
        </button>

        <a href="products.php" class="btn btn-secondary">
            Назад
        </a>

    </form>

</div>

<?php
require_once '../includes/footer.php';
?>