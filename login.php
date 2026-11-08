<?php
require_once 'includes/config.php';
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

                    <form action="admin/index.php">

                        <div class="mb-3">
                            <label for="loginEmail" class="form-label fw-semibold">
                                E-post
                            </label>

                            <div class="input-group">
                                <span class="input-group-text">
                                    <i class="fa-regular fa-envelope"></i>
                                </span>

                                <input type="email" class="form-control" id="loginEmail" placeholder="Sinu e-post">
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

                                <input type="password" class="form-control" id="loginPassword" placeholder="Sinu parool">

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