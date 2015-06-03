<?php
function ViewMyVisit($patientLogin)
{
    echo "<br><fieldset><legend>Twoje wizyty:</legend>";
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B³±d zapytania o ID pacjenta');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $myVisitQuery = "SELECT * FROM wizyty WHERE id_nazwiska_P='" . $patientInfoLine['id_nazwiska'] . "'";
    $visitResult = mysql_query($myVisitQuery) or die('B³±d zapytania o wizyty');
    $myVisitLineNumber = mysql_num_rows($visitResult);
    if($myVisitLineNumber > 0){
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Gabinet</td>";
        echo "<td>Specjalizacja</td>";
        echo "<td>Miasto</td>";
        echo "<td>Data</td>";
        echo "<td>Godzina</td>";
        echo "<td>Opcje</td>";
        while($visitLine = mysql_fetch_assoc($visitResult)){
            echo "<tr>";
            echo "<td>" . $visitLine['ID_gabinetu'] . "</td>";
            echo "<td></td>"; // TODO Miasto do uzupe³nienia
            echo "<td></td>"; // TODO Specjalizacja do uzupe³nienia
            echo "<td>" . $visitLine['data'] . "</td>";
            echo "<td>" . $visitLine['godzina'] . "</td>";
            echo "<td><form action = \"zapis.php\" method=\"POST\"> ";
            echo "<input type=\"submit\" value=\"Usuñ\" ></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nie posiadasz ¿adnych wizyt";
    }
    echo "</fieldset><br>";
}
?>
