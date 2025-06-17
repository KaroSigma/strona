<?php
session_start();

if (!isset($_GET['id'])) {
    header('Location: index.php');
    exit;
}

$order_id = (int)$_GET['id'];

// Połączenie z bazą danych
$mysqli = new mysqli("localhost", "root", "", "słodka_bułka");
if ($mysqli->connect_error) {
    die("Błąd połączenia: " . $mysqli->connect_error);
}

// Pobierz zamówienie
$stmt = $mysqli->prepare("SELECT * FROM zamowienie WHERE id = ?");
$stmt->bind_param("i", $order_id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    die("Zamówienie nie istnieje.");
}

$zamowienie = $result->fetch_assoc();
?>

<!DOCTYPE html>
<html>
<head>
    <title>Potwierdzenie zamówienia - Słodka Bułka</title>
    <meta name="author" content="">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="styleprodukt.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <style>
        .potwierdzenie-container {
            max-width: 800px;
            margin: 50px auto;
            padding: 30px;
            text-align: center;
            background-color: #f8f9fa;
            border-radius: 8px;
        }
        
        .potwierdzenie-container h1 {
            color: #27ae60;
        }
        
        .potwierdzenie-info {
            text-align: left;
            margin: 30px 0;
            padding: 20px;
            background-color: white;
            border-radius: 4px;
        }
        
        .btn-continue {
            display: inline-block;
            background-color: #27ae60;
            color: white;
            padding: 12px 25px;
            border-radius: 4px;
            text-decoration: none;
            font-size: 1.1em;
        }
    </style>
</head>
<body>
    
    <main class="potwierdzenie-container">
        <h1>Dziękujemy za zamówienie!</h1>
        <p>Twoje zamówienie zostało przyjęte i jest przetwarzane.</p>
        
        <div class="potwierdzenie-info">
            <p><strong>Numer zamówienia:</strong> #<?= $zamowienie['id'] ?></p>
            <p><strong>Data:</strong> <?= $zamowienie['data_zamowienia'] ?></p>
            <p><strong>Status:</strong> <?= $zamowienie['status'] ?></p>
            <p><strong>Suma:</strong> <?= number_format($zamowienie['koszt'], 2) ?> zł</p>
        </div>
        
        <a href="index.html" class="btn-continue">Kontynuuj zakupy</a>
    </main>
    
</body>
</html>