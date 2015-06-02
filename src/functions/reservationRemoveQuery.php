<?php
function ReservationRemoveQuery($docEmail, $officeID, $day, $fromTime, $toTime, $sinceDate, $toDate)
{
    $removeInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $removeInfoResult = mysql_query($removeInfoQuery) or die('Błąd zapytania od ID nazwiska lekarza');
    $removeInfoLine = mysql_fetch_assoc($removeInfoResult);
    $removeReservationQuery = "DELETE FROM zajetosc WHERE ";
    $removeReservationQuery .= "ID_nazwiska_Lek='" . $removeInfoLine['id_nazwiska'] . "' AND ";
    $removeReservationQuery .= "ID_gabinetu='" . $officeID . "' AND ";
    $removeReservationQuery .= "dzien_tyg='" . $day . "' AND " ;
    $removeReservationQuery .= "od_godziny='" . $fromTime . "' AND ";
    $removeReservationQuery .= "do_godziny='" . $toTime . "' AND ";
    $removeReservationQuery .= "od_dnia='" . $sinceDate . "' AND ";
    $removeReservationQuery .= "do_dnia='" . $toDate . "'";
    echo $removeReservationQuery;
    mysql_query($removeReservationQuery) or die('Błąd zapytania usunięcia rezerwacji');
}
?>
