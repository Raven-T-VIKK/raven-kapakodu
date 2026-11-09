<?php
// TESTFAIL: otsib töötaja e-posti järgi ja kontrollib parooli.
require_once 'includes/config.php';

$teade = '';

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $email = trim($_POST['email'] ?? '');
    $sisestatud_parool = $_POST['parool'] ?? '';

    $stmt = $conn->prepare("SELECT id, nimi, email, parool, roll FROM tootajad WHERE email = ?");
    $stmt->bind_param("s", $email);
    $stmt->execute();

    $result = $stmt->get_result();
    $tootaja = $result->fetch_assoc();

    if ($tootaja && password_verify($sisestatud_parool, $tootaja['parool'])) {
        $teade = 'Kasutaja andmed on õiged. Tere, ' . $tootaja['nimi'] . ' (' . $tootaja['roll'] . ')!';
    } else {
        $teade = 'Vale e-posti aadress või parool.';
    }
}
?>
<!DOCTYPE html>
<html lang="et">
<head>
    <meta charset="UTF-8">
    <title>Sisselogimise test</title>
</head>
<body>
    <h1>Sisselogimise test</h1>

    <?php if ($teade !== '') { ?>
        <p><strong><?= htmlspecialchars($teade) ?></strong></p>
    <?php } ?>

    <form method="post">
        <p><label>E-post: <input type="email" name="email" required></label></p>
        <p><label>Parool: <input type="password" name="parool" required></label></p>
        <button type="submit">Kontrolli</button>
    </form>
</body>
</html>
