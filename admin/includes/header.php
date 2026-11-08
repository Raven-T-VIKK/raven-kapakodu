<?php
// Praegune leht aktiivse menüüpunkti märkimiseks
$currentPage = basename($_SERVER['PHP_SELF']);

// Lehe pealkiri (leht võib määrata oma $pageTitle enne päise lisamist)
$pageTitle = $pageTitle ?? 'Administraator';
?>
<!DOCTYPE html>
<html lang="et">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title><?= htmlspecialchars($pageTitle) ?></title>
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.8/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-sRIl4kxILFvY47J16cr9ZwB07vP4J8+LH7qKQnuqkuIAvNWLzeN8tE5YBujZqJLB" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/7.0.1/css/all.min.css">
    <link rel="stylesheet" href="../assets/css/style.css">
</head>

<body>

    <!-- Admin nav -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white border-bottom shadow-sm mb-4">
        <div class="container">
            <a class="navbar-brand" href="index.php"><img src="../assets/images/logo_big.svg" alt="Loomade Varjupaik" height="50"></a>

            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#adminMenu"
                aria-controls="adminMenu" aria-expanded="false" aria-label="Toggle navigation"><span class="navbar-toggler-icon"></span></button>

            <div class="collapse navbar-collapse" id="adminMenu">
                <ul class="navbar-nav me-auto">
                    <li class="nav-item"><a class="nav-link <?= $currentPage === 'index.php' ? 'active' : '' ?>" href="index.php"><i class="fa-solid fa-house me-1"></i> Avaleht</a></li>
                    <li class="nav-item"><a class="nav-link <?= in_array($currentPage, ['animals.php', 'animal_edit.php'], true) ? 'active' : '' ?>" href="animals.php"><i class="fa-solid fa-paw me-1"></i> Loomad</a></li>
                    <li class="nav-item"><a class="nav-link <?= $currentPage === 'animal_add.php' ? 'active' : '' ?>" href="animal_add.php"><i class="fa-solid fa-plus me-1"></i> Lisa loom</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-tags me-1"></i> Kategooriad</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fa-solid fa-users me-1"></i> Kasutajad</a></li>
                    <li class="nav-item"><a class="nav-link" href="#"><i class="fa-regular fa-envelope me-1"></i> Päringud</a></li>
                </ul>

                <span class="navbar-text me-3"><i class="fa-solid fa-user-shield text-success me-1"></i> Admin</span>
                <a href="../login.php" class="btn btn-outline-danger"><i class="fa-solid fa-right-from-bracket me-2"></i> Logi välja</a>
            </div>
        </div>
    </nav>
    <!-- Admin nav end -->