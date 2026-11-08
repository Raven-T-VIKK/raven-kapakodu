<?php
// Leivapuru. Eeldab, et $currentPage ja $conn on header.php-s juba olemas.

$bc_pages = [
    'all_animals.php' => 'Kõik loomad',
    'contact.php'     => 'Kontakt',
    'login.php'       => 'Logi sisse'
];

$breadcrumbs = [];

if (isset($bc_pages[$currentPage])) {

    $breadcrumbs = [
        'Avaleht' => 'index.php',
        $bc_pages[$currentPage] => null
    ];

} elseif ($currentPage === 'animal.php') {

    $bc_id = filter_input(INPUT_GET, 'id', FILTER_VALIDATE_INT);

    if ($bc_id) {

        $bc_stmt = $conn->prepare("SELECT nimi FROM loomad WHERE id = ?");
        $bc_stmt->bind_param("i", $bc_id);
        $bc_stmt->execute();
        $bc_loom = $bc_stmt->get_result()->fetch_assoc();

        if ($bc_loom) {
            $breadcrumbs = [
                'Avaleht'     => 'index.php',
                'Kõik loomad' => 'all_animals.php',
                $bc_loom['nimi'] => null
            ];
        }
    }
}

if (!empty($breadcrumbs)) {
?>
    <!-- Breadcrumbs -->
    <div class="container">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">

                <?php foreach ($breadcrumbs as $text => $url) { ?>

                    <?php if ($url) { ?>

                        <li class="breadcrumb-item">
                            <a href="<?= htmlspecialchars($url) ?>">
                                <?= htmlspecialchars($text) ?>
                            </a>
                        </li>

                    <?php } else { ?>

                        <li class="breadcrumb-item active" aria-current="page">
                            <?= htmlspecialchars($text) ?>
                        </li>

                    <?php } ?>

                <?php } ?>

            </ol>
        </nav>
    </div>
    <!-- Breadcrumbs end -->
<?php } ?>
