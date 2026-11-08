<?php
require_once('includes/config.php');

// Loomi kokku
$result_loomad = $conn->query("SELECT COUNT(*) AS kokku FROM loomad");
$loomad_kokku = $result_loomad->fetch_assoc()['kokku'];

// Koduta (saadaval) loomi
$result_saadaval = $conn->query("SELECT COUNT(*) AS kokku FROM loomad WHERE staatus = 'saadaval'");
$saadaval_kokku = $result_saadaval->fetch_assoc()['kokku'];

// Broneeritud loomi
$result_broneeritud = $conn->query("SELECT COUNT(*) AS kokku FROM loomad WHERE staatus = 'broneeritud'");
$broneeritud_kokku = $result_broneeritud->fetch_assoc()['kokku'];

// Uusi sõnumeid
$result_sonumid = $conn->query("SELECT COUNT(*) AS kokku FROM paringud WHERE staatus = 'uus'");
$uued_sonumid_kokku = $result_sonumid->fetch_assoc()['kokku'];

// 4 viimati lisatud looma
$sql_viimased_loomad = "
    SELECT loomad.nimi, loomad.vanus, loomad.staatus, liigid.nimetus AS liik
    FROM loomad
    LEFT JOIN liigid ON loomad.liik_id = liigid.id
    ORDER BY loomad.id DESC
    LIMIT 4
";
$result_viimased_loomad = $conn->query($sql_viimased_loomad);

// 4 viimast sõnumit
$sql_viimased_sonumid = "
    SELECT nimi, teema, staatus
    FROM paringud
    ORDER BY id DESC
    LIMIT 4
";
$result_viimased_sonumid = $conn->query($sql_viimased_sonumid);

$pageTitle = 'Administraator – Avaleht';
require_once('includes/header.php');
?>

<!-- Site content -->
<div class="container py-5">
    <h1 class="fw-bold mb-2">Administraatori avaleht</h1>
    <p class="text-muted mb-5">Ülevaade varjupaiga tegevusest.</p>

    <!-- Stat -->
    <div class="row g-4 mb-5">
        <div class="col-lg-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fa-solid fa-paw fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold"><?= $loomad_kokku ?></h2>
                    <p class="mb-0">Looma</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fa-solid fa-house fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold"><?= $saadaval_kokku ?></h2>
                    <p class="mb-0">Koduta</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fa-regular fa-heart fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold"><?= $broneeritud_kokku ?></h2>
                    <p class="mb-0">Broneeritud</p>
                </div>
            </div>
        </div>

        <div class="col-lg-3">
            <div class="card text-center shadow-sm border-0">
                <div class="card-body">
                    <i class="fa-regular fa-envelope fa-3x text-success mb-3"></i>
                    <h2 class="fw-bold"><?= $uued_sonumid_kokku ?></h2>
                    <p class="mb-0">Uusi sõnumeid</p>
                </div>
            </div>
        </div>
    </div>
    <!-- Stat end -->

    <div class="row g-4">
        <!-- Animals -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Viimati lisatud loomad</h2>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nimi</th>
                                <th>Liik</th>
                                <th>Vanus</th>
                                <th>Staatus</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($loom = $result_viimased_loomad->fetch_assoc()) { ?>
                                <?php
                                $staatuse_klassid = [
                                    'saadaval'    => 'bg-success',
                                    'broneeritud' => 'bg-warning text-dark',
                                    'hoolduses'   => 'bg-secondary'
                                ];

                                $staatuse_klass = $staatuse_klassid[$loom['staatus']] ?? 'bg-dark';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($loom['nimi']) ?></td>
                                    <td><?= htmlspecialchars($loom['liik'] ?? '–') ?></td>
                                    <td><?= htmlspecialchars($loom['vanus']) ?> a</td>
                                    <td><span class="badge <?= $staatuse_klass ?>"><?= htmlspecialchars(ucfirst($loom['staatus'])) ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Animals end -->

        <!-- Requests -->
        <div class="col-lg-6">
            <div class="card shadow-sm border-0">
                <div class="card-header bg-white">
                    <h2 class="h4 mb-0">Viimased sõnumid</h2>
                </div>

                <div class="card-body p-0">
                    <table class="table table-hover mb-0">
                        <thead>
                            <tr>
                                <th>Nimi</th>
                                <th>Teema</th>
                                <th>Staatus</th>
                            </tr>
                        </thead>

                        <tbody>
                            <?php while ($sonum = $result_viimased_sonumid->fetch_assoc()) { ?>
                                <?php
                                $sonumi_klassid = [
                                    'uus'        => 'bg-primary',
                                    'menetluses' => 'bg-warning text-dark',
                                    'lopetatud'  => 'bg-success'
                                ];

                                $sonumi_klass = $sonumi_klassid[$sonum['staatus']] ?? 'bg-dark';
                                ?>
                                <tr>
                                    <td><?= htmlspecialchars($sonum['nimi']) ?></td>
                                    <td><?= htmlspecialchars($sonum['teema']) ?></td>
                                    <td><span class="badge <?= $sonumi_klass ?>"><?= htmlspecialchars(ucfirst($sonum['staatus'])) ?></span></td>
                                </tr>
                            <?php } ?>
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
        <!-- Requests end -->
    </div>
</div>
<!-- Site content end -->

<?php require_once('includes/footer.php'); ?>
