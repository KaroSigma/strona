<?php

session_start();

$conn = new mysqli($host, $imie, $nazwisko, $email, $db);
if ($conn->connect_error) {
    die("Błąd połączenia: " . $conn->connect_error);
}


$imie = $_POST['imie'];
$nazwisko = $_POST['nazwisko'];
$email = $_POST['email'];
$haslo = $_POST['haslo'];


$hashed_password = password_hash($haslo, PASSWORD_DEFAULT);

$sql = "INSERT INTO user ($imie, $nazwisko, $email, $haslo) VALUES (?, ?)";
$stmt = $conn->prepare($sql);
$stmt->bind_param("ss", $imie, $hashed_password);

if ($stmt->execute()) {
    echo "Rejestracja zakończona sukcesem!";
} else {
    echo "Błąd: " . $stmt->error;
}

$stmt->close();
$conn->close();
?>
