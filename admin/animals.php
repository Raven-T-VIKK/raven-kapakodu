<?php
require_once('includes/config.php');

// Kõik loomad koos liigi nimetusega
$sql_loomad = "
    SELECT loomad.id, loomad.nimi, loomad.pilt, loomad.sugu, loomad.vanus, loomad.staatus, loomad.lisatud, liigid.nimetus AS liik
    FROM loomad
    LEFT JOIN liigid ON loomad.liik_id = liigid.id
    ORDER BY loomad.id DESC
";

$result_loomad = $conn->query($sql_loomad);
if (!$result_loomad) {
    die("Päring ebaõnnestus: " . $conn->error);
}

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

        <div class="card-footer bg-white">
            <small class="text-muted">
                Kokku <?= $result_loomad->num_rows ?> looma.
            </small>
        </div>
    </div>
</div>

<?php require_once('includes/footer.php'); ?>
