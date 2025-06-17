<?php
session_start();

// Inicjalizacja koszyka jeśli nie istnieje
if (!isset($_SESSION['koszyk'])) {
    $_SESSION['koszyk'] = [];
}

// Obsługa dodawania produktu (przekierowanie z dodaj_do_koszyka.php)
if (isset($_GET['dodano'])) {
    $komunikat = "Produkt dodany do koszyka!";
}

// Obsługa usuwania produktu
if (isset($_GET['usun'])) {
    $index = (int)$_GET['usun'];
    if (isset($_SESSION['koszyk'][$index])) {
        unset($_SESSION['koszyk'][$index]);
        // Przebuduj indeksy tablicy
        $_SESSION['koszyk'] = array_values($_SESSION['koszyk']);
    }
}

// Obsługa aktualizacji ilości
if ($_SERVER['REQUEST_METHOD'] === 'POST' && isset($_POST['aktualizuj'])) {
    foreach ($_POST['ilosc'] as $index => $ilosc) {
        $ilosc = (int)$ilosc;
        if ($ilosc > 0 && isset($_SESSION['koszyk'][$index])) {
            $_SESSION['koszyk'][$index]['ilosc'] = $ilosc;
        }
    }
}

// Oblicz sumę całkowitą i liczbę produktów
$suma = 0;
$count = 0; // Dodana zmienna dla całkowitej liczby produktów
foreach ($_SESSION['koszyk'] as $pozycja) {
    $suma += $pozycja['cena'] * $pozycja['ilosc'];
    $count += $pozycja['ilosc']; // Zliczanie produktów
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Koszyk - Słodka Bułka</title>
    <meta name="author" content="">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="stylekoszyk.css" type="text/css" />
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
                <a href="koszyk.php">
                    <i class="fas fa-shopping-cart"></i>
                    <?php if ($count > 0): ?>
                        <span class="cart-counter"><?= $count ?></span>
                    <?php endif; ?>
                </a>
            </div>
        </div>
    </header>
    
    <main class="koszyk-container">
        <h1>Twój koszyk</h1>
        
        <?php if (isset($komunikat)): ?>
            <div class="komunikat-sukces">
                <i class="fas fa-check-circle"></i> <?= $komunikat ?>
            </div>
        <?php endif; ?>
        
        <?php if (empty($_SESSION['koszyk'])): ?>
            <div class="pusty-koszyk">
                <i class="fas fa-shopping-basket"></i>
                <p>Twój koszyk jest pusty</p>
                <a href="oferta.html" class="btn btn-kontynuuj">Przeglądaj produkty</a>
            </div>
        <?php else: ?>
            <form action="koszyk.php" method="post">
                <table class="koszyk-tabela">
                    <thead>
                        <tr>
                            <th>Produkt</th>
                            <th>Rozmiar</th>
                            <th>Cena</th>
                            <th>Ilość</th>
                            <th>Suma</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php foreach ($_SESSION['koszyk'] as $index => $pozycja): ?>
                        <tr>
                            <td class="nazwa">
                                <?= htmlspecialchars($pozycja['nazwa']) ?>
                                <?php if (!empty($pozycja['wiadomosc'])): ?>
                                    <div class="wiadomosc" style="font-size: 0.9em; color: #666; margin-top: 5px;">
                                        <i class="fas fa-comment"></i> <?= htmlspecialchars($pozycja['wiadomosc']) ?>
                                    </div>
                                <?php endif; ?>
                            </td>
                            <td><?= htmlspecialchars($pozycja['rozmiar']) ?></td>
                            <td class="cena"><?= number_format($pozycja['cena'], 2) ?> zł</td>
                            <td>
                                <input type="number" 
                                       name="ilosc[<?= $index ?>]" 
                                       class="ilosc-input" 
                                       value="<?= $pozycja['ilosc'] ?>" 
                                       min="1">
                            </td>
                            <td class="cena"><?= number_format($pozycja['cena'] * $pozycja['ilosc'], 2) ?> zł</td>
                            <td>
                                <a href="koszyk.php?usun=<?= $index ?>" class="usun-link" title="Usuń z koszyka">
                                    <i class="fas fa-trash-alt"></i>
                                </a>
                            </td>
                        </tr>
                        <?php endforeach; ?>
                    </tbody>
                </table>
                
                <div class="suma-container">
                    <span>Suma całkowita:</span>
                    <span class="suma-kwota"><?= number_format($suma, 2) ?> zł</span>
                </div>
                
                <div class="przyciski-koszyk">
                    <a href="oferta.html" class="btn btn-kontynuuj">
                        <i class="fas fa-arrow-left"></i> Kontynuuj zakupy
                    </a>
                    <div>
                        <button type="submit" name="aktualizuj" class="btn btn-aktualizuj">
                            <i class="fas fa-sync-alt"></i> Aktualizuj koszyk
                        </button>
                        <a href="zamowienie.php" class="btn btn-zamow">
                            Złóż zamówienie <i class="fas fa-arrow-right"></i>
                        </a>
                    </div>
                </div>
            </form>
        <?php endif; ?>
    </main>
    
    <footer>
        <div id="kontakt">
            <div id="tekstkontakt">
            <p>Lokalizacja:</p>
            Piekarnia-Cukiernia Słodka Bułka:<br><br>
            ul. Targowska 9 42-518 Warszawa<br>
            ul. Piekarza 12 41-702 Kraków<br>
            </div>
            <div id="tekstkontakt">
            <p>Numer Telefonu:</p>
            +48 176 974 514<br>
            +48 618 952 168<br>
            <br>
            <p>Email:</p>
            Słodkabułka@gmail.com<br>
            </div>
            <div id="tekstkontakt">
            <p>Zasady:</p>
            Polityka cookies<br>
            Ustawienia cookies<br>
            Polityka prywatnosci<br>
            Regulamin<br>
            </div>
            <div id="tekstkontakt">
            <p>ZNAJDZ NAS:</p>
                <a href="https://x.com/slodka_bulka_"><img src="https://assets.streamlinehq.com/image/private/w_300,h_300,ar_1/f_auto/v1/icons/logos/x-jvgvt5gje92oz29ez4fgd.png/x-0muuxjzgzvtlpaduv3p4k2s.png?_a=DATAdtAAZAA0" alt="twitter"></a>
                <a href=""><img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1024px-Instagram_icon.png" alt="istagram"></a><br>
            </div>
        </div>

        <div id="terms">
                © Piekarnia Cukiernia Słodka Bułka 2025 | Privacy Policy | Terms & Conditions
            </div>
    </footer>
</body>
</html>