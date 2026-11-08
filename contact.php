<?php
require_once 'includes/config.php';

$vead = [];

$nimi  = '';
$email = '';
$teema = '';
$sonum = '';

$lubatud_teemad = ['Looma adopteerimine', 'Vabatahtlik töö', 'Annetamine', 'Üldine küsimus'];

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Vormi andmete lugemine
    $nimi  = trim($_POST['nimi'] ?? '');
    $email = trim($_POST['email'] ?? '');
    $teema = trim($_POST['teema'] ?? '');
    $sonum = trim($_POST['sonum'] ?? '');

    // Andmete kontrollimine
    if (empty($nimi)) {
        $vead[] = 'Sisesta nimi.';
    }

    if (empty($email)) {
        $vead[] = 'Sisesta e-posti aadress.';
    } elseif (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
        $vead[] = 'Sisesta korrektne e-posti aadress.';
    }

    if (empty($teema) || !in_array($teema, $lubatud_teemad, true)) {
        $vead[] = 'Vali teema.';
    }

    if (empty($sonum)) {
        $vead[] = 'Kirjuta sõnum.';
    }

    // Salvestamine andmebaasi
    if (empty($vead)) {

        $sql = "INSERT INTO paringud
                (loom_id, nimi, email, teema, sonum, staatus)
                VALUES (NULL, ?, ?, ?, ?, 'uus')";

        $stmt = $conn->prepare($sql);
        if ($stmt === false) {
            die("Päringu ettevalmistamine ebaõnnestus: " . $conn->error);
        }

        $stmt->bind_param("ssss", $nimi, $email, $teema, $sonum);

        if ($stmt->execute()) {
            header("Location: contact.php?saadetud=1");
            exit;
        }

        $vead[] = 'Sõnumi salvestamine ebaõnnestus. Proovi hiljem uuesti.';
        $stmt->close();
    }
}

$pageTitle = 'Kontakt – Raven Käpakodu';
require_once 'includes/header.php';
?>
<!-- Contact -->
<div class="container py-5">

    <div class="mb-5">
        <h1 class="fw-bold display-4">Kontakt</h1>
        <p class="fs-5 text-muted">
            Võta meiega ühendust – vastame hea meelega kõikidele küsimustele.
        </p>
    </div>

    <div class="row g-4">

        <!-- Contact information -->
        <div class="col-lg-4">

            <div class="card shadow-sm border-0 h-100">
                <div class="card-body p-4">

                    <h2 class="fw-bold mb-4">Meie kontaktandmed</h2>

                    <div class="d-flex mb-4">
                        <i class="fa-solid fa-location-dot text-success fa-2x me-3 mt-1"></i>
                        <div>
                            <h3 class="h5 fw-bold mb-1">Aadress</h3>
                            <p class="mb-0">
                                Varjupaiga tee 1<br>
                                Tallinn 12345
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <i class="fa-solid fa-phone text-success fa-2x me-3 mt-1"></i>
                        <div>
                            <h3 class="h5 fw-bold mb-1">Telefon</h3>
                            <p class="mb-0">
                                +372 5555 1234<br>
                                E–P 10:00–18:00
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <i class="fa-regular fa-envelope text-success fa-2x me-3 mt-1"></i>
                        <div>
                            <h3 class="h5 fw-bold mb-1">E-post</h3>
                            <p class="mb-0">
                                info@varjupaik.ee
                            </p>
                        </div>
                    </div>

                    <div class="d-flex mb-4">
                        <i class="fa-regular fa-clock text-success fa-2x me-3 mt-1"></i>
                        <div>
                            <h3 class="h5 fw-bold mb-1">Lahtiolekuajad</h3>
                            <p class="mb-0">
                                E–P 10:00–18:00<br>
                                L–P kokkuleppel
                            </p>
                        </div>
                    </div>

                    <h3 class="h5 fw-bold mb-3">
                        <i class="fa-solid fa-share-nodes text-success me-2"></i>
                        Jälgi meid
                    </h3>

                    <div class="d-flex gap-3">

                        <a href="#" class="text-decoration-none">
                            <i class="fa-brands fa-facebook fa-3x text-primary"></i>
                        </a>

                        <a href="#" class="text-decoration-none">
                            <i class="fa-brands fa-instagram fa-3x" style="color:#E1306C;"></i>
                        </a>

                        <a href="#" class="text-decoration-none">
                            <i class="fa-brands fa-youtube fa-3x text-danger"></i>
                        </a>

                    </div>

                </div>
            </div>

        </div>

        <!-- Contact form -->
        <div class="col-lg-8">

            <div class="card shadow-sm border-0">
                <div class="card-body p-4">

                    <h2 class="fw-bold mb-4">Kirjuta meile</h2>

                    <?php if (!empty($vead)) { ?>
                        <div class="alert alert-danger">
                            <ul class="mb-0">
                                <?php foreach ($vead as $viga) { ?>
                                    <li><?= htmlspecialchars($viga) ?></li>
                                <?php } ?>
                            </ul>
                        </div>
                    <?php } ?>

                    <?php if (isset($_GET['saadetud'])) { ?>
                        <div class="alert alert-success">
                            Sõnum saadeti edukalt.
                        </div>
                    <?php } ?>

                    <form action="contact.php" method="post">

                        <div class="row mb-3">

                            <div class="col-md-6">
                                <label for="name" class="form-label fw-semibold">
                                    Sinu nimi
                                </label>

                                <input type="text" class="form-control" id="name" name="nimi" maxlength="100"
                                    placeholder="Sinu nimi" value="<?= htmlspecialchars($nimi) ?>" required>
                            </div>

                            <div class="col-md-6">
                                <label for="email" class="form-label fw-semibold">
                                    E-post
                                </label>

                                <input type="email" class="form-control" id="email" name="email" maxlength="100"
                                    placeholder="Sinu e-post" value="<?= htmlspecialchars($email) ?>" required>
                            </div>

                        </div>

                        <div class="mb-3">

                            <label for="subject" class="form-label fw-semibold">
                                Teema
                            </label>

                            <select class="form-select" id="subject" name="teema" required>
                                <option value="">Vali teema</option>
                                <?php foreach ($lubatud_teemad as $t) { ?>
                                    <option <?= $teema === $t ? 'selected' : '' ?>><?= htmlspecialchars($t) ?></option>
                                <?php } ?>
                            </select>

                        </div>

                        <div class="mb-4">

                            <label for="message" class="form-label fw-semibold">
                                Sõnum
                            </label>

                            <textarea class="form-control" id="message" name="sonum" rows="5"
                                placeholder="Kirjuta oma sõnum siia..." required><?= htmlspecialchars($sonum) ?></textarea>

                        </div>

                        <button class="btn btn-success px-4" type="submit">
                            <i class="fa-regular fa-paper-plane me-2"></i>
                            Saada sõnum
                        </button>

                    </form>

                </div>
            </div>

        </div>

    </div>

</div>
<!-- Contact end -->

<?php require_once 'includes/footer.php'; ?>