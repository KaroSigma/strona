<?php
    session_start();

    if ((isset($_SESSION['zalogowany'])) && ($_SESSION['zalogowany']==true))
    {
        header('Location: panelkonta.php');
        exit();
    }
?>
<!DOCTYPE html>
<html>
<head>
    <title>Zaloguj się - Słodka Bułka</title>
    <meta name="author" content="Karolina Gluza">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="stylekonto.css" type="text/css" />
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
                <a href="konto.html"><i class="fas fa-user"></i></a>
                <a href="koszyk.html"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>
    </header>
    <section id="login">
        <div id="form">
            <h2>Zaloguj się</h2>
            <form action="login.php" method="POST">
                <div class="klasa_dla_formularza">
                    <label for="email">Email:</label>
                    <input type="email" id="email" name="email" required placeholder="Wprowadź swój email">
                </div>
                <div class="klasa_dla_formularza">
                    <label for="haslo">Hasło:</label>
                    <input type="password" id="haslo" name="haslo" required placeholder="Wprowadź swoje hasło">
                <?php
            
            if(isset($_SESSION['blad'])) echo''.$_SESSION['blad'].'';
            ?>
                </div>
        
            <button type="submit" class="przycisk">Zaloguj się</button>
            </form>
            
            <div class="linki_dodatkowe">
                <a href="#">Zapomniałeś hasła?</a> | 
                <a href="rejestracja.php">Załóż konto</a>
            </div>
            
            <div class="formularz_reszta">
                <p>Logując się akceptujesz nasz Regulamin oraz Politykę Prywatności</p>
            </div>
        </div>
    </section>
</body>
</html>