<?php
session_start();

require_once 'includes/db.php';

if (!isset($_SESSION['user_id'])) {
    header("Location: login.php");
    exit;
}

if (empty($_SESSION['cart'])) {
    header("Location: cart.php");
    exit;
}

$total = 0;
$error = "";
$cart_items = [];

foreach ($_SESSION['cart'] as $product_id => $quantity) {
    $product_id = (int)$product_id;
    $quantity = (int)$quantity;

    $query = "SELECT * FROM products WHERE id = $product_id";
    $result = mysqli_query($conn, $query);

    if ($product = mysqli_fetch_assoc($result)) {
        if ($quantity > $product['stock']) {
            $error = "Няма достатъчна наличност за продукта: " . $product['name'];
        }

        $subtotal = $product['price'] * $quantity;
        $total += $subtotal;

        $cart_items[] = [
            'id' => $product['id'],
            'name' => $product['name'],
            'price' => $product['price'],
            'quantity' => $quantity,
            'subtotal' => $subtotal
        ];
    }
}

$delivery_method = "";
$delivery_price = 0;

if (isset($_POST['delivery_method'])) {
    $delivery_method = $_POST['delivery_method'];

    if ($delivery_method == "Еконт до адрес") {
        $delivery_price = 4.99;
    } elseif ($delivery_method == "Спиди до адрес") {
        $delivery_price = 4.49;
    } elseif ($delivery_method == "Вземане от магазин") {
        $delivery_price = 0;
    }
}

$final_total = $total + $delivery_price;

if (isset($_POST['place_order']) && empty($error)) {
    $user_id = (int)$_SESSION['user_id'];

    $phone = mysqli_real_escape_string($conn, $_POST['phone']);
    $city = mysqli_real_escape_string($conn, $_POST['city']);
    $address = mysqli_real_escape_string($conn, $_POST['address']);
    $postal_code = mysqli_real_escape_string($conn, $_POST['postal_code']);
    $payment_method = mysqli_real_escape_string($conn, $_POST['payment_method']);
    $delivery_method = mysqli_real_escape_string($conn, $_POST['delivery_method']);
    $note = mysqli_real_escape_string($conn, $_POST['note']);

    if ($delivery_method == "Еконт до адрес") {
        $delivery_price = 4.99;
    } elseif ($delivery_method == "Спиди до адрес") {
        $delivery_price = 4.49;
    } elseif ($delivery_method == "Вземане от магазин") {
        $delivery_price = 0;
    } else {
        $error = "Моля, изберете метод на доставка.";
    }

    $final_total = $total + $delivery_price;

    foreach ($_SESSION['cart'] as $product_id => $quantity) {
        $product_id = (int)$product_id;
        $quantity = (int)$quantity;

        $check_query = "SELECT * FROM products WHERE id = $product_id";
        $check_result = mysqli_query($conn, $check_query);
        $check_product = mysqli_fetch_assoc($check_result);

        if (!$check_product || $quantity > $check_product['stock']) {
            $error = "Няма достатъчна наличност за един от продуктите.";
            break;
        }
    }

    if (empty($error)) {
        $order_query = "
            INSERT INTO orders (
                user_id,
                total_price,
                status,
                phone,
                city,
                address,
                postal_code,
                payment_method,
                delivery_method,
                delivery_price,
                note
            )
            VALUES (
                $user_id,
                $final_total,
                'pending',
                '$phone',
                '$city',
                '$address',
                '$postal_code',
                '$payment_method',
                '$delivery_method',
                $delivery_price,
                '$note'
            )
        ";

        if (mysqli_query($conn, $order_query)) {
            $order_id = mysqli_insert_id($conn);

            foreach ($_SESSION['cart'] as $product_id => $quantity) {
                $product_id = (int)$product_id;
                $quantity = (int)$quantity;

                $product_query = "SELECT * FROM products WHERE id = $product_id";
                $product_result = mysqli_query($conn, $product_query);
                $product = mysqli_fetch_assoc($product_result);

                if ($product) {
                    $price = $product['price'];

                    $item_query = "
                        INSERT INTO order_items (order_id, product_id, quantity, price)
                        VALUES ($order_id, $product_id, $quantity, $price)
                    ";

                    mysqli_query($conn, $item_query);

                    $stock_query = "
                        UPDATE products
                        SET stock = stock - $quantity
                        WHERE id = $product_id AND stock >= $quantity
                    ";

                    mysqli_query($conn, $stock_query);
                }
            }

            unset($_SESSION['cart']);

            header("Location: my_orders.php");
            exit;
        }
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5">

    <h1 class="mb-4">Финализиране на поръчка</h1>

    <?php if (!empty($error)) { ?>

        <div class="alert alert-danger">
            <?php echo $error; ?>
        </div>

        <a href="cart.php" class="btn btn-secondary mb-4">
            Назад към количката
        </a>

    <?php } ?>

    <div class="row">

        <div class="col-md-7 mb-4">

            <div class="card p-4 h-100">

                <h4>
                    Клиент:
                    <?php echo $_SESSION['user_name']; ?>
                </h4>

                <hr>

                <form method="post">

                    <h5 class="mb-3">Данни за доставка</h5>

                    <div class="mb-3">
                        <label class="form-label">Телефон</label>
                        <input type="text" name="phone" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Град</label>
                        <input type="text" name="city" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Адрес за доставка</label>
                        <input type="text" name="address" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Пощенски код</label>
                        <input type="text" name="postal_code" class="form-control" required>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Метод на доставка</label>

                        <select name="delivery_method" id="delivery_method" class="form-select" required>
                            <option value="">Изберете метод на доставка</option>
                            <option value="Еконт до адрес" data-price="4.99">Еконт до адрес - 4.99 €</option>
                            <option value="Спиди до адрес" data-price="4.49">Спиди до адрес - 4.49 €</option>
                            <option value="Вземане от магазин" data-price="0">Вземане от магазин - 0.00 €</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Метод на плащане</label>

                        <select name="payment_method" class="form-select" required>
                            <option value="">Изберете метод на плащане</option>
                            <option value="Наложен платеж">Наложен платеж</option>
                            <option value="Банкова карта">Банкова карта</option>
                            <option value="Банков превод">Банков превод</option>
                        </select>
                    </div>

                    <div class="mb-4">
                        <label class="form-label">Бележка към поръчката</label>

                        <textarea
                            name="note"
                            class="form-control"
                            rows="3"
                            placeholder="Допълнителна информация към поръчката..."
                        ></textarea>
                    </div>

                    <button
                        type="submit"
                        name="place_order"
                        class="btn btn-success"
                        <?php if (!empty($error)) echo 'disabled'; ?>
                    >
                        Потвърди поръчката
                    </button>

                    <a href="cart.php" class="btn btn-secondary">
                        Назад
                    </a>

                </form>

            </div>

        </div>

        <div class="col-md-5 mb-4">

            <div class="card p-4 h-100">

                <h4 class="mb-3">Вашата поръчка</h4>

                <?php foreach ($cart_items as $item) { ?>

                    <div class="d-flex justify-content-between mb-2">
                        <span>
                            <?php echo $item['name']; ?> x <?php echo $item['quantity']; ?>
                        </span>

                        <strong>
                            <?php echo number_format($item['price'] * $item['quantity'], 2); ?> €
                        </strong>
                    </div>

                <?php } ?>

                <hr>

                <div class="d-flex justify-content-between mb-2">
                    <span>Стойност на продуктите:</span>
                    <strong id="products-total"><?php echo number_format($total, 2); ?> €</strong>
                </div>

                <div class="d-flex justify-content-between mb-2">
                    <span>Доставка:</span>
                    <strong id="delivery-price">0.00 €</strong>
                </div>

                <hr>

                <div class="d-flex justify-content-between">
                    <h5>Крайна сума:</h5>
                    <h5 id="final-total"><?php echo number_format($total, 2); ?> €</h5>
                </div>

            </div>

        </div>

    </div>

</div>

<script>
    const productsTotal = <?php echo json_encode((float)$total); ?>;
    const deliverySelect = document.getElementById("delivery_method");
    const deliveryPriceElement = document.getElementById("delivery-price");
    const finalTotalElement = document.getElementById("final-total");

    deliverySelect.addEventListener("change", function () {
        const selectedOption = deliverySelect.options[deliverySelect.selectedIndex];
        const deliveryPrice = parseFloat(selectedOption.getAttribute("data-price")) || 0;
        const finalTotal = productsTotal + deliveryPrice;

        deliveryPriceElement.textContent = deliveryPrice.toFixed(2) + " €";
        finalTotalElement.textContent = finalTotal.toFixed(2) + " €";
    });
</script>

<?php
require_once 'includes/footer.php';
?>