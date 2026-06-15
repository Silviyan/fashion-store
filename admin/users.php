<?php
require_once '../includes/db.php';
require_once '../includes/header.php';

if (!isset($_SESSION['user_id']) || $_SESSION['user_role'] !== 'admin') {
    header("Location: ../login.php");
    exit;
}

$query = "SELECT * FROM users ORDER BY id DESC";
$result = mysqli_query($conn, $query);
?>

<div class="container my-5">

    <h1 class="mb-4">Управление на потребители</h1>

    <table class="table table-bordered table-striped">

        <thead>
            <tr>
                <th>ID</th>
                <th>Име</th>
                <th>Фамилия</th>
                <th>Имейл</th>
                <th>Роля</th>
                <th>Дата</th>
            </tr>
        </thead>

        <tbody>

            <?php while ($user = mysqli_fetch_assoc($result)) { ?>

                <tr>

                    <td><?php echo $user['id']; ?></td>

                    <td><?php echo $user['first_name']; ?></td>

                    <td><?php echo $user['last_name']; ?></td>

                    <td><?php echo $user['email']; ?></td>

                    <td><?php echo $user['role']; ?></td>

                    <td><?php echo $user['created_at']; ?></td>

                </tr>

            <?php } ?>

        </tbody>

    </table>

</div>

<?php
require_once '../includes/footer.php';
?>