<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $first_name = mysqli_real_escape_string($conn, $_POST['first_name']);
    $last_name = mysqli_real_escape_string($conn, $_POST['last_name']);
    $email = mysqli_real_escape_string($conn, $_POST['email']);
    $password = password_hash($_POST['password'], PASSWORD_DEFAULT);

    $check_query = "SELECT * FROM users WHERE email = '$email'";
    $check_result = mysqli_query($conn, $check_query);

    if (mysqli_num_rows($check_result) > 0) {
        $message = "Потребител с този имейл вече съществува.";
    } else {
        $query = "INSERT INTO users (first_name, last_name, email, password, role)
                  VALUES ('$first_name', '$last_name', '$email', '$password', 'customer')";

        if (mysqli_query($conn, $query)) {
            $message = "Регистрацията е успешна. Можете да влезете в профила си.";
        } else {
            $message = "Грешка при регистрацията.";
        }
    }
}
?>

<div class="container my-5">
    <h1 class="mb-4">Регистрация</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-info">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <form method="POST" class="col-md-6">

        <div class="mb-3">
            <label class="form-label">Име</label>
            <input type="text" name="first_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Фамилия</label>
            <input type="text" name="last_name" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Имейл</label>
            <input type="email" name="email" class="form-control" required>
        </div>

        <div class="mb-3">
            <label class="form-label">Парола</label>
            <input type="password" name="password" class="form-control" required>
        </div>

        <button type="submit" class="btn btn-primary">
            Регистрация
        </button>

    </form>
</div>

<?php
require_once 'includes/footer.php';
?>