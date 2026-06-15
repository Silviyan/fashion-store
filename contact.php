<?php
require_once 'includes/db.php';
require_once 'includes/header.php';

$message = "";

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    $message = "Вашето съобщение беше изпратено успешно.";
}
?>

<div class="container my-5">

    <h1 class="mb-4">Контакти</h1>

    <?php if (!empty($message)) { ?>
        <div class="alert alert-success">
            <?php echo $message; ?>
        </div>
    <?php } ?>

    <div class="row mb-4">

        <div class="col-md-3 mb-3">
            <div class="card p-4 h-100 text-center">
                <h5>📍 Адрес</h5>
                <p class="mb-0">бул. Цариградско шосе 115З</p>
                <p class="mb-0">The Mall, София</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 h-100 text-center">
                <h5>📞 Телефон</h5>
                <p class="mb-0">+359 888 123 456</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 h-100 text-center">
                <h5>✉️ Имейл</h5>
                <p class="mb-0">info@fashionstore.bg</p>
            </div>
        </div>

        <div class="col-md-3 mb-3">
            <div class="card p-4 h-100 text-center">
                <h5>🕒 Работно време</h5>
                <p class="mb-0">Пон. - Пет. 09:00 - 18:00</p>
                <p class="mb-0">Събота 10:00 - 14:00</p>
            </div>
        </div>

    </div>

    <div class="row">

        <div class="col-md-6 mb-4">

            <div class="card p-4 h-100">
                <h4 class="mb-3">Свържете се с нас</h4>

                <form method="POST">

                    <div class="mb-3">
                        <label class="form-label">Име</label>
                        <input
                            type="text"
                            name="name"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Имейл</label>
                        <input
                            type="email"
                            name="email"
                            class="form-control"
                            required
                        >
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Съобщение</label>
                        <textarea
                            name="message"
                            class="form-control"
                            rows="5"
                            required
                        ></textarea>
                    </div>

                    <button type="submit" class="btn btn-success">
                        Изпрати
                    </button>

                </form>
            </div>

        </div>

        <div class="col-md-6 mb-4">

            <div class="card p-4 h-100">
                <h4 class="mb-3">Намерете ни</h4>

                <div class="ratio ratio-4x3">
                    <iframe
                        src="https://maps.google.com/maps?q=The%20Mall%20Sofia&t=&z=15&ie=UTF8&iwloc=&output=embed"
                        style="border:0;"
                        allowfullscreen=""
                        loading="lazy"
                    ></iframe>
                </div>
            </div>

        </div>

    </div>

</div>

<?php
require_once 'includes/footer.php';
?>