<?php
session_start();

if (!isset($_POST["email"]) || !isset($_POST["haslo"])) {
    header('Location: konto.php');
    exit();
}

require_once "connect.php";

$polaczenie = @new mysqli($host, $db_user, $db_passwd, $db_name);

if ($polaczenie->connect_errno != 0) {
    echo "Error: " . $polaczenie->connect_errno;
} else {
    $email = $_POST['email'];
    $password = $_POST['haslo'];
    
    $sql = "SELECT id, imie, nazwisko, email, haslo, numer_telefonu, adres FROM user WHERE email = ?";
    $stmt = $polaczenie->prepare($sql);
    
    if ($stmt) {
        $stmt->bind_param("s", $email);
        $stmt->execute();
        $result = $stmt->get_result();
        
        if ($result->num_rows === 1) {
            $wiersz = $result->fetch_assoc();
            
            if (password_verify($password, $wiersz['haslo'])) {
                $_SESSION['zalogowany'] = true;
                $_SESSION['id'] = $wiersz['id'];
                $_SESSION['email'] = $wiersz['email'];
                $_SESSION['imie'] = $wiersz['imie'];
                $_SESSION['nazwisko'] = $wiersz['nazwisko'];
                $_SESSION['numer_telefonu'] = $wiersz['numer_telefonu'];
                $_SESSION['adres'] = $wiersz['adres'];

                unset($_SESSION['blad']);
                $stmt->close();
                $polaczenie->close();
                header("Location: panelkonta.php");
                exit();
            } 
            else {
                $_SESSION['blad'] = '<span style="display: block; margin-top: 20px; color:red; font-size: 15px; text-align: left">Nieprawidłowy email lub hasło!</span>';
                $stmt->close();
                $polaczenie->close();
                header('Location: konto.php');
                exit();
            }
        } else {
            $_SESSION['blad'] = '<span style="display: block; margin-top: 20px; color:red; font-size: 15px; text-align: left">Nieprawidłowy email lub hasło!</span>';
            $stmt->close();
            $polaczenie->close();
            header('Location: konto.php');
            exit();
        }
    } 
    else {
        $_SESSION['blad'] = '<span style="display: block; margin-top: 20px; color:red; font-size: 15px; text-align: left">Błąd systemu. Prosimy spróbować później.</span>';
        $polaczenie->close();
        header('Location: konto.php');
        exit();
    }
}
?>