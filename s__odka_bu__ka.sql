-- phpMyAdmin SQL Dump
-- version 5.2.1
-- https://www.phpmyadmin.net/
--
-- Host: 127.0.0.1
-- Generation Time: Cze 18, 2025 at 12:04 AM
-- Wersja serwera: 10.4.32-MariaDB
-- Wersja PHP: 8.2.12

SET SQL_MODE = "NO_AUTO_VALUE_ON_ZERO";
START TRANSACTION;
SET time_zone = "+00:00";


/*!40101 SET @OLD_CHARACTER_SET_CLIENT=@@CHARACTER_SET_CLIENT */;
/*!40101 SET @OLD_CHARACTER_SET_RESULTS=@@CHARACTER_SET_RESULTS */;
/*!40101 SET @OLD_COLLATION_CONNECTION=@@COLLATION_CONNECTION */;
/*!40101 SET NAMES utf8mb4 */;

--
-- Database: `słodka_bułka`
--

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `alergen`
--

CREATE TABLE `alergen` (
  `id` int(11) NOT NULL,
  `nazwa` varchar(100) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `alergen`
--

INSERT INTO `alergen` (`id`, `nazwa`) VALUES
(1, 'Gluten'),
(2, 'Jaja'),
(3, 'Mleko i produkty mleczne'),
(4, 'Orzechy'),
(5, 'Soja'),
(6, 'Nasiona sezamu');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `dostawa`
--

CREATE TABLE `dostawa` (
  `id` int(11) NOT NULL,
  `zamowienie_id` int(11) NOT NULL,
  `adres_zamowienia` text NOT NULL,
  `status` enum('przypisano','w trakcie','dostarczono','niepowodzenie') NOT NULL,
  `data_dostawy` date NOT NULL,
  `kurier_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `kurier`
--

CREATE TABLE `kurier` (
  `id` int(11) NOT NULL,
  `imie` varchar(100) NOT NULL,
  `email` varchar(100) NOT NULL,
  `telefon` int(11) NOT NULL,
  `czas_dostawy_dni` int(11) NOT NULL,
  `aktywny` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkt`
--

CREATE TABLE `produkt` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `cena` float NOT NULL,
  `opis` text NOT NULL,
  `waga` float NOT NULL,
  `kategoria` text NOT NULL,
  `skladniki` text NOT NULL,
  `idealne_na` text NOT NULL,
  `dostepne` tinyint(1) NOT NULL,
  `przechowaniegodziny` int(255) NOT NULL,
  `zdjecia` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkt`
--

INSERT INTO `produkt` (`id`, `nazwa`, `cena`, `opis`, `waga`, `kategoria`, `skladniki`, `idealne_na`, `dostepne`, `przechowaniegodziny`, `zdjecia`) VALUES
(1, 'Ciasto Malinowe', 19.99, 'Delikatne, puszyste warstwy biszkoptu przełożone aksamitnym kremem i soczystymi malinami tworzą idealne połączenie smaków. To lekkie, a jednocześnie wyraziste ciasto doskonale sprawdzi się na każdą okazję – czy to do popołudniowej kawy, czy na wyjątkowe chwile z bliskimi 🍰💖', 1, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/4c/3f/ef/4c3fef78717fcd81f766c14e36ef2a3f.jpg'),
(2, 'Ciasto Bananowe', 24.99, 'Wilgotne, aromatyczne ciasto o intensywnym bananowym smaku zachwyca prostotą i domowym ciepłem. Każdy kęs otula podniebienie subtelną słodyczą dojrzałych bananów i nutą wanilii, tworząc deser, który przywołuje wspomnienia beztroskich chwil. Idealne na leniwe poranki, rodzinne spotkania czy chwile tylko dla siebie – z kubkiem herbaty i ulubioną książką 🍌☕📚', 1.5, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/bc/ae/14/bcae1485df5210d559d37b6a41fcc6eb.jpg'),
(3, 'Ciasto Truskawkowe', 14.99, 'Soczyste, słodkie truskawki zatopione w puszystym cieście tworzą deser, który smakuje jak pierwszy dzień lata. Delikatna masa i świeże owoce w idealnej harmonii zachwycają lekkością i naturalnością. To ciasto, które wnosi promień słońca na każdy stół – idealne na rodzinne spotkania, pikniki czy po prostu wtedy, gdy masz ochotę na coś pysznego i owocowego 🍓🌞🍰', 1, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/14/b2/ab/14b2ab10fa0f3e9322aade75a8f5d658.jpg'),
(4, 'Ciasto Czekoladowe', 14.99, 'Głęboki smak czekolady zamknięty w wilgotnym, miękkim cieście to prawdziwa uczta dla podniebienia. Każdy kęs rozpływa się w ustach, otulając słodyczą i delikatną nutą kakao. To deser stworzony dla miłośników klasycznej przyjemności – idealny na wyjątkowe okazje, romantyczne wieczory lub po prostu wtedy, gdy chcesz sprawić sobie odrobinę czekoladowego szczęścia 🍫✨🍰', 2, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/49/65/c0/4965c07f50a4a3d7a314fba2edaa9bc6.jpg'),
(5, 'Karpatka', 24.99, 'Lekko chrupiące, złociste warstwy ciasta parzonego skrywają puszysty, waniliowy krem, który rozpływa się w ustach niczym słodka chmurka. Karpatka to połączenie lekkości i kremowej rozkoszy, które przenosi do domowej kuchni pełnej zapachów i ciepła. Klasyczny smak, który nie wychodzi z mody – idealna na rodzinne spotkania, święta czy chwile, gdy masz ochotę na coś wyjątkowego 🏔️🍮💛', 1.5, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/2c/a5/24/2ca524f797235aad1a640f050d9a35b1.jpg'),
(6, 'Ciasto Cytrynowo-Jagodowe', 29.99, 'Orzeźwiająca cytryna spotyka się z leśną słodyczą jagód w lekkim, puszystym cieście, które zachwyca harmonią smaków. Delikatny krem lub wilgotna masa przełamana cytrusową nutą dodaje energii, a jagody wprowadzają naturalną słodycz i kolor. To ciasto, które smakuje jak letni spacer po lesie – idealne na ciepłe dni, garden party lub jako elegancki deser do popołudniowej herbaty 🍋🌿', 2.2, 'Ciasta', '', '', 1, 0, 'https://i.pinimg.com/736x/fb/20/d0/fb20d03b7da4786261dfd3a665277306.jpg'),
(7, 'Tort Weselny', 700, 'Wykwintne warstwy wilgotnego ciasta waniliowego przeplatają się z delikatnym kremem mascarpone i nutą świeżych truskawek. Każdy kęs to harmonijne połączenie słodyczy i owocowej świeżości, które rozpieszcza podniebienie. Ten tort to idealny sposób, by uczcić najważniejsze chwile z bliskimi — lekki, elegancki i pełen miłości. 🎂✨', 5, 'Torty', '', '', 1, 48, 'https://i.pinimg.com/736x/f9/1a/90/f91a90cc6f55c987e9c887ee035894b6.jpg'),
(8, 'Tort Urodzinowy', 150, 'Miękkie warstwy wilgotnego biszkoptu przeplatają się z kremową masą o smaku karmelu i chrupiącymi kawałkami orzechów. Tort zachwyca nieoczekiwanym połączeniem słodyczy i lekkiej nuty soli, które sprawiają, że każdy kawałek to prawdziwa uczta dla zmysłów. Idealny wybór, by uczcić ważny dzień pełen radości i uśmiechu! 🎂✨🎉', 3, 'Torty', 'biszkopt, jajka, cukier, mąka pszenna, proszek do pieczenia, masło, mleko, śmietanka kremówka, serek mascarpone, cukier puder, wanilia, barwniki spożywcze, masa cukrowa, lukier królewski, żelatyna, kwiaty z masy cukrowej, liście z masy cukrowej, syrop cukrowy, krem maślany, pasta z białej czekolady', 'Urodziny , \r\nrzyjęcie urodzinowe, \r\nrodzinną uroczystość, \r\nkażdą wyjątkową okazję', 1, 48, 'https://i.pinimg.com/736x/74/fd/b4/74fdb4fe71ce3df6dda560e50c7f78da.jpg'),
(9, 'Tort Jeżyk', 120, 'Tort w stylu rustykalnym, którego ściany przypominają naturalne słoje drewna, wykonane z delikatnego kremu o smaku wanilii i miodu. Na wierzchu soczyste jagody dodają świeżości i lekkości, a uroczy, malutki jeżyk z lukru wprowadza bajkowy akcent, który zachwyci zarówno dzieci, jak i dorosłych. To połączenie natury i słodyczy sprawi, że każde przyjęcie nabierze wyjątkowego, ciepłego charakteru. 🌿🦔🍇', 4, 'Torty', '', '', 1, 0, 'https://i.pinimg.com/736x/d9/59/75/d95975218ebd7f0e289350e80d751d1b.jpg'),
(10, 'Chleb', 5, 'Świeży, aromatyczny bochenek chleba o chrupiącej skórce i miękkim, wilgotnym wnętrzu. Wykonany z naturalnego zakwasu i starannie dobranych ziaren, które nadają mu pełny, lekko kwaskowaty smak. Idealny na każdą kanapkę, a także do serwowania z domowym masłem czy ulubionymi dodatkami. Doskonały wybór dla tych, którzy cenią tradycję i jakość pieczywa. 🍞✨', 1, 'Pieczywo', '', '', 1, 0, 'https://i.pinimg.com/736x/e3/92/19/e392193579b5f22f149ad96527c9a006.jpg'),
(11, 'Puszyste Bułki', 1.5, 'Złociste, świeże bułeczki o chrupiącej skórce i miękkim, puszystym środku. Idealne na szybkie śniadanie lub jako baza do kanapek — delikatne w smaku, a jednocześnie sycące. Przygotowane z najwyższej jakości składników, które nadają im lekko maślany aromat i naturalną świeżość każdego dnia. Doskonałe towarzystwo zarówno na słodko, jak i na wytrawnie. 🥖✨\r\n\r\n', 0.05, 'Pieczywo', '', '', 1, 0, 'https://i.pinimg.com/736x/9b/16/71/9b1671148fa41818fc9017cfb086f9e5.jpg'),
(12, 'Croissant', 4.99, 'Maślany, lekko chrupiący na zewnątrz, a jednocześnie puszysty i delikatny w środku croissant, który rozpływa się w ustach. Idealny na poranne śniadanie lub popołudniową przekąskę, samodzielnie lub z ulubionym nadzieniem — od klasycznej czekolady po kremowy ser. Tradycyjna receptura i najwyższej jakości masło nadają mu niepowtarzalny smak i aromat prosto z francuskiej piekarni. 🥐✨', 0.08, 'Pieczywo', '', '', 1, 0, 'https://i.pinimg.com/736x/c9/ca/a2/c9caa2b453305e0d0be411684b414b36.jpg'),
(13, 'Babeczka Czekoladowa', 3.99, 'Wilgotna i intensywnie czekoladowa babeczka, która rozpieszcza każde podniebienie. Gładka, kremowa polewa z ciemnej czekolady i delikatny posmak kakao tworzą idealną harmonię smaku. Babeczka zachwyca nie tylko aromatem, ale także puszystą konsystencją, dzięki czemu jest idealnym deserem na każdą okazję — od popołudniowej kawy po słodkie zakończenie dnia. 🍫🧁✨', 0.1, 'Babeczki', '', '', 1, 0, 'https://i.pinimg.com/736x/55/4e/8a/554e8a244ccc00d945fcdf65d32210a8.jpg'),
(14, 'Babeczka Waniliowa', 4.99, 'Delikatna, puszysta babeczka o subtelnym aromacie prawdziwej wanilii, która rozbudza zmysły przy każdym kęsie. Lekki krem waniliowy lub lukier dopełnia smak, tworząc harmonijną kompozycję słodyczy i delikatności. Idealna propozycja na słodką przekąskę, która doda uroku każdemu spotkaniu i doskonale komponuje się z herbatą lub kawą. 🍰🌿✨', 0.1, 'Babeczki', '', '', 1, 0, 'https://i.pinimg.com/736x/87/79/b8/8779b87d225803c705406c3e4d3b2961.jpg'),
(15, 'Babeczka Truskawkowa', 4.99, 'Lekka i puszysta babeczka, przepełniona naturalnym smakiem świeżych truskawek, które nadają jej soczystość i owocową świeżość. Delikatny krem śmietankowy lub waniliowy otula babeczkę, tworząc idealne połączenie słodyczy i naturalnej kwaskowatości owoców. To doskonały wybór na wiosenne i letnie przyjęcia, który doda koloru i smaku każdemu deserowemu stołowi. 🍓🧁🌸', 0.1, 'Babeczki', '', '', 1, 0, 'https://i.pinimg.com/736x/b1/14/63/b1146339a9c3099c9f99f2c237137563.jpg'),
(17, 'Ciasteczka z Czekoladowym Nadzieniem ', 0.5, 'Miękkie, aromatyczne ciasteczka z aksamitnym, rozpływającym się w ustach czekoladowym nadzieniem. Na wierzchu znajdziesz kawałki najwyższej jakości czekolady, które dodają chrupkości i intensywnego smaku. To słodka pokusa, która zadowoli każdego miłośnika czekolady i sprawi, że chwile przy kawie staną się jeszcze bardziej wyjątkowe. 🍪🍫✨', 0.02, 'Ciasteczka', '', '', 1, 0, 'https://i.pinimg.com/736x/4b/b3/04/4bb304703429700ab1651373d6940daf.jpg'),
(18, 'Ciasteczka Półfrancuskie z Marmoladą', 1.5, 'Delikatne, kruche ciasteczka półfrancuskie, wypełnione słodką, aromatyczną marmoladą, która nadaje im wyrazisty owocowy smak. Ich maślana struktura i subtelna słodycz idealnie komponują się z naturalną kwaskowatością marmolady, tworząc tradycyjny smakołyk, który doskonale pasuje do popołudniowej herbaty lub kawy. Klasyka w lekkim, wyjątkowym wydaniu. 🍪🍓✨', 0.03, 'Ciasteczka', '', '', 1, 0, 'https://img.wprost.pl/img/znasz-te-polskie-ciasteczka-sa-kruche-latwe-w-przygotowaniu-i-zawsze-sie-udaja/2c/e7/8be6f4de3aca67c55c66a8d36314.webp'),
(19, 'Pączki z różą', 2.5, 'Miękkie, puszyste pączki nadziewane aromatycznym kremem różanym, posypane cukrem pudrem. Tradycyjny smak, który zawsze cieszy podniebienie.', 0.07, 'Inne', '', '', 1, 0, 'https://wszystkiegoslodkiego.pl/storage/images/20246/paczki-z-roza-2-copy.jpg'),
(20, 'Makaronik', 1.5, 'Delikatne, kolorowe makaroniki o chrupiącej, lekko miękkiej skorupce i kremowym, aromatycznym nadzieniu. Te francuskie ciasteczka zachwycają nie tylko wyglądem, ale też wyjątkową lekkością i subtelnym smakiem, który może być owocowy, orzechowy lub czekoladowy. Idealne na eleganckie przyjęcia lub jako słodki prezent, który urzeka każdego miłośnika słodyczy. 🌈✨', 0.01, 'Inne', '', '', 1, 0, 'https://i.pinimg.com/736x/e0/cb/9e/e0cb9ec8dbacff5c6c77209bcdf3bfeb.jpg'),
(21, 'Tort Komunijny', 120, 'Delikatny tort komunijny o jasnym biszkopcie, przełożony aksamitnym kremem śmietankowo-waniliowym. Ozdobiony subtelnymi dekoracjami z masy cukrowej i lukru królewskiego, idealnie wpisuje się w podniosły charakter Pierwszej Komunii Świętej. Lekki, elegancki i wyjątkowy – stworzony na ten niezapomniany dzień.', 1.5, 'Torty', 'jajka, cukier, mąka pszenna, proszek do pieczenia, masło, mleko, śmietanka kremówka, serek mascarpone, biała czekolada, cukier puder, wanilia, masa cukrowa, lukier królewski, barwniki spożywcze (złoty, biały, perłowy), opłatek komunijny, dekoracje z masy cukrowej, syrop cukrowy, żelatyna', 'Pierwszą Komunię Świętą, \r\n\r\nUroczystość komunijną, \r\n\r\nTen wyjątkowy dzień, \r\n\r\nŚwiętowanie sakramentu, \r\n\r\nKomunijne przyjęcie z rodziną.', 1, 48, 'https://jacekplacek.com.pl/wp-content/uploads/2022/05/ZDJ_0078.jpg'),
(22, 'Tort Dekoracyjny', 119.99, 'Wyjątkowy tort dekoracyjny, będący prawdziwym dziełem sztuki cukierniczej. Mistrzowsko wykonany, ozdobiony precyzyjnymi detalami i eleganckimi akcentami, z lukrem plastycznym, z czekoladą, z złoceniami. Idealny jako centralny punkt stołu, zachwycający wyglądem i podkreślający wyjątkowy charakter Twojej uroczystości. Zaprojektowany, by olśniewać.', 2, 'Torty', '', '', 1, 48, 'https://torcik.net/wp-content/uploads/2021/02/Stempel-Dekoracyjny-Miniowe-Formy-wykonanie-Slodka-Fabryka-3.jpg'),
(23, 'Tort na 18', 80, '...', 1.5, 'Torty', '...', '18', 1, 48, 'https://i.pinimg.com/736x/e1/aa/f9/e1aaf996db8a65d196a8199eeddbdc0b.jpg');

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `produkt_alergen`
--

CREATE TABLE `produkt_alergen` (
  `produkt_id` int(11) NOT NULL,
  `alergen_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `produkt_alergen`
--

INSERT INTO `produkt_alergen` (`produkt_id`, `alergen_id`) VALUES
(2, 1),
(2, 2),
(2, 3),
(8, 1),
(8, 2),
(8, 3),
(8, 5);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `promocje`
--

CREATE TABLE `promocje` (
  `id` int(11) NOT NULL,
  `nazwa` text NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `opis` text NOT NULL,
  `kwota_zniżnki` double NOT NULL,
  `data_rozpoczecia` date NOT NULL,
  `data_zakończenia` date NOT NULL,
  `aktywna` tinyint(1) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `przedmiot_zamowienia`
--

CREATE TABLE `przedmiot_zamowienia` (
  `id` int(11) NOT NULL,
  `zamowienie_id` int(11) NOT NULL,
  `ilosc` int(11) NOT NULL,
  `cena_za_1` double NOT NULL,
  `produkt_id` int(11) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `przedmiot_zamowienia`
--

INSERT INTO `przedmiot_zamowienia` (`id`, `zamowienie_id`, `ilosc`, `cena_za_1`, `produkt_id`) VALUES
(1, 1, 1, 150, 0);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `status_zamowienia`
--

CREATE TABLE `status_zamowienia` (
  `id` int(11) NOT NULL,
  `zamowienie_id` int(11) NOT NULL,
  `produkt_id` int(11) NOT NULL,
  `specjalne_prośby` text NOT NULL,
  `wartość` text NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `user`
--

CREATE TABLE `user` (
  `id` int(11) NOT NULL,
  `imie` text NOT NULL,
  `nazwisko` text NOT NULL,
  `email` varchar(255) NOT NULL,
  `haslo` varchar(255) NOT NULL,
  `numer_telefonu` int(11) DEFAULT NULL,
  `adres` text DEFAULT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `user`
--

INSERT INTO `user` (`id`, `imie`, `nazwisko`, `email`, `haslo`, `numer_telefonu`, `adres`) VALUES
(6, 'Admin', '1', 'Karola9873@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$N3RsZXMxV3kxUnJkRkhMTg$1wUX/ok5QX4YqOfCtIDQtME7yBpTaDQEXiZcyiZ0tDM', 111111111, 'pomidorowa 9'),
(7, 'Oskar', 'Mazurek', 'o_mazurek@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Z3ZmZEZ1NExtU1VzZXQxVA$MBjJ8R+ZtMlmPoduudZwS6BZLWSn0ZG3v26eiqtMtn8', NULL, NULL),
(11, 'Robert', 'Lewandowski', 'lewy@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$bEdNTUVuYzhoOHBkMDc1aA$Cn29MV+7kMMxXDYG9xQiTt9TwR0Q76Zu8ERVtHPbWmI', NULL, NULL),
(12, 'a', 'a', 'aaaaaaaaaa@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OUowcTkuajUxRi9GbVVvSA$ZEZskOIhdgUArB2mBBcglDlTx2Mi5kL2baerFLufPV0', NULL, NULL),
(13, 'karo', 'a', 'email@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$Tk91OGRFV21rYklhQllGTQ$DAA/DPKpXwjz2d3zVGFrnepnxF1xV9/C3F3kN96OJ9M', NULL, NULL),
(14, 'a', 'a', 'aaaaaaaaaaAAA@gmail.com', '$argon2i$v=19$m=65536,t=4,p=1$OWVMSmpjdFJNT3cycDFtVw$poV7Kkly1eaQc2Qum/nM7JphLkCv+l/OwdIu4n8sioY', NULL, NULL);

-- --------------------------------------------------------

--
-- Struktura tabeli dla tabeli `zamowienie`
--

CREATE TABLE `zamowienie` (
  `id` int(11) NOT NULL,
  `status` enum('oczekujące','w realizacji','gotowe do wysyłki','w dostawie','zakończone','anulowane') NOT NULL,
  `data_zamowienia` date NOT NULL,
  `id_uzytkownika` int(11) NOT NULL,
  `opis` text NOT NULL,
  `koszt` float NOT NULL,
  `dostawa` varchar(255) NOT NULL,
  `uwagi` varchar(255) NOT NULL
) ENGINE=InnoDB DEFAULT CHARSET=utf8mb4 COLLATE=utf8mb4_general_ci;

--
-- Dumping data for table `zamowienie`
--

INSERT INTO `zamowienie` (`id`, `status`, `data_zamowienia`, `id_uzytkownika`, `opis`, `koszt`, `dostawa`, `uwagi`) VALUES
(1, 'oczekujące', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Małe (0,8 kg)) x1: 150.00 zł\n\nDostawa: Odbiór osobisty\nUwagi: \nSuma całkowita: 150.00 zł', 150, 'odbior', ''),
(3, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Małe (0,8 kg)) x1: 150.00 zł\n\nDostawa: Dostawa do lokalu (+20.00 zł)\nUwagi: dziekuje! ;)\nSuma całkowita: 170.00 zł', 170, 'dostawa', 'dziekuje! ;)'),
(4, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Małe (0,8 kg)) x2: 300.00 zł\n\nDostawa: Dostawa do lokalu (+20.00 zł)\nUwagi: super zamowienie! :D\nSuma całkowita: 320.00 zł', 320, 'dostawa', 'super zamowienie! :D'),
(5, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Małe (0,8 kg)) x1: 150.00 zł\n\nDostawa: Dostawa do lokalu (+20.00 zł)\nUwagi: \nSuma całkowita: 170.00 zł', 170, 'dostawa', ''),
(6, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Średnie (1,2 kg)) x2: 390.00 zł\n\nDostawa: Dostawa do lokalu (+20.00 zł)\nUwagi: prosze o szybka dostawe :)\nSuma całkowita: 410.00 zł', 410, 'dostawa', 'prosze o szybka dostawe :)'),
(7, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Średnie (1,2 kg)) x2: 390.00 zł\n- Tort Urodzinowy (Małe (0,8 kg)) x1: 150.00 zł\n\nDostawa: Dostawa do lokalu (+20.00 zł)\nUwagi: milej podrozy :>\nSuma całkowita: 560.00 zł', 560, 'dostawa', 'milej podrozy :>'),
(8, '', '2025-06-17', 6, 'Zamówienie:\n- Tort Urodzinowy (Małe (0,8 kg)) x1: 150.00 zł\n\nDostawa: Odbiór osobisty\nUwagi: \nSuma całkowita: 150.00 zł', 150, 'odbior', '');

--
-- Indeksy dla zrzutów tabel
--

--
-- Indeksy dla tabeli `alergen`
--
ALTER TABLE `alergen`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `dostawa`
--
ALTER TABLE `dostawa`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zamowienie_id` (`zamowienie_id`),
  ADD KEY `kurier_id` (`kurier_id`);

--
-- Indeksy dla tabeli `kurier`
--
ALTER TABLE `kurier`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `produkt`
--
ALTER TABLE `produkt`
  ADD PRIMARY KEY (`id`);

--
-- Indeksy dla tabeli `produkt_alergen`
--
ALTER TABLE `produkt_alergen`
  ADD KEY `produkt_id` (`produkt_id`,`alergen_id`),
  ADD KEY `alergen_id` (`alergen_id`);

--
-- Indeksy dla tabeli `promocje`
--
ALTER TABLE `promocje`
  ADD PRIMARY KEY (`id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `przedmiot_zamowienia`
--
ALTER TABLE `przedmiot_zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zamowienie_id` (`zamowienie_id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `status_zamowienia`
--
ALTER TABLE `status_zamowienia`
  ADD PRIMARY KEY (`id`),
  ADD KEY `zamowienie_id` (`zamowienie_id`),
  ADD KEY `produkt_id` (`produkt_id`);

--
-- Indeksy dla tabeli `user`
--
ALTER TABLE `user`
  ADD PRIMARY KEY (`id`),
  ADD UNIQUE KEY `email` (`email`);

--
-- Indeksy dla tabeli `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD PRIMARY KEY (`id`),
  ADD KEY `id_uzytkownika` (`id_uzytkownika`);

--
-- AUTO_INCREMENT for dumped tables
--

--
-- AUTO_INCREMENT for table `alergen`
--
ALTER TABLE `alergen`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=7;

--
-- AUTO_INCREMENT for table `produkt`
--
ALTER TABLE `produkt`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=24;

--
-- AUTO_INCREMENT for table `przedmiot_zamowienia`
--
ALTER TABLE `przedmiot_zamowienia`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=2;

--
-- AUTO_INCREMENT for table `user`
--
ALTER TABLE `user`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=15;

--
-- AUTO_INCREMENT for table `zamowienie`
--
ALTER TABLE `zamowienie`
  MODIFY `id` int(11) NOT NULL AUTO_INCREMENT, AUTO_INCREMENT=9;

--
-- Constraints for dumped tables
--

--
-- Constraints for table `dostawa`
--
ALTER TABLE `dostawa`
  ADD CONSTRAINT `dostawa_ibfk_1` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienie` (`id`),
  ADD CONSTRAINT `dostawa_ibfk_2` FOREIGN KEY (`kurier_id`) REFERENCES `kurier` (`id`);

--
-- Constraints for table `produkt_alergen`
--
ALTER TABLE `produkt_alergen`
  ADD CONSTRAINT `produkt_alergen_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `produkt` (`id`),
  ADD CONSTRAINT `produkt_alergen_ibfk_2` FOREIGN KEY (`alergen_id`) REFERENCES `alergen` (`id`);

--
-- Constraints for table `promocje`
--
ALTER TABLE `promocje`
  ADD CONSTRAINT `promocje_ibfk_1` FOREIGN KEY (`produkt_id`) REFERENCES `produkt` (`id`);

--
-- Constraints for table `przedmiot_zamowienia`
--
ALTER TABLE `przedmiot_zamowienia`
  ADD CONSTRAINT `przedmiot_zamowienia_ibfk_1` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienie` (`id`);

--
-- Constraints for table `status_zamowienia`
--
ALTER TABLE `status_zamowienia`
  ADD CONSTRAINT `status_zamowienia_ibfk_1` FOREIGN KEY (`zamowienie_id`) REFERENCES `zamowienie` (`id`),
  ADD CONSTRAINT `status_zamowienia_ibfk_2` FOREIGN KEY (`produkt_id`) REFERENCES `produkt` (`id`);

--
-- Constraints for table `zamowienie`
--
ALTER TABLE `zamowienie`
  ADD CONSTRAINT `zamowienie_ibfk_1` FOREIGN KEY (`id_uzytkownika`) REFERENCES `user` (`id`);
COMMIT;

/*!40101 SET CHARACTER_SET_CLIENT=@OLD_CHARACTER_SET_CLIENT */;
/*!40101 SET CHARACTER_SET_RESULTS=@OLD_CHARACTER_SET_RESULTS */;
/*!40101 SET COLLATION_CONNECTION=@OLD_COLLATION_CONNECTION */;
