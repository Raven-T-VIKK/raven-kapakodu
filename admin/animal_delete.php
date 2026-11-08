<?php
require_once('includes/config.php');

// Kustutada saab ainult POST-päringuga
if ($_SERVER['REQUEST_METHOD'] !== 'POST') {
    header('Location: animals.php');
    exit;
}

// ID kontroll
$id = filter_input(INPUT_POST, 'id', FILTER_VALIDATE_INT);

if (!$id) {
    header('Location: animals.php?error=1');
    exit;
}

try {
    $stmt = $conn->prepare("DELETE FROM loomad WHERE id = ?");
    $stmt->bind_param("i", $id);
    $stmt->execute();

    if ($stmt->affected_rows > 0) {
        header('Location: animals.php?deleted=1');
    } else {
        header('Location: animals.php?error=1');
    }
} catch (mysqli_sql_exception $e) {
    // Looma külge on seotud päringuid (võõrvõtme piirang)
    header('Location: animals.php?linked=1');
}

exit;
