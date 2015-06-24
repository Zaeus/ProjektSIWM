<?php
include("GenerateDate.php");

// Funkcja signUpForDoc - funkcja odpowiedzialna za generowanie tablicy zapisów
function signUpForDoc($officeSpecialization)
{
    echo "<br><fieldset><legend><b>Dostêpne gabinety w systemie o specjalizacji: " . $officeSpecialization . "</b></legend>";
    $officeSpecQuery = "SELECT zajetosc.ID_gabinetu, zajetosc.ID_nazwiska_Lek, zajetosc.dzien_tyg, zajetosc.od_dnia, zajetosc.do_dnia, zajetosc.od_godziny, zajetosc.do_godziny, budynki.miasto FROM gabinety ";
    $officeSpecQuery .= "INNER JOIN zajetosc ON gabinety.ID_gabinetu = zajetosc.ID_gabinetu ";
    $officeSpecQuery .= "INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
    $officeSpecQuery .= "WHERE gabinety.specjalnosc='" . $officeSpecialization . "'";
    $officeSpecResult = mysql_query($officeSpecQuery) or die('B³±d zapytania o gabinety o podanej specjalizacji');
    $howMuchLines = mysql_num_rows($officeSpecResult);
    if($howMuchLines > 0) {
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>Dane lekarza</td>";
        echo "<td>Miasto</td>";
        echo "<td>Dzieñ tygodnia</td>";
        echo "<td>Dostêpny od</td>";
        echo "<td>Dostêpny do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Opcje</td>";
        echo "</tr>";
        while($officeSpecLine = mysql_fetch_assoc($officeSpecResult)) {
            $today = new DateTime();
            if(($officeSpecLine['od_dnia'] <= date_format($today, 'Y-m-d')) && (date_format($today, 'Y-m-d') <= $officeSpecLine['do_dnia'])) {
                echo "<tr>";
                $docNameQuery = "SELECT imie, nazwisko FROM nazwiska WHERE id_nazwiska='" . $officeSpecLine['ID_nazwiska_Lek'] . "'";
                $docNameResult = mysql_query($docNameQuery) or die('B³±d zapytania o nazwisko lekarza');
                $docNameLine = mysql_fetch_assoc($docNameResult);
                echo "<td>" . "dr " . $docNameLine['imie'] . " " . $docNameLine['nazwisko'] . "</td>";
                echo "<td>" . $officeSpecLine['miasto'] . "</td>";
                echo "<td>" . $officeSpecLine['dzien_tyg'] . "</td>";
                echo "<td>" . $officeSpecLine['od_dnia'] . "</td>";
                echo "<td>" . $officeSpecLine['do_dnia'] . "</td>";
                echo "<td>" . $officeSpecLine['od_godziny'] . "</td>";
                echo "<td>" . $officeSpecLine['do_godziny'] . "</td>";
                echo "<td><form action = \"signUpForDoc.php\" method=\"POST\"> ";
                generateDays($officeSpecLine['dzien_tyg'],  $officeSpecLine['do_dnia'], $officeSpecLine['od_dnia'] );
                echo "<input type=\"hidden\" name=\"officeID\" value=\"" . $officeSpecLine['ID_gabinetu'] . "\">";
                echo "<input type=\"hidden\" name=\"fromTime\" value=\"" . $officeSpecLine['od_godziny'] . "\">";
                echo "<input type=\"hidden\" name=\"sinceDate\" value=\"" . $officeSpecLine['od_dnia'] . "\">";
                echo "<input type=\"submit\" value=\"Dalej\" ></form></td>";
                echo "</tr>";
                }
            }
        echo "</table>";
    } else {
        echo "Brak gabinetów o podanej specjalizacji";
    }
    echo "</fieldset>";
}

// Funkcja signUpForDoc - funkcja odpowiedzialna za generowanie danych kwerendy zapisu
function signUpForDoc2($officeSpecialization, $regDate, $officeID, $fromTime, $sinceDate) {
    echo "<br><fieldset><legend><b>Rezerwacja w gabinecie nr." . $officeID . " o specjalizacji: " . $officeSpecialization . "</b></legend>";
    $officeSpecQuery = "SELECT zajetosc.ID_gabinetu, zajetosc.ID_nazwiska_Lek, zajetosc.dzien_tyg, zajetosc.od_dnia, zajetosc.do_dnia, zajetosc.od_godziny, zajetosc.do_godziny, budynki.miasto FROM gabinety ";
    $officeSpecQuery .= "INNER JOIN zajetosc ON gabinety.ID_gabinetu = zajetosc.ID_gabinetu ";
    $officeSpecQuery .= "INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
    $officeSpecQuery .= "WHERE gabinety.ID_gabinetu='" . $officeID . "' ";
    $officeSpecQuery .= "AND zajetosc.od_godziny='" . $fromTime . "' ";
    $officeSpecQuery .= "AND zajetosc.od_dnia='" . $sinceDate . "'";
    $officeSpecResult = mysql_query($officeSpecQuery) or die('B³±d zapytania o gabinety o podanej specjalizacji');
    echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    echo "<td>Dane lekarza</td>";
    echo "<td>Miasto</td>";
    echo "<td>Dzieñ tygodnia</td>";
    echo "<td>Dnia</td>";
    echo "<td>Opcje</td>";
    echo "</tr>";
    while($officeSpecLine = mysql_fetch_assoc($officeSpecResult)) {
        $today = new DateTime();
        if(($officeSpecLine['od_dnia'] <= date_format($today, 'Y-m-d')) && (date_format($today, 'Y-m-d') <= $officeSpecLine['do_dnia'])) {
            echo "<tr>";
            $docNameQuery = "SELECT imie, nazwisko FROM nazwiska WHERE id_nazwiska='" . $officeSpecLine['ID_nazwiska_Lek'] . "'";
            $docNameResult = mysql_query($docNameQuery) or die('B³±d zapytania o nazwisko lekarza');
            $docNameLine = mysql_fetch_assoc($docNameResult);
            echo "<td>" . "dr " . $docNameLine['imie'] . " " . $docNameLine['nazwisko'] . "</td>";
            echo "<td>" . $officeSpecLine['miasto'] . "</td>";
            echo "<td>" . $officeSpecLine['dzien_tyg'] . "</td>";
            echo "<td>" . $regDate . "</td>";
            echo "<td><form action = \"signUpForDoc.php\" method=\"POST\"> ";
            $finalDate = date_create($regDate);
            $timeVisitQuery = "SELECT godzina FROM wizyty WHERE ID_gabinetu='" . $officeSpecLine['ID_gabinetu'] . "' AND data='" . date_format($finalDate, 'Y-m-d') . "'";
            $timeVisitResult = mysql_query($timeVisitQuery) or die('B³±d zapytania o nazwisko lekarza');
            $iterator = 0;
            while ($timeVisitLine = mysql_fetch_assoc($timeVisitResult)) {
            // Sprawdzenie czy godzina mie¶cie siê w pracy gabinetu
                if (($officeSpecLine['od_godziny'] <= $timeVisitLine['godzina']) && ($officeSpecLine['do_godziny'] >= $timeVisitLine['godzina'])) {
                    $occupiedHours[$iterator] = date_create($timeVisitLine['godzina']);
                    $iterator = $iterator + 1;
                }
            }
            sort($occupiedHours);
            echo "<select name=\"godzinaRezerwacji\">";
            $start = date_create($officeSpecLine['od_godziny']);
            $stop = date_create($officeSpecLine['do_godziny']);
            $stop = date_modify($stop, '-'.VISIT_DURATION);
            if (isset($occupiedHours)) {
                // Je¿eli w bazie danych s± godziny zajêtych wizyt to wchodzimy w tego if'a
                $temp = clone($occupiedHours[0]);
                generateDate($start, date_modify($temp, '-'.VISIT_DURATION));
                for ($i = 0; $i < count($occupiedHours); $i++) {
                    $temp = clone($occupiedHours[$i]);
                    if (isset($occupiedHours[$i + 1])) {
                        echo "tutaj";
                        $temp2 = clone($occupiedHours[$i + 1]);
                        generateDate(date_modify($temp, '+'.VISIT_DURATION), date_modify($temp2, '-'.VISIT_DURATION));
                    } else {
                        generateDate(date_modify($temp, '+'.VISIT_DURATION), $stop);
                    }
                }
            } else {
                // Je¿eli nie ma zajêtych godzin to generujemy pe³ny selektor od startu do stopu
                generateDate($start, $stop);
            }
            echo "</select> ";
            echo "<input type=\"hidden\" name=\"finalRegDate\" value=\"" . date_format($finalDate, 'Y-m-d') . "\">";
            echo "<input type=\"hidden\" name=\"officeID\" value=\"" . $officeSpecLine['ID_gabinetu'] . "\">";
            echo "<input type=\"hidden\" name=\"docID\" value=\"" . $officeSpecLine['ID_nazwiska_Lek'] . "\">";
            echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
            echo "</tr>";
            unset($temp, $temp2, $start, $stop, $occupiedHours);
        }
    }
    echo "</table>";
    echo "</fieldset>";
}

function generateDays($dayOfTheWeek, $toDay, $fromDay){
    switch ($dayOfTheWeek) {
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
    $dataFormula = "";
    echo "<select name=\"regDate\">";
    $start = date_create($fromDay);
    $stop = date_create($toDay);
    if($start <= date_create()){
        $start = date_create();
        //Ile wcze¶niej mo¿na siê zapisywaæ (np. min 24h przed)
        date_modify($start,'+'.DAYS_BEFORE_VISIT_MIN);
    }
    $stopCheck = clone $start;
    if($stop>date_modify($stopCheck, '+'.DAYS_BEFORE_VISIT_MAX)){
        //ile wcze¶niej mo¿na siê zapisywaæ (np. max 8 tygodni przed)
        $stop=$stopCheck;
    }
    while($start < $stop){
        if(date_format($start,'l')==$dayOfTheWeek){
            $date = date_format($start,'Y-m-d');
            $dataFormula .= "<option value=" . $date . ">$date</option>";
            date_modify($start,'+1 week');
        }else{
            date_modify($start,'+1 day');
        }
    }
    echo $dataFormula;
}

// Funkcja regVisit - funkcja odpowiedzialna za kwerendê zapisania nowej wizyty do bazy danych
function regVisit($time, $date, $officeID, $docID, $patientLogin)
{
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B³±d zapytania o ID nazwiska lekarza');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $today = new DateTime();
    if($date >= date_format($today, 'Y-m-d')) {
        $regQuery = "INSERT INTO wizyty (ID_nazwiska_Lek, ID_nazwiska_P,ID_gabinetu,data,godzina) VALUES ";
        $regQuery .= "(";
        $regQuery .= "'" . $docID . "'" . ",";
        $regQuery .= "'" . $patientInfoLine['id_nazwiska'] . "'" . ",";
        $regQuery .= "'" . $officeID . "'" . ",";
        $regQuery .= "'" . $date . "'" . ",";
        $regQuery .= "'" . $time . "'";
        $regQuery .= ")";
        mysql_query($regQuery) or die('B³±d zapytania nowej rezerwacji wizyty');
        echo "<br><i>Zarezerwowano wizytê w gabinecie: " . $officeID . " dnia: " . $date . " o godzinie: " . $time . " </i><br>";
    } else {
        echo "<br><i>Nie mo¿na zarezerwowaæ wizyty w gabinecie w czasie przesz³ym </i><br>";
    }
}

// Funkcja viewMyVisit - buduj±ca tabelê ze wszystkimi wizytami osoby zalogowanej
function viewMyVisit($patientLogin)
{
    echo "<br><fieldset><legend><b>Twoje wizyty:</b></legend>";
    echo "Mo¿esz usuwaæ wizyty, ale nie pó¼niej ni¿ <b>24 godziny</b> przed zaplanowan± wizyt±<br><br>";
    $patientInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $patientLogin . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B³±d zapytania o ID pacjenta');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    $myVisitQuery = "SELECT * FROM wizyty WHERE id_nazwiska_P='" . $patientInfoLine['id_nazwiska'] . "'";
    $visitResult = mysql_query($myVisitQuery) or die('B³±d zapytania o wizyty');
    $myVisitLineNumber = mysql_num_rows($visitResult);
    $i=0;
    while($visitLine = mysql_fetch_assoc($visitResult)){
        $result[$i]=$visitLine;
        $i++;
    }
    $sortedResult = array_orderby($result, 'data', SORT_DESC, 'godzina', SORT_DESC);
    if($myVisitLineNumber > 0){
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Gabinet</td>";
        echo "<td>Dane lekarza</td>";
        echo "<td>Specjalizacja</td>";
        echo "<td>Miasto</td>";
        echo "<td>Data wizyty</td>";
        echo "<td>Godzina wizyty</td>";
        echo "<td>Opcje</td>";
        foreach($sortedResult as $visitLine){
            $docInfoQuery = "SELECT imie, nazwisko FROM nazwiska WHERE id_nazwiska='" . $visitLine['ID_nazwiska_Lek'] . "'";
            $docInfoResult = mysql_query($docInfoQuery) or die('B³±d zapytania o nazwisko o podanym ID');
            $docInfoLine = mysql_fetch_assoc($docInfoResult);
            $officeInfoQuery = "SELECT budynki.miasto, gabinety.specjalnosc FROM gabinety INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
            $officeInfoQuery .= "WHERE gabinety.ID_gabinetu='" . $visitLine['ID_gabinetu'] . "'";
            $officeInfoResult = mysql_query($officeInfoQuery) or die('B³±d zapytania o gabinety o podanym ID');
            $officeInfoLine = mysql_fetch_assoc($officeInfoResult);
            echo "<tr>";
            echo "<td>" . $visitLine['ID_gabinetu'] . "</td>";
            echo "<td> dr " . $docInfoLine['imie'] . " " . $docInfoLine['nazwisko'] . "</td>";
            echo "<td>" . $officeInfoLine['specjalnosc'] . "</td>";
            echo "<td>" . $officeInfoLine['miasto'] . "</td>";
            echo "<td>" . $visitLine['data'] . "</td>";
            echo "<td>" . $visitLine['godzina'] . "</td>";
            $visitDate = date_create($visitLine['data'] . " " . $visitLine['godzina']);
            if(date_create() < $visitDate) {
                echo "<td><form action = \"signUpForDoc.php\" method=\"POST\"> ";
                echo "<input type=\"hidden\" name=\"removeVisitOfficeID\" value=\"" . $visitLine['ID_gabinetu'] . "\">";
                echo "<input type=\"hidden\" name=\"removeVisitDate\" value=\"" . $visitLine['data'] . "\">";
                echo "<input type=\"hidden\" name=\"removeVisitTime\" value=\"" . $visitLine['godzina'] . "\">";
                echo "<input type=\"submit\" value=\"Usuñ\" ";
                echo($visitLine['data'] == date_format(date_modify(new DateTime(), '+1 day'), 'Y-m-d'));
                // Zablokowanie mo¿liwo¶ci usuniêcia wizyty je¿eli do wizyty zosta³o mniej ni¿ 24h
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
        echo "</table>";
    } else {
        echo "Nie posiadasz ¿adnych wizyt";
    }
    echo "</fieldset><br>";
}

// Funkcja removeMyVisit - funkcja odpowiedzialna za kwerendê usuniêcia wizyty z bazy danych
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
function array_orderby()
{
    $args = func_get_args();
    $data = array_shift($args);
    foreach ($args as $n => $field) {
        if (is_string($field)) {
            $tmp = array();
            foreach ($data as $key => $row)
                $tmp[$key] = $row[$field];
            $args[$n] = $tmp;
        }
    }
    $args[] = &$data;
    call_user_func_array('array_multisort', $args);
    return array_pop($args);
}
?>

