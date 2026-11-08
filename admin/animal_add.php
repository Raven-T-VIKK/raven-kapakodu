<?php
require_once('includes/config.php');
$pageTitle = 'Administraator – Lisa uus loom';
require_once('includes/header.php');
?>

<!-- Add animal form -->
    <main class="py-5">

        <div class="container-xl">

            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h1 class="h2 fw-bold mb-1">Lisa uus loom</h1>
                    <p class="text-muted mb-0">
                        Sisesta looma andmed ja lisa ta varjupaiga nimekirja.
                    </p>
                </div>

                <a href="animals.php" class="btn btn-outline-secondary">
                    <i class="fa-solid fa-arrow-left me-2"></i>
                    Tagasi loomade juurde
                </a>
            </div>

            <form>
                <div class="row g-4">
                    <div class="col-lg-8">
                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">
                                <h2 class="h5 fw-bold mb-4">Looma põhiandmed</h2>
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name" class="form-label fw-semibold">
                                            Nimi
                                        </label>

                                        <input type="text" class="form-control" id="name"
                                            placeholder="Sisesta looma nimi" required>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="species" class="form-label fw-semibold">
                                            Liik
                                        </label>

                                        <select class="form-select" id="species" required>
                                            <option value="" selected disabled>Vali liik</option>
                                            <option value="dog">Koer</option>
                                            <option value="cat">Kass</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="gender" class="form-label fw-semibold">
                                            Sugu
                                        </label>

                                        <select class="form-select" id="gender" required>
                                            <option value="" selected disabled>Vali sugu</option>
                                            <option value="male">Isane</option>
                                            <option value="female">Emane</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="status" class="form-label fw-semibold">
                                            Staatus
                                        </label>

                                        <select class="form-select" id="status" required>
                                            <option value="" selected disabled>Vali staatus</option>
                                            <option value="available">Koduta</option>
                                            <option value="care">Hoolduses</option>
                                            <option value="reserved">Broneeritud</option>
                                            <option value="adopted">Adopteeritud</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="birthdate" class="form-label fw-semibold">
                                            Sünnikuupäev
                                        </label>

                                        <input type="date" class="form-control" id="birthdate">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="location" class="form-label fw-semibold">
                                            Asukoht
                                        </label>

                                        <input type="text" class="form-control" id="location"
                                            placeholder="Näiteks Haapsalu varjupaik">
                                    </div>

                                    <div class="col-md-6">
                                        <label for="size" class="form-label fw-semibold">
                                            Suurus
                                        </label>

                                        <select class="form-select" id="size">
                                            <option value="" selected disabled>Vali suurus</option>
                                            <option value="small">Väike</option>
                                            <option value="medium">Keskmine</option>
                                            <option value="large">Suur</option>
                                        </select>
                                    </div>

                                    <div class="col-md-6">
                                        <label for="color" class="form-label fw-semibold">
                                            Värvus
                                        </label>

                                        <input type="text" class="form-control" id="color"
                                            placeholder="Näiteks must ja valge">
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0 mb-4">
                            <div class="card-body p-4">

                                <h2 class="h5 fw-bold mb-4">Kirjeldus</h2>

                                <div class="mb-3">
                                    <label for="shortDescription" class="form-label fw-semibold">
                                        Lühikirjeldus
                                    </label>

                                    <input type="text" class="form-control" id="shortDescription"
                                        placeholder="Lühike tutvustus loomakaardi jaoks">
                                </div>

                                <div>
                                    <label for="description" class="form-label fw-semibold">
                                        Pikem kirjeldus
                                    </label>

                                    <textarea class="form-control" id="description" rows="7"
                                        placeholder="Kirjelda looma iseloomu, käitumist ja vajadusi"></textarea>
                                </div>
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">

                                <h2 class="h5 fw-bold mb-4">Terviseandmed</h2>

                                <div class="row g-3">

                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="neutered">

                                            <label class="form-check-label" for="neutered">
                                                Loom on steriliseeritud või kastreeritud
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="chipped">

                                            <label class="form-check-label" for="chipped">
                                                Loom on kiibistatud
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="vaccinated">

                                            <label class="form-check-label" for="vaccinated">
                                                Loom on vaktsineeritud
                                            </label>
                                        </div>
                                    </div>

                                    <div class="col-md-6">
                                        <div class="form-check">
                                            <input class="form-check-input" type="checkbox" id="treated">

                                            <label class="form-check-label" for="treated">
                                                Loom on saanud parasiiditõrje
                                            </label>
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

                                <div class="border rounded text-center p-4 mb-3 bg-light">
                                    <i class="fa-regular fa-image fa-3x text-muted mb-3"></i>

                                    <p class="text-muted mb-0">
                                        Vali loomale sobiv pilt.
                                    </p>
                                </div>

                                <label for="image" class="form-label fw-semibold">
                                    Laadi pilt üles
                                </label>

                                <input class="form-control" type="file" id="image" accept="image/*">
                            </div>
                        </div>

                        <div class="card shadow-sm border-0">
                            <div class="card-body p-4">

                                <h2 class="h5 fw-bold mb-3">Salvestamine</h2>

                                <p class="text-muted">
                                    Kontrolli enne salvestamist, et kõik looma andmed oleksid õiged.
                                </p>

                                <button type="submit" class="btn btn-success w-100 mb-2">
                                    <i class="fa-solid fa-floppy-disk me-2"></i>
                                    Salvesta loom
                                </button>

                                <a href="animals.php" class="btn btn-outline-secondary w-100">
                                    Tühista
                                </a>
                            </div>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
    <!-- Add animal form end -->

<?php require_once('includes/footer.php'); ?>
