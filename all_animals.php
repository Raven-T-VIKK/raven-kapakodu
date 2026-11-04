<?php
require_once('includes/config.php');

// Väärtused aadressirealt (GET)
$otsi  = trim($_GET['otsi'] ?? '');
$liik  = filter_input(INPUT_GET, 'liik', FILTER_VALIDATE_INT);
$sugu  = $_GET['sugu'] ?? '';
$vanus = $_GET['vanus'] ?? '';

// Põhipäring
$sql_loomad = "SELECT loomad.*, liigid.nimetus AS liik
               FROM loomad
               LEFT JOIN liigid ON loomad.liik_id = liigid.id
               WHERE loomad.staatus = 'saadaval'";

$types  = '';
$params = [];

// Otsing nime järgi
if ($otsi !== '') {
    $sql_loomad .= " AND loomad.nimi LIKE ?";
    $types   .= 's';
    $params[] = "%$otsi%";
}

// Liigi filter
if ($liik) {
    $sql_loomad .= " AND loomad.liik_id = ?";
    $types   .= 'i';
    $params[] = $liik;
}

// Soo filter
if ($sugu === 'isane' || $sugu === 'emane') {
    $sql_loomad .= " AND loomad.sugu = ?";
    $types   .= 's';
    $params[] = $sugu;
}

// Vanuse filter (valmis tingimus, küsimärki ei kasutata)
if ($vanus == 1) {
    $sql_loomad .= " AND loomad.sunnikuupaev >= DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
} elseif ($vanus == 2) {
    $sql_loomad .= " AND loomad.sunnikuupaev BETWEEN DATE_SUB(CURDATE(), INTERVAL 5 YEAR)
                     AND DATE_SUB(CURDATE(), INTERVAL 1 YEAR)";
} elseif ($vanus == 3) {
    $sql_loomad .= " AND loomad.sunnikuupaev < DATE_SUB(CURDATE(), INTERVAL 5 YEAR)";
}

$sql_loomad .= " ORDER BY loomad.lisatud DESC";

// Prepared statement
$stmt = $conn->prepare($sql_loomad);
if ($stmt === false) {
    die("Päringu ettevalmistamine ebaõnnestus: " . $conn->error);
}
if ($params) {
    $stmt->bind_param($types, ...$params);
}
if ($stmt->execute() === false) {
    die("Päringu täitmine ebaõnnestus: " . $stmt->error);
}
$result = $stmt->get_result();

// Liigid rippmenüü jaoks
$liigid = $conn->query("SELECT id, nimetus FROM liigid ORDER BY nimetus");
?>
<!doctype html>
<html lang="et">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <title>Raven Käpakodu</title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
</head>

<body>
    <!-- Header section -->
    <div class="container">
        <nav class="navbar navbar-expand-lg">
            <div class="container-fluid">
                <a class="navbar-brand" href="index.php">
                    <img src="assets/images/logo_big.svg" alt="Logo" width="140">
                </a>
                <button class="navbar-toggler" type="button" data-bs-toggle="collapse"
                    data-bs-target="#navbarSupportedContent" aria-controls="navbarSupportedContent"
                    aria-expanded="false" aria-label="Toggle navigation">
                    <span class="navbar-toggler-icon"></span>
                </button>
                <div class="collapse navbar-collapse" id="navbarSupportedContent">
                    <ul class="navbar-nav mx-auto mb-2 mb-lg-0">
                        <li class="nav-item"> <a class="nav-link" href="index.php"><strong>Avaleht</strong></a>
                        </li>
                        <li class="nav-item"> <a class="nav-link active" href="all_animals.php"><strong>Loomad</strong></a> </li>
                        <li class="nav-item"> <a class="nav-link" href="#"><strong>Kuidas aidata</strong></a>
                        </li>
                        <li class="nav-item"> <a class="nav-link" href="#"><strong>Meist</strong></a> </li>
                        <li class="nav-item"> <a class="nav-link" href="contact.html"><strong>Kontakt</strong></a> </li>
                    </ul>
                    <button class="btn btn-success" type="button">Anneta <i class="fa-solid fa-heart-circle-check"></i>
                    </button>

                </div>
            </div>
        </nav>
    </div>
    <!-- Header section end -->
    <!-- Animals page -->
    <div class="container py-5">

        <div class="text-center mb-5">
            <h1 class="fw-bold">Kõik loomad</h1>
            <p class="text-muted">
                Tutvu meie hoolealustega ja leia endale uus parim sõber.
            </p>
        </div>

        <!-- Filter -->
        <form action="" method="get">
            <div class="row g-3 mb-5">

                <div class="col-lg-4">
                    <input type="text" name="otsi" class="form-control" placeholder="Otsi looma nime järgi..."
                        aria-label="Otsi looma nime järgi" value="<?= htmlspecialchars($otsi) ?>">
                </div>

                <div class="col-lg-2">
                    <select name="liik" class="form-select" aria-label="Liik">
                        <option value="">Kõik liigid</option>
                        <?php while ($l = $liigid->fetch_assoc()) : ?>
                            <option value="<?= $l['id'] ?>" <?= $liik == $l['id'] ? 'selected' : '' ?>>
                                <?= htmlspecialchars($l['nimetus']) ?>
                            </option>
                        <?php endwhile; ?>
                    </select>
                </div>

                <div class="col-lg-2">
                    <select name="vanus" class="form-select" aria-label="Vanus">
                        <option value="">Kõik vanused</option>
                        <option value="1" <?= $vanus == 1 ? 'selected' : '' ?>>Alla 1 aasta</option>
                        <option value="2" <?= $vanus == 2 ? 'selected' : '' ?>>1–5 aastat</option>
                        <option value="3" <?= $vanus == 3 ? 'selected' : '' ?>>Üle 5 aasta</option>
                    </select>
                </div>

                <div class="col-lg-2">
                    <select name="sugu" class="form-select" aria-label="Sugu">
                        <option value="">Kõik sood</option>
                        <option value="isane" <?= $sugu === 'isane' ? 'selected' : '' ?>>Isane</option>
                        <option value="emane" <?= $sugu === 'emane' ? 'selected' : '' ?>>Emane</option>
                    </select>
                </div>

                <div class="col-lg-2 d-grid">
                    <button class="btn btn-success" type="submit">Filtreeri</button>
                </div>

            </div>
        </form>
        <!-- Filter end -->

        <div class="row g-4">
            <?php if ($result->num_rows > 0) { ?>
                <?php while ($row = $result->fetch_assoc()) {
                    $badge = match ($row['staatus']) {
                        'saadaval'    => 'bg-success',
                        'broneeritud' => 'bg-warning text-dark',
                        'hoolduses'   => 'bg-secondary',
                        default       => 'bg-dark'
                    };
                ?>
                    <!-- single animal card -->
                    <div class="col-sm-6 col-lg-3">
                        <div class="card h-100 shadow-sm border-0">
                            <img onerror="this.onerror=null;this.src='assets/images/logo_icon.svg'"
                                src="assets/images/<?= htmlspecialchars($row['pilt']) ?>" class="card-img-top"
                                alt="<?= htmlspecialchars($row['nimi']) ?>">
                            <div class="card-body d-flex flex-column">
                                <h2 class="h5 card-title fw-bold"><?= htmlspecialchars($row['nimi']) ?></h2>
                                <p class="small text-muted mb-2">
                                    <?= htmlspecialchars($row['liik']) ?> • <?= htmlspecialchars($row['sugu']) ?> • <?= $row['vanus'] ?> a
                                </p>
                                <p class="card-text small"><?= htmlspecialchars($row['luhikirjeldus']) ?></p>
                                <p class="mb-3"><span class="badge <?= $badge ?>"><?= htmlspecialchars(ucfirst($row['staatus'])) ?></span></p>
                                <a href="animal.php?id=<?= $row['id'] ?>" class="btn btn-outline-success btn-sm w-100 mt-auto">Vaata lähemalt</a>
                            </div>
                        </div>
                    </div>
                    <!-- single animal card end -->
                <?php } ?>
            <?php } else { ?>
                <div class="col-12">
                    <div class="alert alert-warning">Otsingule ei leitud ühtegi vastet.</div>
                </div>
            <?php } ?>
        </div>

    </div>
    <!-- Animals page end -->
    <!-- Footer -->
    <footer class="bg-white border-top mt-5">
        <div class="container py-5">

            <div class="row gy-4">

                <div class="col-lg-3">
                    <img src="assets/images/logo_big.svg" alt="Loomade Varjupaik" height="50">

                    <p class="mt-3">
                        Aitame koduta ja hätta sattunud loomi ning leiame neile armastavad kodud.
                    </p>

                    <div class="d-flex gap-3 mt-4">
                        <a href="#" class="btn btn-outline-secondary rounded-circle">
                            <i class="fa-brands fa-facebook-f"></i>
                        </a>

                        <a href="#" class="btn btn-outline-secondary rounded-circle">
                            <i class="fa-brands fa-instagram"></i>
                        </a>

                        <a href="#" class="btn btn-outline-secondary rounded-circle">
                            <i class="fa-regular fa-envelope"></i>
                        </a>
                    </div>
                </div>

                <div class="col-lg-3">
                    <p class="h5 fw-bold mb-3">Kiirlingid</p>

                    <ul class="list-unstyled">
                        <li class="mb-2"><a href="index.php" class="text-decoration-none text-dark">Avaleht</a></li>
                        <li class="mb-2"><a href="all_animals.php" class="text-decoration-none text-dark">Loomad</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Kuidas aidata</a></li>
                        <li class="mb-2"><a href="#" class="text-decoration-none text-dark">Meist</a></li>
                        <li><a href="contact.html" class="text-decoration-none text-dark">Kontakt</a></li>
                    </ul>
                </div>

                <div class="col-lg-3">
                    <p class="h5 fw-bold mb-3">Kontakt</p>

                    <p><i class="fa-solid fa-location-dot me-2"></i>Varjupaiga tee 1, Tallinn 12345</p>
                    <p><i class="fa-solid fa-phone me-2"></i>+372 5555 1234</p>
                    <p><i class="fa-regular fa-envelope me-2"></i>info@varjupaik.ee</p>
                    <p><i class="fa-regular fa-clock me-2"></i>E–P 10:00–18:00</p>
                </div>

                <div class="col-lg-3">
                    <p class="h5 fw-bold mb-3">Toeta meid</p>

                    <p>
                        Sinu annetus aitab meil pakkuda loomadele paremat elu.
                    </p>

                    <button class="btn btn-success px-4" type="button">
                        Anneta
                    </button>
                </div>

            </div>

            <hr class="my-4">

            <div class="d-flex justify-content-between align-items-center flex-wrap">
                <small>&copy; 2027 Loomade Varjupaik. Kõik õigused kaitstud.</small>

                <div>
                    <a href="#" class="text-decoration-none text-muted me-3">Küpsiste eelistused</a>
                    <a href="#" class="text-decoration-none text-muted">Privaatsuspoliitika</a>
                </div>
            </div>

        </div>
    </footer>
    <!-- Footer end -->

    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-FKyoEForCGlyvwx9Hj09JcYn3nv7wiPVlz7YYwJrWVcXK/BmnVDxM+D2scQbITxI"
        crossorigin="anonymous"></script>
</body>

</html>
