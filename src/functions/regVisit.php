<?php
function RegVisit($time, $date, $officeID, $patientLogin)
{
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B³±d zapytania o ID nazwiska lekarza');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $regQuery = "INSERT INTO wizyty (ID_nazwiska_P,ID_gabinetu,data,godzina) VALUES ";
    $regQuery .= "(";
    $regQuery .= "'" . $patientInfoLine['id_nazwiska'] . "'" . ",";
    $regQuery .= "'" . $officeID . "'" . ",";
    $regQuery .= "'" . $date . "'" . ",";
    $regQuery .= "'" . $time . "'";
    $regQuery .= ")";
    mysql_query($regQuery) or die('B³±d zapytania nowej rezerwacji wizyty');
    echo "<br>Zarezerwowano wizytê w gabinecie: " . $officeID . " dnia: " . $date . " o godzinie: " . $time . " <br>";
}
?>
