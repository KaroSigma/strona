<?php
$mysqli = new mysqli("localhost", "root", "", "słodka_bułka");
if ($mysqli->connect_error) {
    die("Błąd połączenia: " . $mysqli->connect_error);
}

$id = isset($_GET['id']) ? (int)$_GET['id'] : 0;

$stmt = $mysqli->prepare("SELECT * FROM produkt WHERE id = ? AND dostepne = 1");
$stmt->bind_param("i", $id);
$stmt->execute();
$result = $stmt->get_result();

if ($result->num_rows === 0) {
    echo "Produkt nie istnieje lub jest niedostępny.";
    exit;
}

$produkt = $result->fetch_assoc();
if ($produkt["kategoria"] == "Ciasta" || $produkt["kategoria"] == "Torty") {
    $przechowywanie = "Przechowywać w lodówce, spożyć w ciągu " . $produkt["przechowaniegodziny"] . "h";
} else {
    $przechowywanie = "Spożyć w ciągu " . $produkt["przechowaniegodziny"] . "h";
}

$alergeny = array();
$stmt = $mysqli->prepare("
    SELECT a.nazwa 
    FROM alergen a
    JOIN produkt_alergen pa ON a.id = pa.alergen_id
    WHERE pa.produkt_id = ?
");
$stmt->bind_param("i", $id);
$stmt->execute();
$resultAlergen = $stmt->get_result();
while ($row = $resultAlergen->fetch_assoc()) {
    $alergeny[] = $row['nazwa'];
}

$miniaturki = array();
if (!empty($produkt['zdjecia'])) {
    $miniaturki = explode(',', $produkt['zdjecia']);
}

$podobne = array();

if (!empty($produkt['kategoria'])) {
    $kategoria = $produkt['kategoria'];
    $stmt = $mysqli->prepare("
        SELECT * FROM produkt 
        WHERE kategoria = ? AND id != ? AND dostepne = 1 
        ORDER BY RAND()
        LIMIT 3
    ");
    $stmt->bind_param("si", $kategoria, $id);
    $stmt->execute();
    $resultPodobne = $stmt->get_result();
    while ($row = $resultPodobne->fetch_assoc()) {
        $podobne[] = $row;
    }
}

$kategoria = isset($_GET['kategoria']) 
    ? $_GET['kategoria'] 
    : $produkt['kategoria']; 

$stmt->close();
$mysqli->close();
?>
<!DOCTYPE html>
<html>
<head>
    <title>Produkt - Słodka Bułka</title>
    <meta name="author" content="">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="styleprodukt.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Funkcja aktualizująca licznik koszyka
            function updateCartCounter() {
                fetch('get_cart_count.php')
                    .then(response => response.json())
                    .then(data => {
                        const counter = document.querySelector('.cart-counter');
                        if (data.count > 0) {
                            counter.textContent = data.count;
                            counter.style.display = 'block';
                        } else {
                            counter.style.display = 'none';
                        }
                    });
            }

            // Inicjalna aktualizacja licznika
            updateCartCounter();

            // Obsługa dodawania do koszyka
            document.querySelector('.btn-primary').addEventListener('click', function(e) {
                e.preventDefault();
                
                const formData = new FormData();
                formData.append('id', <?= $produkt['id'] ?>);
                formData.append('nazwa', '<?= addslashes($produkt['nazwa']) ?>');
                formData.append('rozmiar', document.querySelector('input[name="rozmiar"]:checked').parentElement.querySelector('.label').textContent);
                formData.append('cena', document.querySelector('input[name="rozmiar"]:checked').value);
                formData.append('ilosc', document.getElementById('qty').value);
                formData.append('wiadomosc', document.querySelector('input[name="wiadomosc"]').value);
                
                fetch('dodaj_do_koszyka.php', {
                    method: 'POST',
                    body: formData
                })
                .then(response => response.json())
                .then(data => {
                    if (data.success) {
                        // Animacja dodania do koszyka
                        const btn = document.querySelector('.btn-primary');
                        btn.innerHTML = '<i class="fas fa-check"></i> Dodano!';
                        btn.classList.add('success');
                        
                        // Aktualizuj licznik
                        updateCartCounter();
                        
                        // Przywróć przycisk po 2 sekundach
                        setTimeout(() => {
                            btn.innerHTML = 'Dodaj do koszyka';
                            btn.classList.remove('success');
                        }, 2000);
                    } else {
                        alert('Wystąpił błąd podczas dodawania do koszyka');
                    }
                });
            });
        });
    </script>
    <style>
        /* Animacja dodania do koszyka */
        .btn-primary.success {
            background-color: #28a745 !important;
        }
        
        /* Licznik koszyka - POPRAWIONE POZYCJONOWANIE */
        .cart-counter {
            position: absolute;
            top: -5px;
            right: -5px;
            background-color: #e74c3c;
            color: white;
            border-radius: 50%;
            width: 18px;
            height: 18px;
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 0.65rem;
            font-weight: bold;
            line-height: 1;
        }
        
        .icony {
            position: relative;
            display: flex;
            align-items: center;
        }
        
        .icony > a {
            position: relative;
            margin-left: 15px;
            display: flex;
            justify-content: center;
            align-items: center;
        }
    </style>
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
                    <span class="cart-counter" style="display: none;">0</span>
                </a>
            </div>
        </div>
    </header>
    <main class="produkt-page">
  <div class="breadcrumbs">
  <a href="oferta.html">Oferta</a> &raquo;
  <?php 
  $slug = strtolower($kategoria) . '.php';  
    ?>
    <a href="<?= htmlspecialchars($slug) ?>">
    <?= htmlspecialchars($kategoria) ?>
    </a>
    <span><?= htmlspecialchars($produkt['nazwa']) ?></span>
    </div>

  <div class="produkt-top">
    <div class="galeria">
      <div class="main-image">
        <img src="<?= htmlspecialchars($miniaturki[0]) ?>" alt="">
      </div>
      <div class="thumbnails">
        <?php foreach($miniaturki as $src): ?>
          <img class="thumb" src="<?= htmlspecialchars(trim($src)) ?>" alt="" onclick="document.querySelector('.main-image img').src=this.src">
        <?php endforeach; ?>
      </div>
    </div>

    <div class="produkt-info">
      <h1><?= htmlspecialchars($produkt['nazwa']) ?></h1>
      <p class="opis"><?= nl2br(htmlspecialchars($produkt['opis'])) ?></p>

      <div class="rozmiary">
        <?php 
          $bazowa = $produkt['cena'];
          $warianty = [
            'Małe (0,8 kg)'   => $bazowa,
            'Średnie (1,2 kg)' => $bazowa*1.3,
            'Duże (2,0 kg)'    => $bazowa*2,
          ];
        
        foreach($warianty as $label=>$c): ?>
          <label>
            <input type="radio" name="rozmiar" value="<?= $c ?>" <?= $label==='Małe (0,8 kg)'?'checked':'' ?>>
            <span class="label"><?= $label ?></span>
            <span class="price"><?= number_format($c,2) ?> zł</span>
          </label>
        <?php endforeach; ?>
      </div>

    <div class="opcje-dodatkowe">
        <input type="text" name="wiadomosc" placeholder="Wpisz np. dedykację">
    </div>

      <div class="alergeny">
        <i class="fas fa-exclamation-triangle"></i>
        Alergeny: <?= htmlspecialchars(implode(', ', $alergeny)) ?>
      </div>

      <form action="dodaj_do_koszyka.php" method="post">
      <input type="hidden" name="id" value="<?= $produkt['id'] ?>">
      <input type="hidden" name="nazwa" value="<?= htmlspecialchars($produkt['nazwa']) ?>">
      <input type="hidden" name="rozmiar" id="formRozmiar" value="Małe (0,8 kg)">
      <input type="hidden" name="cena" id="formCena" value="<?= $bazowa ?>">
    
      <div class="dodaj-koszyk">
        <div class="suma">
            RAZEM: <span id="total"><?= number_format($bazowa,2) ?></span> zł
        </div>
        <input type="number" name="ilosc" id="qty" value="1" min="1">
        <button type="submit" class="btn-primary">Dodaj do koszyka</button>
    </div>
</form>
    </div>
  </div>

  <div class="produkt-extra">
    <div class="extra-box">
      <i class="fas fa-apple-alt"></i>
      <h4>Najważniejsze składniki</h4>
      <p><?= htmlspecialchars($produkt['skladniki']) ?></p>
    </div>
    <div class="extra-box">
      <i class="fas fa-ice-cream"></i>
      <h4>Przechowywanie</h4>
      <p><?= htmlspecialchars($przechowywanie) ?></p>
    </div>
    <div class="extra-box">
      <i class="fas fa-coffee"></i>
      <h4>Idealne na</h4>
      <p><?= htmlspecialchars($produkt['idealne_na']) ?></p>
    </div>
    <div class="extra-box">
      <i class="fas fa-shopping-bag"></i>
      <h4>Zamów online</h4>
      <p>delektuj się wyjątkowym smakiem!</p>
    </div>
  </div>

  <h2 class="similar-title">Inne podobne:</h2>
  <div class="similar-products">
    <?php foreach($podobne as $p): 
      $t = explode(',', $p['zdjecia']); ?>
      <div class="sim-item">
        <img src="<?= htmlspecialchars(trim($t[0])) ?>" alt="">
        <h5><?= htmlspecialchars($p['nazwa']) ?></h5>
        <p class="sim-price"><?= number_format($p['cena'],2) ?> zł</p>
        <a href="produkt.php?id=<?= $p['id'] ?>">zobacz →</a>
      </div>
    <?php endforeach; ?>
  </div>
</main>

<script>
document.querySelectorAll('input[name=rozmiar]').forEach(el => {
    el.addEventListener('change', () => {
        const selectedLabel = el.parentElement.querySelector('.label').textContent;
        document.getElementById('formRozmiar').value = selectedLabel;
        document.getElementById('formCena').value = el.value;
        
        // Aktualizuj wyświetlaną sumę
        const cena = parseFloat(el.value);
        const il = parseInt(document.getElementById('qty').value) || 1;
        document.getElementById('total').innerText = (cena * il).toFixed(2);
    });
});

document.getElementById('qty').addEventListener('input', () => {
    const cena = parseFloat(document.querySelector('input[name=rozmiar]:checked').value);
    const il = parseInt(document.getElementById('qty').value) || 1;
    document.getElementById('total').innerText = (cena * il).toFixed(2);
});
</script>
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