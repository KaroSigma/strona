<?php
session_start();

if (!isset($_SESSION["zalogowany"])) {
    header('Location: login.php');
    exit();
}

// Połączenie z bazą danych
$host = 'localhost';
$dbname = 'słodka_bułka';
$username = 'root';
$password = '';

try {
    $pdo = new PDO("mysql:host=$host;dbname=$dbname;charset=utf8", $username, $password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
    
    // ID zalogowanego użytkownika
    $user_id = $_SESSION['id'];
    
    // Paginacja
    $page = isset($_GET['page']) ? (int)$_GET['page'] : 1;
    $items_per_page = 2;
    $offset = ($page - 1) * $items_per_page;
    
    // Główne zapytanie o zamówienia
    $stmt = $pdo->prepare("
        SELECT 
            z.id                     AS zamowienie_id,
            z.data_zamowienia,
            z.status,
            pz.ilosc,
            pz.cena_za_1             AS cena_jednostkowa,
            p.nazwa                  AS produkt_nazwa,
            d.data_dostawy
        FROM zamowienie z
        JOIN przedmiot_zamowienia pz 
            ON z.id = pz.zamowienie_id
        JOIN produkt p 
            ON p.id = pz.produkt_id
        LEFT JOIN dostawa d 
            ON z.id = d.zamowienie_id
        WHERE z.id_uzytkownika = :user_id
        ORDER BY z.data_zamowienia DESC
        LIMIT :offset, :limit
    ");
    $stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $stmt->bindValue(':offset', $offset, PDO::PARAM_INT);
    $stmt->bindValue(':limit', $items_per_page, PDO::PARAM_INT);
    $stmt->execute();
    $orders = $stmt->fetchAll(PDO::FETCH_ASSOC);
    
    // Zapytanie o łączną liczbę zamówień (do paginacji)
    $count_stmt = $pdo->prepare("
        SELECT COUNT(*) 
        FROM zamowienie 
        WHERE id_uzytkownika = :user_id
    ");
    $count_stmt->bindValue(':user_id', $user_id, PDO::PARAM_INT);
    $count_stmt->execute();
    $total_orders = $count_stmt->fetchColumn();
    
} catch (PDOException $e) {
    die("Błąd połączenia z bazą danych: " . $e->getMessage());
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Panel konta - Słodka Bułka</title>
    <meta name="author" content="Karolina Gluza">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="stylepanelkonta.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
</head>
<body>
   <header>
       <div class="nav-wrapper">
            <a href="index.html" class="logo">
            <img src="https://cdn-icons-png.flaticon.com/512/3081/3081949.png" alt="Słodka Bułka">
            Słodka Bułka
            </a>
            <nav>
                <ul>
                    <li><a href="index.html">Home</a></li>
                    <li><a href="oferta.html">Oferta</a></li>
                    <li><a href="aktualnosci.html">Aktualności</a></li>
                    <li><a href="galeria.html">Galeria</a></li>
                    <li><a href="onas.html">O nas</a></li>
                    <li><a href="kontakt.html">Kontakt</a></li>
                </ul>
            </nav>

            <div class="icony">
                <a href="konto.php"><i class="fas fa-user"></i></a>
                <a href="koszyk.html"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>
    </header>
    
    <main class="panel-container">
        <aside class="sidebar">
            <a href="konto.php"><i class="fas fa-user"></i></a>
            <a href="zamowienia.php"><i class="fas fa-shopping-cart"></i></a>
            <a href="#"><i class="fas fa-truck"></i></a>
            <a href="#"><i class="fas fa-star"></i></a>
            <a href="#"><i class="fas fa-phone"></i></a>
            <a href="#"><i class="fas fa-cog"></i></a>
        </aside>

        <section class="content">
        <div class="welcome-box">
            <?php
                echo "<p>Witaj ".$_SESSION['imie']." ".$_SESSION['nazwisko']."!</p>";
            ?>
        </div>

        <h2 class="section-title">Zamówienia</h2>
        <div class="orders-list">
            <?php if (count($orders) > 0): ?>
                <?php foreach ($orders as $order): ?>
                    <div class="order-card">
                        <div class="order-header">
                            <span class="order-name"><?= htmlspecialchars($order['produkt_nazwa']) ?></span>
                            <span class="order-price">
                                <?= number_format($order['cena_jednostkowa'] * $order['ilosc'], 2, ',', ' ') ?> zł
                            </span>
                        </div>
                        <div class="order-details">
                            <div><strong>Ilość:</strong> <?= (int)$order['ilosc'] ?> szt.</div>
                            <div><strong>Status:</strong> <?= htmlspecialchars($order['status']) ?></div>
                            <div><strong>Data zamówienia:</strong><br>
                                <?= date('d.m.Y H:i', strtotime($order['data_zamowienia'])) ?>
                            </div>
                            <div><strong>Dostawa:</strong><br>
                                <?= $order['data_dostawy']
                                    ? date('d.m.Y H:i', strtotime($order['data_dostawy']))
                                    : '—'
                                ?>
                            </div>
                        </div>
                        <a href="szczegoly.php?id=<?= $order['zamowienie_id'] ?>" class="btn-details">szczegóły</a>
                    </div>
                <?php endforeach; ?>
            <?php else: ?>
                <p>Brak zamówień do wyświetlenia.</p>
            <?php endif; ?>
        </div>
        
        <?php if ($total_orders > ($offset + $items_per_page)): ?>
            <a href="?page=<?= $page + 1 ?>" class="btn-show-more">Pokaż starsze</a>
        <?php endif; ?>

        <div class="logout-container">
            <a href="wyloguj.php" class="btn-logout">Wyloguj się</a>
        </div>
    </section>
    <!-- ... koniec body/html ... -->
</body>
</html>