<?php


$conn = new mysqli('localhost','root','','słodka_bułka');
if($conn->connect_error) {
    die('Błąd połączenia: '.$conn->connect_error);
}

$kategoria = 'Ciasta';
$stmt = $conn->prepare("SELECT id, nazwa, cena, zdjecia FROM produkt WHERE kategoria = ? AND dostepne = 1");
$stmt->bind_param('s', $kategoria);
$stmt->execute();
$result = $stmt->get_result();

$produkty = $result->fetch_all(MYSQLI_ASSOC);

$stmt->close();
$conn->close();
?>
<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Ciasta – Słodka Bułka</title>
    <meta name="keywords" content="ciasta, słodka bułka, wypieki">
    <link rel="stylesheet" href="styleoferta.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&display=swap" rel="stylesheet">
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
<style>
    #obraz 
    {
        background: linear-gradient(rgba(0, 0, 0, 0.5), rgba(0, 0, 0, 0.4)), url('https://hips.hearstapps.com/del.h-cdn.co/assets/16/38/1600x900/hd-aspect-1474650684-cakes-group-193.jpg?resize=1200:*') center/cover no-repeat;
    }

    .kategoria{
        width: 500px;
    }

    .kategoria button {
    background-color:rgb(196, 117, 97);
    }
    .kategoria button:hover {
    background-color:rgb(161, 84, 65);
    }
</style>
</header>
<main>
   <div id="obraz"> 
  <section id="oferta">
    <h1>Ciasta</h1>
    <p class="intro">
      Puszyste i słodkie ciasta na każdą okazję. Od klasycznych po nowoczesne, wszystkie pieczone z miłością i najlepszych składników.
    </p>
  </section>
  </div>

  <section id="kategorie">
    <?php if(count($produkty)===0): ?>
      <p>Brak dostępnych produktów w tej kategorii.</p>
    <?php else: ?>
      <?php foreach($produkty as $p): 
        $lista = explode(',', $p['zdjecia']);
        $img = trim($lista[0]);
      ?>
      <div class="kategoria">
        <img src="<?= htmlspecialchars($img) ?>" alt="<?= htmlspecialchars($p['nazwa']) ?>">
        <h3><?= htmlspecialchars($p['nazwa']) ?></h3>
        <p>Cena od <?= number_format($p['cena'],2) ?> zł</p>
        <a href="produkt.php?id=<?= $p['id'] ?>"><button>Zobacz więcej</button></a>
      </div>
      <?php endforeach; ?>
    <?php endif; ?>
  </section>
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
      Slodkabulka@gmail.com<br>
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
      <a href="https://x.com/slodka_bulka_">
        <img src="https://assets.streamlinehq.com/image/private/w_300,h_300,ar_1/f_auto/v1/icons/logos/x-jvgvt5gje92oz29ez4fgd.png/x-0muuxjzgzvtlpaduv3p4k2s.png?_a=DATAdtAAZAA0" alt="twitter">
      </a>
      <a href="#">
        <img src="https://upload.wikimedia.org/wikipedia/commons/thumb/a/a5/Instagram_icon.png/1024px-Instagram_icon.png" alt="instagram">
      </a><br>
    </div>
  </div>
  <div id="terms">
    © Piekarnia Cukiernia Słodka Bułka 2025 | Privacy Policy | Terms & Conditions
  </div>
</footer>
</body>
</html>
