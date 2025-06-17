<?php
session_start();

$message = '';
$messageClass = '';

if(isset($_REQUEST["action"]) && $_REQUEST["action"] == "rejestracja") 
{
    
    $secretKey = "6LeC_2ErAAAAAPJ6hFPnkRIxW2lKlU1cfcb1Rh9_";
    $captchaResponse = $_POST['g-recaptcha-response'] ?? '';
    
    if(empty($captchaResponse)) {
        $message = "Proszę potwierdzić, że nie jesteś robotem.";
        $messageClass = "error";
    } else {
        $verifyUrl = "https://www.google.com/recaptcha/api/siteverify";
        $data = [
            'secret' => $secretKey,
            'response' => $captchaResponse
        ];
        
        $options = [
            'http' => [
                'header' => "Content-type: application/x-www-form-urlencoded\r\n",
                'method' => 'POST',
                'content' => http_build_query($data)
            ]
        ];
        
        $context = stream_context_create($options);
        $response = file_get_contents($verifyUrl, false, $context);
        $responseData = json_decode($response);
        
        if(!$responseData->success) {
            $message = "Weryfikacja reCAPTCHA nie powiodła się. Spróbuj ponownie.";
            $messageClass = "error";
        } else {
            $db = new mysqli("localhost", "root", "", "słodka_bułka");
            $email = $_REQUEST['email'];
            $email = filter_var($email, FILTER_SANITIZE_EMAIL);
            $imie = $_REQUEST['imie'];
            $nazwisko = $_REQUEST['nazwisko'];
            $haslo = $_REQUEST['haslo'];
            $haslo2 = $_REQUEST['haslo2'];

            $checkEmail = $db->prepare("SELECT id FROM user WHERE email = ?");
            $checkEmail->bind_param("s", $email);
            $checkEmail->execute();
            $emailExists = $checkEmail->get_result()->num_rows > 0;
            $checkEmail->close();

            if($emailExists) {
                $message = "Ten email jest już zarejestrowany";
                $messageClass = "error";
            } 
            elseif($haslo != $haslo2) {
                $message = "Hasła nie są zgodne - spróbuj ponownie";
                $messageClass = "error";
            }
            else
            {
                $q = $db->prepare("INSERT INTO user 
                                  (imie, nazwisko, email, haslo, numer_telefonu, adres) 
                                  VALUES (?, ?, ?, ?, NULL, NULL)");
                $haslohash = password_hash($haslo, PASSWORD_ARGON2I);
                $q->bind_param("ssss", $imie, $nazwisko, $email, $haslohash);
                $result = $q->execute();
                
                if($result) {
                    $message = "Konto utworzono poprawnie";
                    $messageClass = "success";
                } else {
                    $message = "Coś poszło nie tak...";
                    $messageClass = "error";
                }
                $q->close();
            }
            $db->close();
        }
    }
}
?>
<!DOCTYPE html>
<html>
<head>
    <title>Rejestracja</title>
    <meta name="author" content="Karolina Gluza">
    <meta name="keywords" content="Piekarnia, Cukiernia, Piekarniocukiernia, Ciasta, Torty, Babeczki, Pieczywo, Słodkości,Ciasteczka">
    <meta name="description" content="Piekarnia Cukiernia Slodka Bulka zaprasza na swieze pieczywo i wyroby prosto z serca">
    <link rel="stylesheet" href="stylekonto.css" type="text/css" />
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0-beta3/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Pacifico&family=Quicksand:wght@400;500;700&family=Amatic+SC:wght@700&display=swap" rel="stylesheet">
    <script async src="https://www.google.com/recaptcha/api.js"></script>
    <style>
        #login{
            margin-top: -55px;
        }
        
        .form-message {
            padding: 10px;
            margin: 15px 0;
            border-radius: 5px;
            text-align: center;
            font-weight: bold;
        }
        
        .error {
            background-color: #ffdddd;
            color: #ff0000;
        }
        
        .success {
            background-color: #ddffdd;
            color: #00aa00;
        }

        .g-recaptcha {
            display: flex;
            justify-content: center;
            margin: 15px 0;
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
                <a href="koszyk.html"><i class="fas fa-shopping-cart"></i></a>
            </div>
        </div>
    </header>
    <section id="login">
        <div id="form">
            <h2>Załóż konto</h2>
            <form action="rejestracja.php" method="POST">
                 <div class="klasa_dla_formularza">
                    <input type="text" id="imie" name="imie" required placeholder="Imię">
                </div>
                <div class="klasa_dla_formularza">
                    <input type="text" id="nazwisko" name="nazwisko" required placeholder="Nazwisko">
                </div>
                <div class="klasa_dla_formularza">
                    <input type="email" id="email" name="email" required placeholder="Email">
                </div>
                <div class="klasa_dla_formularza">
                    <input type="password" id="haslo" name="haslo" required placeholder="Hasło"> 
                </div>
                <div class="klasa_dla_formularza">
                    <input type="password" id="haslo2" name="haslo2" required placeholder="Powtórz hasło"> 
                </div>
                
                <?php if($message != ''): ?>
                    <div class="form-message <?php echo $messageClass; ?>">
                        <?php echo $message; ?>
                    </div>
                <?php endif; ?>
                
                <div class="g-recaptcha" data-sitekey="6LeC_2ErAAAAAEMnYZ-Y-AXtolpnMhGfDZUVjrtQ"></div>
                
                <input type="hidden" id="action" name="action" value="rejestracja">
                <button type="submit" class="przycisk">Załóż konto</button>
            </form>
            
            <div class="linki_dodatkowe">
                <a href="konto.php">Masz już konto? Zaloguj się tutaj.</a>
            </div>
            
            <div class="formularz_reszta">
                <p>Rejestrując się akceptujesz nasz Regulamin oraz Politykę Prywatności</p>
            </div>
        </div>
    </section>
</body>
</html>