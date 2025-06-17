<?php
session_start();

if (!isset($_SESSION['id'])) {
    header('Location: panelkonta.php');
    exit;
}

// Połączenie z bazą danych
$mysqli = new mysqli("localhost", "root", "", "słodka_bułka");
if ($mysqli->connect_error) {
    die("Błąd połączenia: " . $mysqli->connect_error);
}

// Pobierz dane użytkownika
$user_id = $_SESSION['id'];
$stmt = $mysqli->prepare("SELECT * FROM user WHERE id = ?");
$stmt->bind_param("i", $user_id);
$stmt->execute();
$result = $stmt->get_result();
$user = $result->fetch_assoc();

// Sprawdź czy koszyk nie jest pusty
if (empty($_SESSION['koszyk'])) {
    header('Location: koszyk.php');
    exit;
}

// Oblicz sumę całkowitą
$suma = 0;
foreach ($_SESSION['koszyk'] as $pozycja) {
    $suma += $pozycja['cena'] * $pozycja['ilosc'];
}

// Obsługa formularza
$errors = [];
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    // Pobierz dane z formularza
    $dostawa = $_POST['dostawa'] ?? 'odbior';
    $uwagi = $_POST['uwagi'] ?? '';
    $adres = $_POST['adres'] ?? '';
    $telefon = $_POST['telefon'] ?? '';
    
    // Aktualizuj dane użytkownika w bazie danych
    $update_stmt = $mysqli->prepare("UPDATE user SET adres = ?, numer_telefonu = ? WHERE id = ?");
    $update_stmt->bind_param("ssi", $adres, $telefon, $user_id);
    $update_stmt->execute();
    $update_stmt->close();
    
    // Dodaj koszt dostawy jeśli wybrano
    $koszt_dostawy = ($dostawa === 'dostawa') ? 20.00 : 0.00;
    $suma_calkowita = $suma + $koszt_dostawy;
    
    // Przygotuj opis zamówienia (produkty w koszyku)
    $opis = "Zamówienie:\n";
    foreach ($_SESSION['koszyk'] as $pozycja) {
        $opis .= sprintf(
            "- %s (%s) x%d: %.2f zł\n", 
            $pozycja['nazwa'], 
            $pozycja['rozmiar'], 
            $pozycja['ilosc'], 
            $pozycja['cena'] * $pozycja['ilosc']
        );
    }
    $opis .= "\nDostawa: " . (($dostawa === 'dostawa') ? "Dostawa do lokalu (+20.00 zł)" : "Odbiór osobisty");
    $opis .= "\nUwagi: $uwagi";
    $opis .= "\nSuma całkowita: " . number_format($suma_calkowita, 2) . " zł";
    
    // Zapisz zamówienie do bazy danych
    $stmt = $mysqli->prepare("
        INSERT INTO zamowienie 
        (id_uzytkownika, status, data_zamowienia, opis, koszt, dostawa, uwagi) 
        VALUES (?, 'Nowe', NOW(), ?, ?, ?, ?)
    ");
    
    $stmt->bind_param(
        "issss", 
        $user_id,
        $opis,
        $suma_calkowita,
        $dostawa,
        $uwagi
    );
    
    if ($stmt->execute()) {
        // Wyczyść koszyk po udanym zamówieniu
        unset($_SESSION['koszyk']);
        
        // Przekieruj na stronę potwierdzenia
        header('Location: potwierdzenie.php?id=' . $stmt->insert_id);
        exit;
    } else {
        $errors[] = "Błąd podczas zapisywania zamówienia: " . $stmt->error;
    }
}

// Ustaw domyślne wartości
$dostawa = 'odbior';
?>

<!DOCTYPE html>
<html>
<head>
    <title>Zamówienie - Słodka Bułka</title>
    <meta name="author" content="">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="styleprodukt.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <style>
        .zamowienie-container {
            max-width: 1200px;
            margin: 20px auto;
            padding: 20px;
            display: flex;
            gap: 30px;
        }
        
        .formularz {
            flex: 1;
        }
        
        .podsumowanie {
            flex: 1;
            background-color: #f8f9fa;
            padding: 20px;
            border-radius: 4px;
        }
        
        .form-group {
            margin-bottom: 15px;
        }
        
        label {
            display: block;
            margin-bottom: 5px;
            font-weight: 600;
        }
        
        .dane-uzytkownika {
            background-color: #f0f8ff;
            padding: 15px;
            border-radius: 4px;
            margin-bottom: 20px;
        }
        
        .dane-uzytkownika p {
            margin: 5px 0;
        }
        
        .dostawa-radio {
            display: flex;
            gap: 20px;
        }
        
        .dostawa-option {
            border: 1px solid #ddd;
            padding: 15px;
            border-radius: 4px;
            cursor: pointer;
            flex: 1;
        }
        
        .dostawa-option.selected {
            border-color: #27ae60;
            background-color: #e8f7f0;
        }
        
        .dostawa-option input[type="radio"] {
            margin-right: 10px;
        }
        
        .podsumowanie-item {
            display: flex;
            justify-content: space-between;
            margin-bottom: 10px;
        }
        
        .podsumowanie-total {
            font-weight: 700;
            font-size: 1.2em;
            margin-top: 20px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
        }
        
        .btn-zamow {
            background-color: #27ae60;
            color: white;
            border: none;
            padding: 12px 25px;
            border-radius: 4px;
            font-size: 1.1em;
            cursor: pointer;
            width: 100%;
            margin-top: 20px;
        }
        
        .error {
            color: #e74c3c;
            margin-bottom: 15px;
        }
        
        textarea {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            min-height: 100px;
        }
        
        input[type="text"] {
            width: 100%;
            padding: 10px;
            border: 1px solid #ddd;
            border-radius: 4px;
            box-sizing: border-box;
        }
    </style>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Obsługa wyboru dostawy
            document.querySelectorAll('input[name="dostawa"]').forEach(radio => {
                radio.addEventListener('change', function() {
                    document.querySelectorAll('.dostawa-option').forEach(option => {
                        option.classList.remove('selected');
                    });
                    this.closest('.dostawa-option').classList.add('selected');
                    updateSummary();
                });
            });
            
            function updateSummary() {
                const dostawa = document.querySelector('input[name="dostawa"]:checked').value;
                const kosztDostawy = dostawa === 'dostawa' ? 20.00 : 0.00;
                document.getElementById('koszt-dostawy').textContent = kosztDostawy.toFixed(2) + ' zł';
                document.getElementById('suma-calkowita').textContent = (<?= $suma ?> + kosztDostawy).toFixed(2) + ' zł';
            }
            
            // Inicjalna aktualizacja podsumowania
            updateSummary();
        });
    </script>
</head>
<body>
    
    <main class="zamowienie-container">
        <form class="formularz" method="post">
            <h2>Dane do zamówienia</h2>
            
            <?php if (!empty($errors)): ?>
                <div class="error">
                    <?php foreach($errors as $error): ?>
                        <p><?= $error ?></p>
                    <?php endforeach; ?>
                </div>
            <?php endif; ?>
            
            <div class="dane-uzytkownika">
                <h3>Twoje dane</h3>
                <p><strong>Imię i nazwisko:</strong> <?= htmlspecialchars($user['imie'] . ' ' . $user['nazwisko']) ?></p>
                <p><strong>Email:</strong> <?= htmlspecialchars($user['email']) ?></p>
                
                <div class="form-group">
                    <label for="adres">Adres</label>
                    <input type="text" id="adres" name="adres" value="<?= htmlspecialchars($user['adres'] ?? '') ?>" required>
                </div>
                
                <div class="form-group">
                    <label for="telefon">Telefon</label>
                    <input type="text" id="telefon" name="telefon" value="<?= htmlspecialchars($user['numer_telefonu'] ?? '') ?>" required>
                </div>
            </div>
            
            <div class="form-group">
                <label>Dostawa</label>
                <div class="dostawa-radio">
                    <label class="dostawa-option <?= $dostawa === 'odbior' ? 'selected' : '' ?>">
                        <input type="radio" name="dostawa" value="odbior" <?= $dostawa === 'odbior' ? 'checked' : '' ?>> Odbiór osobisty (0 zł)
                    </label>
                    <label class="dostawa-option <?= $dostawa === 'dostawa' ? 'selected' : '' ?>">
                        <input type="radio" name="dostawa" value="dostawa" <?= $dostawa === 'dostawa' ? 'checked' : '' ?>> Dostawa do lokalu (20 zł)
                    </label>
                </div>
            </div>
            
            <div class="form-group">
                <label for="uwagi">Dodatkowe uwagi do zamówienia</label>
                <textarea id="uwagi" name="uwagi" placeholder="Prosimy o podanie godziny odbioru lub innych uwag..."></textarea>
            </div>
            
            <button type="submit" class="btn-zamow">Zapłać</button>
        </form>
        
        <div class="podsumowanie">
            <h2>Podsumowanie zamówienia</h2>
            
            <?php foreach ($_SESSION['koszyk'] as $pozycja): ?>
                <div class="podsumowanie-item">
                    <span><?= htmlspecialchars($pozycja['nazwa']) ?> (<?= htmlspecialchars($pozycja['rozmiar']) ?>) x <?= $pozycja['ilosc'] ?></span>
                    <span><?= number_format($pozycja['cena'] * $pozycja['ilosc'], 2) ?> zł</span>
                </div>
            <?php endforeach; ?>
            
            <div class="podsumowanie-item">
                <span>Suma produktów</span>
                <span><?= number_format($suma, 2) ?> zł</span>
            </div>
            
            <div class="podsumowanie-item">
                <span>Koszt dostawy</span>
                <span id="koszt-dostawy">0.00 zł</span>
            </div>
            
            <div class="podsumowanie-item podsumowanie-total">
                <span>Suma całkowita</span>
                <span id="suma-calkowita"><?= number_format($suma, 2) ?> zł</span>
            </div>
        </div>
    </main>
    
</body>
</html>