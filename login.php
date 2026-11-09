<?php
session_start();

require_once 'includes/config.php';

$viga = '';

// Kas vorm on saadetud (POST)
if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Võta kasutaja sisestatud e-post ja parool
    $email = trim($_POST['email'] ?? '');
    $parool = $_POST['parool'] ?? '';

    // Otsi töötaja e-posti järgi
    $stmt = $conn->prepare("SELECT id, nimi, email, parool, roll FROM tootajad WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();
    $result = $stmt->get_result();
    $tootaja = $result->fetch_assoc();

    // Kontrolli parooli räsi vastu
    if ($tootaja && password_verify($parool, $tootaja['parool'])) {
        // Uus sessiooni ID kaitseks sessiooni kinnipanemise vastu
        session_regenerate_id(true);

        // Salvesta kasutaja andmed sessiooni
        $_SESSION['kasutaja'] = [
            'id' => $tootaja['id'],
            'nimi' => $tootaja['nimi'],
            'roll' => $tootaja['roll']
        ];

        header('Location: admin/index.php');
        exit;
    } else {
        $viga = 'Vale e-posti aadress või parool.';
    }
}

$pageTitle = 'Logi sisse – Raven Käpakodu';
require_once 'includes/header.php';
?>
<!-- Login -->
<div class="container py-5">
    <div class="row justify-content-center">
        <div class="col-lg-5">
            <div class="card border-0 shadow-sm">
                <div class="card-body p-5">
                    <div class="text-center mb-4">
                        <img src="assets/images/logo_big.svg" alt="Logo" width="80">

                        <h1 class="fw-bold mt-3">Logi sisse</h1>

                        <p class="text-muted">
                            Logi sisse, et hallata oma päringuid<br>
                            ja jälgida lemmiku adopteerimise staatust.
                        </p>
                    </div>

                    <?php if (!empty($viga)) { ?>
                        <div class="alert alert-danger">
                            <?= htmlspecialchars($viga) ?>
                        </div>
                    <?php } ?>

                    <form method="post">

                        <div class="mb-3">
                            <label for="loginEmail" class="form-label fw-semibold">
                                E-post
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>

                                <input type="email" class="form-control" id="loginEmail" name="email" placeholder="Sinu e-post"
                                    value="<?= htmlspecialchars($email ?? '') ?>" required>
                            </div>
                        </div>

                        <div class="mb-3">
                            <label for="loginPassword" class="form-label fw-semibold">
                                Parool
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-solid fa-lock"></i>
                                </span>

                                <input type="password" class="form-control" id="loginPassword" name="parool" placeholder="Sinu parool" required>

                                <button class="btn btn-outline-secondary" type="button" aria-label="Näita parooli">
                                    <i class="fa-regular fa-eye"></i>
                                </button>
                            </div>
                        </div>

                        <div class="d-flex justify-content-between align-items-center mb-4">

                            <div class="form-check">
                                <input class="form-check-input" type="checkbox" id="remember">

                                <label class="form-check-label" for="remember">
                                    Jäta mind meelde
                                </label>
                            </div>

                            <a href="#" class="text-success text-decoration-none">
                                Unustasid parooli?
                            </a>

                        </div>

                        <div class="d-grid mb-4">
                            <button class="btn btn-success" type="submit">
                                Logi sisse
                            </button>
                        </div>

                        <div class="text-center text-muted mb-3">
                            <hr>
                            <span class="px-3 bg-white position-relative" style="top:-32px;">
                                või
                            </span>
                        </div>

                        <div class="d-grid">
                            <button class="btn btn-outline-success" type="button">
                                <i class="fa-regular fa-user me-2"></i>
                                Loo uus konto
                            </button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>
<!-- Login end -->

<?php require_once 'includes/footer.php'; ?>