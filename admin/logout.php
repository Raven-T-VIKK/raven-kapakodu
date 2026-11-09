<?php
session_start();

// Eemalda kõik sessiooni muutujad ja lõpeta sessioon
session_unset();
session_destroy();

// Tagasi sisselogimislehele (login.php asub kaustas raven-kapakodu)
header('Location: ../login.php');
exit;
