<?php
require_once('includes/config.php');

$vead = [];

// Muudetava looma ID aadressirealt
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: animals.php');
    exit;
}

// Loomaliigid rippmenüü jaoks
$sql_liigid = "SELECT id, nimetus FROM liigid ORDER BY nimetus";
$result_liigid = $conn->query($sql_liigid);

// Muudetava looma andmed
$stmt = $conn->prepare("SELECT * FROM loomad WHERE id = ?");
$stmt->bind_param("i", $id);
$stmt->execute();

$result = $stmt->get_result();
$loom = $result->fetch_assoc();

if (!$loom) {
    header('Location: animals.php');
    exit;
}

// Vormiväljade algväärtused andmebaasist
$nimi            = $loom['nimi'];
$liik_id         = $loom['liik_id'];
$sugu            = $loom['sugu'];
$staatus         = $loom['staatus'];
$sunnikuupaev    = $loom['sunnikuupaev'] ?? '';
$asukoht         = $loom['asukoht'];
$suurus          = $loom['suurus'];
$varvus          = $loom['varvus'];
$luhikirjeldus   = $loom['luhikirjeldus'];
$kirjeldus       = $loom['kirjeldus'];
$steriliseeritud = $loom['steriliseeritud'];
$kiibistatud     = $loom['kiibistatud'];
$pildi_nimi      = $loom['pilt'];

$lubatud_laiendid = ['jpg', 'jpeg', 'png', 'webp'];
$max_pildi_suurus = 2 * 1024 * 1024; // 2 MB

if ($_SERVER['REQUEST_METHOD'] === 'POST') {

    // Muudetud andmete lugemine
    $nimi          = trim($_POST['nimi'] ?? '');
    $liik_id       = filter_input(INPUT_POST, 'liik_id', FILTER_VALIDATE_INT);
    $sugu          = $_POST['sugu'] ?? '';
    $staatus       = $_POST['staatus'] ?? '';
    $sunnikuupaev  = trim($_POST['sunnikuupaev'] ?? '');
    $asukoht       = trim($_POST['asukoht'] ?? '');
    $suurus        = $_POST['suurus'] ?? '';
    $varvus        = trim($_POST['varvus'] ?? '');
    $luhikirjeldus = trim($_POST['luhikirjeldus'] ?? '');
    $kirjeldus     = trim($_POST['kirjeldus'] ?? '');

    $steriliseeritud = isset($_POST['steriliseeritud']) ? 1 : 0;
    $kiibistatud     = isset($_POST['kiibistatud']) ? 1 : 0;

    $pilt = $_FILES['pilt'] ?? null;

    // Andmete kontrollimine
    if ($nimi === '') {
        $vead[] = 'Sisesta looma nimi';
    }

    if (!$liik_id) {
        $vead[] = 'Vali loomaliik';
    }

    if (!in_array($sugu, ['isane', 'emane'], true)) {
        $vead[] = 'Vali sobiv sugu';
    }

    if (!in_array($staatus, ['saadaval', 'broneeritud', 'hoolduses'], true)) {
        $vead[] = 'Vali sobiv staatus';
    }

    // Sünnikuupäev: korrektne kuupäev, mis ei ole tulevikus. Vanus arvutatakse sellest.
    $vanus = 0;
    $kuupaev = DateTime::createFromFormat('Y-m-d', $sunnikuupaev);
    $tana = new DateTime('today');

    if (!$kuupaev || $kuupaev->format('Y-m-d') !== $sunnikuupaev || $kuupaev > $tana) {
        $vead[] = 'Sisesta sobiv sünnikuupäev';
    } else {
        $vanus = $kuupaev->diff($tana)->y;
    }

    if (!in_array($suurus, ['väike', 'keskmine', 'suur'], true)) {
        $vead[] = 'Vali sobiv suurus';
    }

    if ($asukoht === '') {
        $vead[] = 'Sisesta looma asukoht';
    }

    if ($luhikirjeldus === '') {
        $vead[] = 'Sisesta looma lühikirjeldus';
    }

    if ($kirjeldus === '') {
        $vead[] = 'Sisesta looma kirjeldus';
    }

    // Pildi kontroll (pildi vahetamine ei ole kohustuslik)
    $pilt_valitud = $pilt && $pilt['error'] !== UPLOAD_ERR_NO_FILE;

    if ($pilt_valitud) {
        if ($pilt['error'] !== UPLOAD_ERR_OK) {
            $vead[] = 'Pildi üleslaadimine ebaõnnestus';
        } else {
            $laiend = strtolower(pathinfo($pilt['name'], PATHINFO_EXTENSION));

            if (!in_array($laiend, $lubatud_laiendid, true)) {
                $vead[] = 'Pilt peab olema JPG, PNG või WEBP';
            } elseif ($pilt['size'] > $max_pildi_suurus) {
                $vead[] = 'Pilt võib olla kuni 2 MB';
            } elseif (getimagesize($pilt['tmp_name']) === false) {
                $vead[] = 'Fail ei ole korrektne pilt';
            }
        }
    }

    // Uue pildi salvestamine (kui pilti ei valitud, jääb senine pilt)
    if (empty($vead) && $pilt_valitud) {
        $puhas_nimi = preg_replace('/[^A-Za-z0-9._-]/', '_', basename($pilt['name']));
        $uus_pildi_nimi = time() . '_' . $puhas_nimi;
        $pildi_asukoht = __DIR__ . '/../assets/images/' . $uus_pildi_nimi;

        if (move_uploaded_file($pilt['tmp_name'], $pildi_asukoht)) {
            $pildi_nimi = $uus_pildi_nimi;
        } else {
            $vead[] = 'Pildi salvestamine kausta ebaõnnestus';
        }
    }

    // Looma uuendamine andmebaasis
    if (empty($vead)) {
        try {
            $stmt = $conn->prepare("
                UPDATE loomad
                SET nimi = ?, vanus = ?, liik_id = ?, sugu = ?, staatus = ?, sunnikuupaev = ?,
                    asukoht = ?, suurus = ?, varvus = ?, luhikirjeldus = ?, kirjeldus = ?,
                    steriliseeritud = ?, kiibistatud = ?, pilt = ?
                WHERE id = ?
            ");

            $stmt->bind_param(
                "siissssssssiisi",
                $nimi, $vanus, $liik_id, $sugu, $staatus, $sunnikuupaev, $asukoht, $suurus, $varvus,
                $luhikirjeldus, $kirjeldus, $steriliseeritud, $kiibistatud, $pildi_nimi, $id
            );

            $stmt->execute();

            header('Location: animals.php?updated=1');
            exit;
        } catch (mysqli_sql_exception $e) {
            $vead[] = 'Looma muutmine ebaõnnestus: ' . $e->getMessage();
        }
    }
}

$pageTitle = 'Administraator – Muuda looma';
require_once('includes/header.php');
?>

<!-- Edit animal form -->
<main class="py-5">

    <div class="container-xl">

        <div class="d-flex justify-content-between align-items-center mb-4">
            <div>
                <h1 class="h2 fw-bold mb-1">Muuda looma: <?= htmlspecialchars($loom['nimi']) ?></h1>
                <p class="text-muted mb-0">
                    Muuda looma andmeid ja salvesta muudatused.
                </p>
            </div>

            <a href="animals.php" class="btn btn-outline-secondary">
                <i class="fa-solid fa-arrow-left me-2"></i>
                Tagasi loomade juurde
            </a>
        </div>

        <?php if (!empty($vead)) { ?>
            <div class="alert alert-danger">
                <ul class="mb-0">
                    <?php foreach ($vead as $viga) { ?>
                        <li><?= htmlspecialchars($viga) ?></li>
                    <?php } ?>
                </ul>
            </div>
        <?php } ?>

        <form action="animal_edit.php?id=<?= $id ?>" method="post" enctype="multipart/form-data">
            <div class="row g-4">
                <div class="col-lg-8">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-4">Looma põhiandmed</h2>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <label for="nimi" class="form-label fw-semibold">Nimi</label>
                                    <input type="text" name="nimi" id="nimi" class="form-control"
                                        value="<?= htmlspecialchars($nimi) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="liik_id" class="form-label fw-semibold">Liik</label>
                                    <select name="liik_id" id="liik_id" class="form-select" required>
                                        <option value="">Vali liik</option>
                                        <?php while ($liik = $result_liigid->fetch_assoc()) { ?>
                                            <option value="<?= $liik['id'] ?>" <?= (int)$liik_id === (int)$liik['id'] ? 'selected' : '' ?>>
                                                <?= htmlspecialchars($liik['nimetus']) ?>
                                            </option>
                                        <?php } ?>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="sugu" class="form-label fw-semibold">Sugu</label>
                                    <select name="sugu" id="sugu" class="form-select" required>
                                        <option value="">Vali sugu</option>
                                        <option value="isane" <?= $sugu === 'isane' ? 'selected' : '' ?>>Isane</option>
                                        <option value="emane" <?= $sugu === 'emane' ? 'selected' : '' ?>>Emane</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="staatus" class="form-label fw-semibold">Staatus</label>
                                    <select name="staatus" id="staatus" class="form-select" required>
                                        <option value="">Vali staatus</option>
                                        <option value="saadaval" <?= $staatus === 'saadaval' ? 'selected' : '' ?>>Saadaval</option>
                                        <option value="broneeritud" <?= $staatus === 'broneeritud' ? 'selected' : '' ?>>Broneeritud</option>
                                        <option value="hoolduses" <?= $staatus === 'hoolduses' ? 'selected' : '' ?>>Hoolduses</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="sunnikuupaev" class="form-label fw-semibold">Sünnikuupäev</label>
                                    <input type="date" name="sunnikuupaev" id="sunnikuupaev" class="form-control"
                                        max="<?= date('Y-m-d') ?>" value="<?= htmlspecialchars($sunnikuupaev) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="asukoht" class="form-label fw-semibold">Asukoht</label>
                                    <input type="text" name="asukoht" id="asukoht" class="form-control"
                                        value="<?= htmlspecialchars($asukoht) ?>" required>
                                </div>

                                <div class="col-md-6">
                                    <label for="suurus" class="form-label fw-semibold">Suurus</label>
                                    <select name="suurus" id="suurus" class="form-select" required>
                                        <option value="">Vali suurus</option>
                                        <option value="väike" <?= $suurus === 'väike' ? 'selected' : '' ?>>Väike</option>
                                        <option value="keskmine" <?= $suurus === 'keskmine' ? 'selected' : '' ?>>Keskmine</option>
                                        <option value="suur" <?= $suurus === 'suur' ? 'selected' : '' ?>>Suur</option>
                                    </select>
                                </div>

                                <div class="col-md-6">
                                    <label for="varvus" class="form-label fw-semibold">Värvus</label>
                                    <input type="text" name="varvus" id="varvus" class="form-control"
                                        value="<?= htmlspecialchars($varvus) ?>">
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-4">Kirjeldus</h2>

                            <div class="mb-3">
                                <label for="luhikirjeldus" class="form-label fw-semibold">Lühikirjeldus</label>
                                <input type="text" name="luhikirjeldus" id="luhikirjeldus" class="form-control"
                                    value="<?= htmlspecialchars($luhikirjeldus) ?>" required>
                            </div>

                            <div>
                                <label for="kirjeldus" class="form-label fw-semibold">Pikem kirjeldus</label>
                                <textarea name="kirjeldus" id="kirjeldus" class="form-control" rows="7"
                                    required><?= htmlspecialchars($kirjeldus) ?></textarea>
                            </div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-4">Terviseandmed</h2>

                            <div class="row g-3">
                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="steriliseeritud" id="steriliseeritud"
                                            class="form-check-input" value="1" <?= $steriliseeritud ? 'checked' : '' ?>>
                                        <label for="steriliseeritud" class="form-check-label">Loom on steriliseeritud või kastreeritud</label>
                                    </div>
                                </div>

                                <div class="col-md-6">
                                    <div class="form-check">
                                        <input type="checkbox" name="kiibistatud" id="kiibistatud"
                                            class="form-check-input" value="1" <?= $kiibistatud ? 'checked' : '' ?>>
                                        <label for="kiibistatud" class="form-check-label">Loom on kiibistatud</label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="col-lg-4">
                    <div class="card shadow-sm border-0 mb-4">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-4">Looma pilt</h2>

                            <img src="../assets/images/<?= htmlspecialchars($pildi_nimi) ?>"
                                alt="<?= htmlspecialchars($nimi) ?>" class="img-fluid rounded mb-3"
                                onerror="this.onerror=null;this.src='../assets/images/logo_icon.svg'">

                            <label for="pilt" class="form-label fw-semibold">Vaheta pilt (JPG, PNG või WEBP, kuni 2 MB)</label>
                            <input type="file" name="pilt" id="pilt" class="form-control" accept=".jpg,.jpeg,.png,.webp">
                            <div class="form-text">Kui pilti ei vali, jääb senine pilt alles.</div>
                        </div>
                    </div>

                    <div class="card shadow-sm border-0">
                        <div class="card-body p-4">
                            <h2 class="h5 fw-bold mb-3">Salvestamine</h2>
                            <p class="text-muted">Kontrolli enne salvestamist, et kõik looma andmed oleksid õiged.</p>
                            <button type="submit" class="btn btn-success w-100 mb-2">
                                <i class="fa-solid fa-floppy-disk me-2"></i> Salvesta muudatused
                            </button>
                            <a href="animals.php" class="btn btn-outline-secondary w-100">Tühista</a>
                        </div>
                    </div>
                </div>
            </div>
        </form>
    </div>
</main>
<!-- Edit animal form end -->

<?php require_once('includes/footer.php'); ?>
