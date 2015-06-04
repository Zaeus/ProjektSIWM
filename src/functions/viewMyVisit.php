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
            $officeInfoQuery = "SELECT budynki.miasto, gabinety.specjalnosc FROM gabinety INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
            $officeInfoQuery .= "WHERE gabinety.ID_gabinetu='" . $visitLine['ID_gabinetu'] . "'";
            $officeInfoResult = mysql_query($officeInfoQuery) or die('B³±d zapytania o gabinety o podanym ID');
            $officeInfoLine = mysql_fetch_assoc($officeInfoResult);
            echo "<tr>";
            echo "<td>" . $visitLine['ID_gabinetu'] . "</td>";
            echo "<td>" . $officeInfoLine['specjalnosc'] . "</td>";
            echo "<td>" . $officeInfoLine['miasto'] . "</td>";
            echo "<td>" . $visitLine['data'] . "</td>";
            echo "<td>" . $visitLine['godzina'] . "</td>";
            echo "<td><form action = \"zapis.php\" method=\"POST\"> ";
            echo "<input type=\"hidden\" name=\"removeVisitOfficeID\" value=\"" . $visitLine['ID_gabinetu'] . "\">";
            echo "<input type=\"hidden\" name=\"removeVisitDate\" value=\"" . $visitLine['data'] . "\">";
            echo "<input type=\"hidden\" name=\"removeVisitTime\" value=\"" . $visitLine['godzina']  . "\">";
            echo "<input type=\"submit\" value=\"Usuñ\" ";
            echo ($visitLine['data'] == date_format(date_modify(new DateTime(), '+1 day'), 'Y-m-d'));
            if($visitLine['data'] == date_format(new DateTime(), 'Y-m-d')){
                echo "disabled";
            }
            echo " ></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nie posiadasz ¿adnych wizyt";
    }
    echo "</fieldset><br>";
}
?>
