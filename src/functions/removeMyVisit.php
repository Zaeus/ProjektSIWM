<?php
function removeMyVisit($patientLogin, $officeID, $date, $time)
{
    $removeVisitInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $removeVisitInfoResult = mysql_query($removeVisitInfoQuery) or die('B³±d zapytania od ID nazwiska lekarza');
    $removeVisitInfoLine = mysql_fetch_assoc($removeVisitInfoResult);
    $removeVisitQuery = "DELETE FROM wizyty WHERE ";
    $removeVisitQuery .= "ID_nazwiska_P='" . $removeVisitInfoLine['id_nazwiska'] . "' AND ";
    $removeVisitQuery .= "ID_gabinetu='" . $officeID . "' AND ";
    $removeVisitQuery .= "data='" . $date . "' AND ";
    $removeVisitQuery .= "godzina='" . $time . "'";
    mysql_query($removeVisitQuery) or die('B³±d zapytania usuniêcia rezerwacji');
}
?>
