<?php
// Administraatori vaated kasutavad sama andmebaasiühendust, mis avalik veebileht.
// Nii on parool ainult ühes failis (includes/config.php).
require_once __DIR__ . '/../../includes/config.php';

// Kõik admin-lehed lisavad selle faili esimesena, seega kontrollime sisselogimist siin.
// See kaitseb ka lehti, mis teevad tööd enne päise lisamist (animal_add, animal_edit, animal_delete).
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

if (!isset($_SESSION['kasutaja'])) {
    header('Location: ../login.php');
    exit;
}
