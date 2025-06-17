<?php
session_start();

$count = 0;
if (!empty($_SESSION['koszyk'])) {
    foreach ($_SESSION['koszyk'] as $item) {
        $count += $item['ilosc'];
    }
}

header('Content-Type: application/json');
echo json_encode(['count' => $count]);
exit;