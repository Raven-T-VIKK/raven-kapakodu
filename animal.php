<?php
require_once 'includes/config.php';

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

$pageTitle = $row['nimi'] . ' – Raven Käpakodu';
require_once 'includes/header.php';
?>
<!-- Animal details -->
<div class="container py-4">

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
                        <a href="contact.php" class="btn btn-success">
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

<?php require_once 'includes/footer.php'; ?>