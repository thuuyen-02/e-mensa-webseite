<?php
// Salt
$salt = "honig";
// Passwort
$password = "admin123";
// Passwort mit Salt kombinieren
$saltedPassword = $salt . $password;
// Passwort-Hash
$hash = password_hash($saltedPassword, PASSWORD_BCRYPT);
echo "Der zu speichernde Hash für das Passwort ist: " . $hash;
