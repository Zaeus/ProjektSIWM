<?php
function reservationQuery($docEmail, $officeID, $day, $sinceDate, $toDate, $fromTime, $toTime)
{
    $emailInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $infoResult = mysql_query($emailInfoQuery) or die('Błąd zapytania od ID nazwiska lekarza');
    $infoLine = mysql_fetch_assoc($infoResult);
    $reservationQuery = "INSERT INTO zajetosc (ID_nazwiska_Lek,ID_gabinetu,dzien_tyg,od_dnia,do_dnia,od_godziny,do_godziny) VALUES ";
    $reservationQuery .= "(";
    $reservationQuery .= "'" . $infoLine['id_nazwiska'] . "'" . ",";
    $reservationQuery .= "'" . $officeID . "'" . ",";
    $reservationQuery .= "'" . $day . "'" . ",";
    $reservationQuery .= "'" . $sinceDate . "'" . ",";
    $reservationQuery .= "'" . $toDate . "'" . ",";
    $reservationQuery .= "'" . $fromTime . "'" . ",";
    $reservationQuery .= "'" . $toTime . "'";
    $reservationQuery .= ")";
    mysql_query($reservationQuery) or die('Błąd zapytania nowej rezerwacji gabinetu');
    echo "<br>Wpisanie danych rezerwacj dla gabinetu o ID: " . $officeID . " do bazy danych w godzinach: <br>" . $fromTime . "-" . $toTime . "<br> od-do: <br>" . $sinceDate . "-" . $toDate . "<br>";
}
?>
