<?php
session_start();

if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    $id = isset($_POST['id']) ? (int)$_POST['id'] : 0;
    $nazwa = isset($_POST['nazwa']) ? $_POST['nazwa'] : '';
    $rozmiar = isset($_POST['rozmiar']) ? $_POST['rozmiar'] : '';
    $cena = isset($_POST['cena']) ? (float)$_POST['cena'] : 0;
    $ilosc = isset($_POST['ilosc']) ? (int)$_POST['ilosc'] : 1;
    $wiadomosc = isset($_POST['wiadomosc']) ? $_POST['wiadomosc'] : '';

    if ($id > 0 && !empty($nazwa) && $cena > 0 && $ilosc > 0) {
        $produkt = [
            'id' => $id,
            'nazwa' => $nazwa,
            'rozmiar' => $rozmiar,
            'cena' => $cena,
            'ilosc' => $ilosc,
            'wiadomosc' => $wiadomosc
        ];

        if (!isset($_SESSION['koszyk'])) {
            $_SESSION['koszyk'] = [];
        }

        $_SESSION['koszyk'][] = $produkt;
        
        // Zwracamy tylko status sukcesu (bez przekierowania)
        $response = [
            'success' => true,
            'cart' => $_SESSION['koszyk']
        ];
        echo json_encode($response);
        exit;
    }
}

echo json_encode(['success' => false]);
exit;