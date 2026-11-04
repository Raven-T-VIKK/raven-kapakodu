<?php
require_once('includes/config.php');

// Kontrollime, kas ID on olemas ja on kehtiv täisarv
$id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);
if ($id === null || $id === false) {
    header("Location: index.php");
    exit;
}

// Loome SQL-päringu, et leida loom vastavalt ID-le
$sql = "
    SELECT
        loomad.*,
        liigid.nimetus AS liik,
        toud.nimetus AS toug
    FROM loomad
    LEFT JOIN liigid ON loomad.liik_id = liigid.id
    LEFT JOIN toud ON loomad.toug_id = toud.id
    WHERE loomad.id = ?
";

// Valmistame ette päringu
$stmt = $conn->prepare($sql);
if ($stmt === false) {
    die("Päringu ettevalmistamine ebaõnnestus: " . $conn->error);
}

// Seome parameetri päringuga ja käivitame
$stmt->bind_param("i", $id);
if ($stmt->execute() === false) {
    die("Päringu täitmine ebaõnnestus: " . $stmt->error);
}

// Saame tulemuse
$result = $stmt->get_result();
if ($result->num_rows === 0) {
    header("Location: index.php");
    exit;
}

$row = $result->fetch_assoc();
?>
<!doctype html>
<html lang="et">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="stylesheet" href="assets/css/style.css">
    <title><?= htmlspecialchars($row['nimi']) ?> – Raven Käpakodu</title>
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
    <!-- Animal details -->
    <div class="container py-4">

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="index.php" class="text-decoration-none">Avaleht</a></li>
                <li class="breadcrumb-item"><a href="all_animals.php" class="text-decoration-none">Loomad</a></li>
                <li class="breadcrumb-item active" aria-current="page"><?= htmlspecialchars($row['nimi']) ?></li>
            </ol>
        </nav>

        <div class="card shadow-sm border-0">
            <div class="card-body p-4">
                <div class="row g-4">
                    <div class="col-md-5">
                        <img src="assets/images/<?= htmlspecialchars($row['pilt']) ?>" class="img-fluid rounded" alt="<?= htmlspecialchars($row['nimi']) ?>">
                    </div>
                    <div class="col-md-7">
                        <h1 class="fw-bold"><?= htmlspecialchars($row['nimi']) ?></h1>
                        <div class="d-flex flex-wrap gap-3 small text-muted mb-3">
                            <span><i class="fa-solid fa-paw text-success me-1"></i> <?= htmlspecialchars($row['liik']) ?></span>
                            <span><i class="fa-solid fa-mars text-success me-1"></i> <?= htmlspecialchars($row['sugu']) ?></span>
                            <span><i class="fa-regular fa-calendar text-success me-1"></i> <?= $row['vanus'] ?> a</span>
                        </div>
                        <p>
                            <?= htmlspecialchars($row['kirjeldus']) ?>
                        </p>
                        <table class="table table-sm mb-4">
                            <tbody>
                                <tr>
                                    <th scope="row" class="w-25">Tõug</th>
                                    <td><?= htmlspecialchars($row['toug'] ?? '–') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Suurus</th>
                                    <td><?= htmlspecialchars($row['suurus'] ?? '–') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Värvus</th>
                                    <td><?= htmlspecialchars($row['varvus'] ?? '–') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Sünniaeg</th>
                                    <td><?= htmlspecialchars($row['sunnikuupaev'] ?? '–') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Asukoht</th>
                                    <td><?= htmlspecialchars($row['asukoht'] ?? '–') ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Steriliseeritud</th>
                                    <td><?= $row['steriliseeritud'] == 1 ? 'Jah' : 'Ei' ?></td>
                                </tr>
                                <tr>
                                    <th scope="row">Kiibistatud</th>
                                    <td><?= $row['kiibistatud'] == 1 ? 'Jah' : 'Ei' ?></td>
                                </tr>
                            </tbody>
                        </table>
                        <div class="d-flex flex-wrap gap-2">
                            <a href="contact.html" class="btn btn-success">
                                <i class="fa-solid fa-heart me-2"></i> Soovin tutvuda
                            </a>
                            <a href="all_animals.php" class="btn btn-outline-secondary">
                                <i class="fa-solid fa-arrow-left me-2"></i> Tagasi nimekirja
                            </a>
                        </div>
                    </div>
                </div>
            </div>
        </div>

    </div>
    <!-- Animal details end -->
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