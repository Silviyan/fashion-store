<?php
session_start();
require_once 'includes/db.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = $_POST['password'];

    $query = "SELECT * FROM users WHERE email = '$email'";
    $result = mysqli_query($conn, $query);

    if (mysqli_num_rows($result) == 1) {
        $user = mysqli_fetch_assoc($result);

        if (password_verify($password, $user['password'])) {
            $_SESSION['user_id'] = $user['id'];
            $_SESSION['user_name'] = $user['first_name'];
            $_SESSION['user_role'] = $user['role'];

            if (!empty($_SESSION['cart'])) {
                header("Location: checkout.php");
                exit;
            }

            header("Location: index.php");
            exit;
        } else {
            $message = "Грешна парола.";
        }
    } else {
        $message = "Не съществува потребител с този имейл.";
    }
}

require_once 'includes/header.php';
?>

<div class="container my-5">
    <h1 class="mb-4">Вход</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-danger">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST" class="col-md-6">

        <div class="mb-3">
            <label class="form-label">Имейл</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Парола</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Вход
        </button>

    </form>
</div>

<?php
require_once 'includes/footer.php';
?>