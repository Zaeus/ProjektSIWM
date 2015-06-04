<?php
include("GenerateDate.php");

// Funkcja signUpForDoc - funkcja odpowiedzialna za generowanie tablicy zapis�w
function signUpForDoc($officeSpecialization, $regDate)
{
    echo "<br><fieldset><legend>Dost�pne gabinety o specjalizacji: " . $officeSpecialization . "</legend>";
    $officeSpecQuery = "SELECT zajetosc.ID_gabinetu, zajetosc.ID_nazwiska_Lek, zajetosc.dzien_tyg, zajetosc.od_dnia, zajetosc.do_dnia, zajetosc.od_godziny, zajetosc.do_godziny, budynki.miasto FROM gabinety ";
    $officeSpecQuery .= "INNER JOIN zajetosc ON gabinety.ID_gabinetu = zajetosc.ID_gabinetu ";
    $officeSpecQuery .= "INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
    $officeSpecQuery .= "WHERE gabinety.specjalnosc='" . $officeSpecialization . "'";
    $officeSpecResult = mysql_query($officeSpecQuery) or die('B��d zapytania o gabinety o podanej specjalizacji');
    $howMuchLines = mysql_num_rows($officeSpecResult);
    if($howMuchLines > 0) {
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>Dane lekarza</td>";
        echo "<td>Miasto</td>";
        echo "<td>Dzie� tygodnia</td>";
        echo "<td>Dost�pny od</td>";
        echo "<td>Dost�pny do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Opcje</td>";
        echo "</tr>";
        while($officeSpecLine = mysql_fetch_assoc($officeSpecResult)) {
            $today = new DateTime();
            if(($officeSpecLine['od_dnia'] <= date_format($today, 'Y-m-d')) && (date_format($today, 'Y-m-d') <= $officeSpecLine['do_dnia'])) {
                echo "<tr>";
                $docNameQuery = "SELECT imie, nazwisko FROM nazwiska WHERE id_nazwiska='" . $officeSpecLine['ID_nazwiska_Lek'] . "'";
                $docNameResult = mysql_query($docNameQuery) or die('B��d zapytania o nazwisko lekarza');
                $docNameLine = mysql_fetch_assoc($docNameResult);
                echo "<td>" . "dr " . $docNameLine['imie'] . " " . $docNameLine['nazwisko'] . "</td>";
                echo "<td>" . $officeSpecLine['miasto'] . "</td>";
                echo "<td>" . $officeSpecLine['dzien_tyg'] . "</td>";
                echo "<td>" . $officeSpecLine['od_dnia'] . "</td>";
                echo "<td>" . $officeSpecLine['do_dnia'] . "</td>";
                echo "<td>" . $officeSpecLine['od_godziny'] . "</td>";
                echo "<td>" . $officeSpecLine['do_godziny'] . "</td>";
                echo "<td><form action = \"signUpForDoc.php\" method=\"POST\"> ";
                // TODO obs�uga - data je�el nie jest w zakresie daj komunikat b��du
                if (isset($regDate)) {
                    $finalDate = date_create($regDate);
                    switch ($officeSpecLine['dzien_tyg']) {
                        case "Pon":
                            $dayOfTheWeek = "Monday";
                            break;
                        case "Wto":
                            $dayOfTheWeek = "Tuesday";
                            break;
                        case "Sro":
                            $dayOfTheWeek = "Wednesday";
                            break;
                        case "Czw":
                            $dayOfTheWeek = "Thursday";
                            break;
                        case "Pia":
                            $dayOfTheWeek = "Friday";
                            break;
                        default:
                            $dayOfTheWeek = "Monday";
                            break;
                    }
                    while (date_format($finalDate, 'l') != $dayOfTheWeek) {
                        date_modify($finalDate, '+1 day');
                    }
                    $timeVisitQuery = "SELECT godzina FROM wizyty WHERE ID_gabinetu='" . $officeSpecLine['ID_gabinetu'] . "' AND data='" . date_format($finalDate, 'Y-m-d') . "'";
                    $timeVisitResult = mysql_query($timeVisitQuery) or die('B��d zapytania o nazwisko lekarza');
                    $iterator = 0;
                    while ($timeVisitLine = mysql_fetch_assoc($timeVisitResult)) {
                        // Sprawdzenie czy godzina mie�cie si� w pracy gabinetu
                        if (($officeSpecLine['od_godziny'] <= $timeVisitLine['godzina']) && ($officeSpecLine['do_godziny'] >= $timeVisitLine['godzina'])) {
                            $occupiedHours[$iterator] = date_create($timeVisitLine['godzina']);
                            $iterator = $iterator + 1;
                        }
                    }
                    sort($occupiedHours);
                    echo "<select name=\"godzinaRezerwacji\">";
                    $start = date_create($officeSpecLine['od_godziny']);
                    $stop = date_create($officeSpecLine['do_godziny']);
                    $stop = date_modify($stop, '-30 minutes');
                    if (isset($occupiedHours)) {
                        // Je�eli w bazie danych s� godziny zaj�tych wizyt to wchodzimy w tego if'a
                        $temp = clone($occupiedHours[0]);
                        generateDate($start, date_modify($temp, '-30 minutes'));
                        for ($i = 0; $i < count($occupiedHours); $i++) {
                            $temp = clone($occupiedHours[$i]);
                            if (isset($occupiedHours[$i + 1])) {
                                echo "tutaj";
                                $temp2 = clone($occupiedHours[$i + 1]);
                                generateDate(date_modify($temp, '+30 minutes'), date_modify($temp2, '-30 minutes'));
                            } else {
                                generateDate(date_modify($temp, '+30 minutes'), $stop);
                            }
                        }
                    } else {
                        // Je�eli nie ma zaj�tych godzin to generujemy pe�ny selektor od startu do stopu
                        generateDate($start, $stop);
                    }
                    echo "</select> ";
                    echo "<input type=\"week\" name=\"notImportantDate\" value=\"" . $regDate . "\" disabled> ";
                    echo "<input type=\"hidden\" name=\"finalRegDate\" value=\"" . date_format($finalDate, 'Y-m-d') . "\">";
                    echo "<input type=\"hidden\" name=\"officeID\" value=\"" . $officeSpecLine['ID_gabinetu'] . "\">";
                    echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
                    echo "</tr>";
                    unset($temp, $temp2, $start, $stop, $occupiedHours);
                } else {
                    echo "<input type=\"week\" name=\"regDate\" value=\"" . date_format(new DateTime(), 'Y') . "-W" . date_format(new DateTime(), 'W') . "\" > ";
                    echo "<input type=\"submit\" value=\"Dalej\" ></form></td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table>";
    } else {
        echo "Brak gabinet�w o podanej specjalizacji";
    }
    echo "</fieldset>";
}

// Funkcja regVisit - funkcja odpowiedzialna za kwerend� zapisania nowej wizyty do bazy danych
function regVisit($time, $date, $officeID, $patientLogin)
{
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B��d zapytania o ID nazwiska lekarza');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $regQuery = "INSERT INTO wizyty (ID_nazwiska_P,ID_gabinetu,data,godzina) VALUES ";
    $regQuery .= "(";
    $regQuery .= "'" . $patientInfoLine['id_nazwiska'] . "'" . ",";
    $regQuery .= "'" . $officeID . "'" . ",";
    $regQuery .= "'" . $date . "'" . ",";
    $regQuery .= "'" . $time . "'";
    $regQuery .= ")";
    mysql_query($regQuery) or die('B��d zapytania nowej rezerwacji wizyty');
    echo "<br>Zarezerwowano wizyt� w gabinecie: " . $officeID . " dnia: " . $date . " o godzinie: " . $time . " <br>";
}

// Funkcja viewMyVisit - buduj�ca tabel� ze wszystkimi wizytami osoby zalogowanej
function viewMyVisit($patientLogin)
{
    echo "<br><fieldset><legend>Twoje wizyty:</legend>";
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B��d zapytania o ID pacjenta');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $myVisitQuery = "SELECT * FROM wizyty WHERE id_nazwiska_P='" . $patientInfoLine['id_nazwiska'] . "'";
    $visitResult = mysql_query($myVisitQuery) or die('B��d zapytania o wizyty');
    $myVisitLineNumber = mysql_num_rows($visitResult);
    if($myVisitLineNumber > 0){
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Gabinet</td>";
        echo "<td>Specjalizacja</td>";
        echo "<td>Miasto</td>";
        echo "<td>Data wizyty</td>";
        echo "<td>Godzina wizyty</td>";
        echo "<td>Opcje</td>";
        while($visitLine = mysql_fetch_assoc($visitResult)){
            $officeInfoQuery = "SELECT budynki.miasto, gabinety.specjalnosc FROM gabinety INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
            $officeInfoQuery .= "WHERE gabinety.ID_gabinetu='" . $visitLine['ID_gabinetu'] . "'";
            $officeInfoResult = mysql_query($officeInfoQuery) or die('B��d zapytania o gabinety o podanym ID');
            $officeInfoLine = mysql_fetch_assoc($officeInfoResult);
            echo "<tr>";
            echo "<td>" . $visitLine['ID_gabinetu'] . "</td>";
            echo "<td>" . $officeInfoLine['specjalnosc'] . "</td>";
            echo "<td>" . $officeInfoLine['miasto'] . "</td>";
            echo "<td>" . $visitLine['data'] . "</td>";
            echo "<td>" . $visitLine['godzina'] . "</td>";
            echo "<td><form action = \"signUpForDoc.php\" method=\"POST\"> ";
            echo "<input type=\"hidden\" name=\"removeVisitOfficeID\" value=\"" . $visitLine['ID_gabinetu'] . "\">";
            echo "<input type=\"hidden\" name=\"removeVisitDate\" value=\"" . $visitLine['data'] . "\">";
            echo "<input type=\"hidden\" name=\"removeVisitTime\" value=\"" . $visitLine['godzina']  . "\">";
            echo "<input type=\"submit\" value=\"Usu�\" ";
            echo ($visitLine['data'] == date_format(date_modify(new DateTime(), '+1 day'), 'Y-m-d'));
            if($visitLine['data'] == date_format(new DateTime(), 'Y-m-d')){
                echo "disabled";
            }
            echo " ></form></td>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Nie posiadasz �adnych wizyt";
    }
    echo "</fieldset><br>";
}

// Funkcja removeMyVisit - funkcja odpowiedzialna za kwerend� usuni�cia wizyty z bazy danych
function removeMyVisit($patientLogin, $officeID, $date, $time)
{
    $removeVisitInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $removeVisitInfoResult = mysql_query($removeVisitInfoQuery) or die('B��d zapytania od ID nazwiska lekarza');
    $removeVisitInfoLine = mysql_fetch_assoc($removeVisitInfoResult);
    $removeVisitQuery = "DELETE FROM wizyty WHERE ";
    $removeVisitQuery .= "ID_nazwiska_P='" . $removeVisitInfoLine['id_nazwiska'] . "' AND ";
    $removeVisitQuery .= "ID_gabinetu='" . $officeID . "' AND ";
    $removeVisitQuery .= "data='" . $date . "' AND ";
    $removeVisitQuery .= "godzina='" . $time . "'";
    mysql_query($removeVisitQuery) or die('B��d zapytania usuni�cia rezerwacji');
}
?>