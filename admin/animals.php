<?php
require_once('includes/config.php');

// Mitu looma ühel lehel kuvatakse
$lehel = 10;

// Lehekülje number aadressirealt (vigane või puuduv väärtus = 1. leht)
$leht = filter_input(INPUT_GET, 'leht', FILTER_VALIDATE_INT);

if (!$leht || $leht < 1) {
    $leht = 1;
}

// Loomade koguarv ja lehekülgede arv
$sql_kokku = "SELECT COUNT(*) AS kokku FROM loomad";
$result_kokku = $conn->query($sql_kokku);
$kokku = (int)$result_kokku->fetch_assoc()['kokku'];

$lehti = max(1, (int)ceil($kokku / $lehel));

// Liiga suur lehe number (nt pärast kustutamist) viiakse viimasele lehele
if ($leht > $lehti) {
    $leht = $lehti;
}

// Millisest kirjest päring alustab
$algus = ($leht - 1) * $lehel;

// Ühe lehekülje loomad koos liigi nimetusega
$sql_loomad = "
    SELECT loomad.id, loomad.nimi, loomad.pilt, loomad.sugu, loomad.vanus, loomad.staatus, loomad.lisatud, liigid.nimetus AS liik
    FROM loomad
    LEFT JOIN liigid ON loomad.liik_id = liigid.id
    ORDER BY loomad.id DESC
    LIMIT ?, ?
";

$stmt = $conn->prepare($sql_loomad);
$stmt->bind_param("ii", $algus, $lehel);
$stmt->execute();

$result_loomad = $stmt->get_result();

// Kuvatavate kirjete vahemik tabeli aluse teksti jaoks
$esimene = $kokku > 0 ? $algus + 1 : 0;
$viimane = min($algus + $lehel, $kokku);

$pageTitle = 'Administraator – Loomad';
require_once('includes/header.php');
?>

<div class="container-xl">
    <!-- Lehe pealkiri -->
    <div class="d-flex justify-content-between align-items-center mb-4">

        <div>
            <h1 class="h2 fw-bold mb-1">Loomad</h1>
            <p class="text-muted mb-0">
                Halda varjupaiga loomi: vaata, lisa, muuda või kustuta.
            </p>
        </div>

        <a href="animal_add.php" class="btn btn-success">
            <i class="fa-solid fa-plus me-2"></i>
            Lisa uus loom
        </a>

    </div>

    <!-- Tagasiside -->
    <?php if (isset($_GET['updated'])) { ?>
        <div class="alert alert-success">Looma andmed muudeti edukalt.</div>
    <?php } ?>

    <?php if (isset($_GET['added'])) { ?>
        <div class="alert alert-success">Loom lisati edukalt.</div>
    <?php } ?>

    <?php if (isset($_GET['deleted'])) { ?>
        <div class="alert alert-success">Loom kustutati edukalt.</div>
    <?php } ?>

    <?php if (isset($_GET['linked'])) { ?>
        <div class="alert alert-warning">Looma ei saa kustutada, sest temaga on seotud päringuid.</div>
    <?php } ?>

    <?php if (isset($_GET['error'])) { ?>
        <div class="alert alert-danger">Looma kustutamine ebaõnnestus.</div>
    <?php } ?>

    <!-- Tabel -->
    <div class="card shadow-sm border-0">

        <div class="table-responsive">

            <table class="table align-middle table-hover mb-0">

                <thead>
                    <tr>
                        <th>Pilt</th>
                        <th>Nimi</th>
                        <th>Liik</th>
                        <th>Sugu</th>
                        <th>Vanus</th>
                        <th>Staatus</th>
                        <th>Lisatud</th>
                        <th class="text-end">Tegevused</th>
                    </tr>
                </thead>

                <tbody>
                    <?php while ($loom = $result_loomad->fetch_assoc()) { ?>
                        <tr>
                            <td>
                                <img src="../assets/images/<?= htmlspecialchars($loom['pilt']) ?>"
                                    alt="<?= htmlspecialchars($loom['nimi']) ?>" width="60" height="60"
                                    class="rounded object-fit-cover"
                                    onerror="this.onerror=null;this.src='../assets/images/logo_icon.svg'">
                            </td>
                            <td class="fw-semibold"><?= htmlspecialchars($loom['nimi']) ?></td>
                            <td><?= htmlspecialchars($loom['liik'] ?? '–') ?></td>
                            <td><?= htmlspecialchars(ucfirst($loom['sugu'])) ?></td>
                            <td><?= htmlspecialchars($loom['vanus']) ?> a</td>
                            <td><?= htmlspecialchars(ucfirst($loom['staatus'])) ?></td>
                            <td><?= date('d.m.Y', strtotime($loom['lisatud'])) ?></td>
                            <td class="text-end">

                                <a href="animal_edit.php?id=<?= $loom['id'] ?>" class="btn btn-outline-primary btn-sm" title="Muuda">
                                    <i class="fa-solid fa-pen"></i>
                                </a>

                                <form action="animal_delete.php" method="post" class="d-inline"
                                    onsubmit="return confirm('Kas soovid selle looma kustutada?')">
                                    <input type="hidden" name="id" value="<?= $loom['id'] ?>">
                                    <button type="submit" class="btn btn-outline-danger btn-sm" title="Kustuta">
                                        <i class="fa-solid fa-trash"></i>
                                    </button>
                                </form>

                                <a href="../animal.php?id=<?= $loom['id'] ?>" class="btn btn-outline-secondary btn-sm" title="Vaata">
                                    <i class="fa-solid fa-eye"></i>
                                </a>

                            </td>
                        </tr>
                    <?php } ?>
                </tbody>

            </table>

        </div>

        <div class="card-footer bg-white d-flex justify-content-between align-items-center flex-wrap gap-2">
            <small class="text-muted">
                Kuvatakse <?= $esimene ?>–<?= $viimane ?> kokku <?= $kokku ?> loomast.
            </small>

            <?php if ($lehti > 1) { ?>
                <nav aria-label="Loomade leheküljed">
                    <ul class="pagination pagination-sm mb-0">
                        <?php for ($i = 1; $i <= $lehti; $i++) { ?>
                            <li class="page-item <?= $i === $leht ? 'active' : '' ?>">
                                <a class="page-link" href="animals.php?leht=<?= $i ?>"><?= $i ?></a>
                            </li>
                        <?php } ?>
                    </ul>
                </nav>
            <?php } ?>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>