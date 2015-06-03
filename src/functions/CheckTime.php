<?
// Funkcja sprawdzaj±ca zajêto¶æ dnia oraz wy¶wietlaj±ca t± wynik w postaci pokolorowanej komórki tabeli
// Komórka jest zielona w przypadku braku znalezienia pasuj±cego do wytycznych rekordu
// Komórka jest czerwona z wypisanym nazwiskiem lekarza w przypadku znalezienia pasuj±cego do wytycznych rekordu
function checkTime($day, $time, $date, $idGabinetu) {

    $time = date_format($time, 'H:i:s');
    $date = date_format($date, 'Y-m-d');

    $queryCheckTime = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='" . $day . "' AND ID_gabinetu='" . $idGabinetu . "'";
    $resultCheckTime = mysql_query($queryCheckTime) or die('B³±d zapytania');
    if ($resultCheckTime) {
        unset($tabRecord);
        while ($line = mysql_fetch_assoc($resultCheckTime)) {
            if (($line['od_godziny'] <= $time) && ($line['do_godziny'] >= $time) && ($line['od_dnia'] <= $date) && ($line['do_dnia'] >= $date)) {
                $queryDoc = "SELECT nazwisko FROM nazwiska WHERE id_nazwiska='" . $line['ID_nazwiska_Lek'] . "'";
                $docResult = mysql_query($queryDoc) or die('B³±d zapytania');
                $docData = mysql_fetch_assoc($docResult);
                $tabRecord = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $docData['nazwisko'] . "</td>";
            }
        }
    }
    if (!isset($tabRecord)) {
        $tabRecord = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
    }
    echo $tabRecord;
}
?>
