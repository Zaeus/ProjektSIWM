<?php
// Funkcja allMyVisitsTable odpowiada za narysowanie tablicy zawierającej wszystkie wizyty do aktualnie zalogowanego lekarza
function allMyVisitsTable($userLogin)
{
    echo "<br><fieldset><legend><b>Wizyty do twoich gabinetów:</b></legend>";
    echo "Możesz usuwać wizyty, ale nie później niż <b>24 godziny</b> przed zaplanowaną wizytą<br><br>";
    $docInfoQuery = "SELECT zajetosc.ID_gabinetu, nazwiska.id_nazwiska FROM nazwiska INNER JOIN zajetosc ON zajetosc.ID_nazwiska_Lek = nazwiska.id_nazwiska WHERE email ='" . $userLogin . "'";
    $docInfoResult = mysql_query($docInfoQuery) or die('Błąd zapytania o ID gabinetów');
    $myOfficesNumber = mysql_num_rows($docInfoResult);
    if($myOfficesNumber > 0){
        $iterator = 0;
        while($docInfoLine = mysql_fetch_assoc($docInfoResult)){
            $allDocOffices[$iterator] = $docInfoLine['ID_gabinetu'];
            $iterator++;
            $docID = $docInfoLine['id_nazwiska'];
        }
        sort($allDocOffices);
        $allDocOffices = array_unique($allDocOffices);
        echo "<div class=\"CSSTableGenerator\"><table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Pacjent</td>";
        echo "<td>Gabinet</td>";
        echo "<td>Miasto</td>";
        echo "<td>Data wizyty</td>";
        echo "<td>Godzina wizyty</td>";
        echo "<td>Opcje</td>";
        foreach($allDocOffices as $key => $officeID){
            $myVisitQuery = "SELECT * FROM wizyty WHERE ID_gabinetu='" . $officeID . "' AND ID_nazwiska_Lek='" . $docID . "'";
            $visitResult = mysql_query($myVisitQuery) or die('Błąd zapytania o wizyty');
            $myVisitLineNumber = mysql_num_rows($visitResult);
            if($myVisitLineNumber > 0) {
                while($visitLine = mysql_fetch_assoc($visitResult)){
                    $officeInfoQuery = "SELECT budynki.miasto FROM gabinety INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
                    $officeInfoQuery .= "WHERE gabinety.ID_gabinetu='" . $visitLine['ID_gabinetu'] . "'";
                    $officeInfoResult = mysql_query($officeInfoQuery) or die('Błąd zapytania o gabinety o podanym ID');
                    $officeInfoLine = mysql_fetch_assoc($officeInfoResult);
                    $patientInfoQuery = "SELECT nazwisko, imie FROM nazwiska WHERE id_nazwiska='" . $visitLine['ID_nazwiska_P'] . "'";
                    $patientInfoResult = mysql_query($patientInfoQuery) or die('Błąd zapytania o ID pacjenta');
                    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
                    echo "<tr>";
                    echo "<td>" . $patientInfoLine['nazwisko'] . " " .  $patientInfoLine['imie'] . "</td>";
                    echo "<td>" . $officeID . "</td>";
                    echo "<td>" . $officeInfoLine['miasto'] . "</td>";
                    echo "<td>" . $visitLine['data'] . "</td>";
                    echo "<td>" . $visitLine['godzina'] . "</td>";
                    $visitDate = date_create($visitLine['data'] . " " . $visitLine['godzina']);
                    if(date_create() < $visitDate) {
                        echo "<td><form action = \"docMyVisits.php\" method=\"POST\"> ";
                        echo "<input type=\"hidden\" name=\"removeVisitOfficeID\" value=\"" . $visitLine['ID_gabinetu'] . "\">";
                        echo "<input type=\"hidden\" name=\"removeVisitDate\" value=\"" . $visitLine['data'] . "\">";
                        echo "<input type=\"hidden\" name=\"removeVisitTime\" value=\"" . $visitLine['godzina'] . "\">";
                        echo "<input type=\"hidden\" name=\"removePatientID\" value=\"" . $visitLine['ID_nazwiska_P'] . "\">";
                        echo "<input type=\"submit\" value=\"Usuń\" ";
                        echo($visitLine['data'] == date_format(date_modify(new DateTime(), '+1 day'), 'Y-m-d'));
                        // Zablokowanie możliwości usunięcia wizyty jeżeli do wizyty zostało mniej niż 24h
                        date_modify($visitDate, '-1 day');
                        if ((date_create() > $visitDate) && (date_create($visitLine['data'] . " " . $visitLine['godzina']) < $visitDate)) {
                            echo "disabled";
                        }
                        echo " ></form>";
                    } else {
                        echo "<td>Dane zarchiwizowane";
                    }
                    echo "</td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table></div>";
    } else {
        echo "Nie posiadasz żadnych gabinetów";
    }
    echo "</fieldset><br>";
}

// Funkcja removeVisitToMyOffice
function removeVisitToMyOffice($docLogin, $patientID, $officeID, $date, $time)
{
    $removeVisitInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docLogin . "'";
    $removeVisitInfoResult = mysql_query($removeVisitInfoQuery) or die('Błąd zapytania od ID nazwiska lekarza');
    $removeVisitInfoLine = mysql_fetch_assoc($removeVisitInfoResult);
    $removeVisitQuery = "DELETE FROM wizyty WHERE ";
    $removeVisitQuery .= "ID_nazwiska_P='" . $patientID . "' AND ";
    $removeVisitQuery .= "ID_nazwiska_Lek='" . $removeVisitInfoLine['id_nazwiska'] . "' AND ";
    $removeVisitQuery .= "ID_gabinetu='" . $officeID . "' AND ";
    $removeVisitQuery .= "data='" . $date . "' AND ";
    $removeVisitQuery .= "godzina='" . $time . "'";
    mysql_query($removeVisitQuery) or die('Błąd zapytania usunięcia rezerwacji');
}
?>
