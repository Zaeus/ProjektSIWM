<?
// Funkcja sprawdzająca zajętość dnia oraz wyświetlająca tą wynik w postaci pokolorowanej komórki tabeli
function checkTime($day, $hour, $date) {
    $kwerenda_checkTime = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='" . $day . "' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
    $wynik_checkTime = mysql_query($kwerenda_checkTime) or die('Błąd zapytania');
    if ($wynik_checkTime) {
        // TODO kwerenda pobierająca nazwisko lekarza z tablicy nazwisk
        unset($tab_wiersz);
        while ($wiersz = mysql_fetch_assoc($wynik_checkTime)) {
            if (($wiersz['od_godziny'] <= $hour) && ($wiersz['do_godziny'] >= $hour) && ($wiersz['od_dnia'] <= date_format($date, 'Y-m-d')) && ($wiersz['do_dnia'] >= date_format($date, 'Y-m-d'))) {
                $kwerenda_Lekarz = "SELECT nazwisko FROM nazwiska WHERE id_nazwiska='" . $wiersz['ID_nazwiska_Lek'] . "'";
                $wynik_Lekarz = mysql_query($kwerenda_Lekarz) or die('Błąd zapytania');
                $dane_Lekarz = mysql_fetch_assoc($wynik_Lekarz);
                $tab_wiersz = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $dane_Lekarz['nazwisko'] . "</td>";
            }
        }
    }
    if (!isset($tab_wiersz)) {
        $tab_wiersz = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
    }
    echo $tab_wiersz;
}
?>