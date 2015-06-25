<?php

// Funkcja drawTable - odpowiada za
function drawTable($date, $idGabinetu){
    $openingTime = date_create(OPENING_TIME);
    $closingTime = date_create(CLOSING_TIME);
    date_modify($closingTime,'+'.VISIT_DURATION);
    $dateShowDay = clone $date;
    if(isset($idGabinetu)){
        ?>
        <br>
        <div class="CSSTableGenerator" >
        <table align="center" cellpadding="5" border="1">
            <tr bgcolor>
                <td style="text-align: center;">Godzina</td>
                <td style="text-align: center;">Poniedziałek<br>
                    <?
                    echo date_format($dateShowDay, 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Wtorek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Środa<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Czwartek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Piątek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
            </tr>
            <?
            $time = $openingTime;
            while($time < $closingTime) {
                ?>
                <tr bgcolor=white>
                    <td width="100" style="text-align: center;">
                        <?
                        echo date_format($time,'H:i');
                        ?>
                    </td>
                    <?
                    // Sprawdzenie poniedziałku
                    checkTime("Pon", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie wtorku
                    checkTime("Wto", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie środy
                    checkTime("Sro", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie czwartku
                    checkTime("Czw", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie piątku
                    checkTime("Pia", $time, $date, $idGabinetu);
                    date_modify($date, '-4 days')
                    ?>
                </tr>
                <?
                $time = date_modify($time, '+'.VISIT_DURATION);
            }
            ?>
        </table></div>
    <?
    }
}

// Funkcja checkTime - sprawdzająca zajętość dnia oraz wyświetlająca tą wynik w postaci pokolorowanej komórki tabeli
// Komórka jest zielona w przypadku braku znalezienia pasującego do wytycznych rekordu
// Komórka jest czerwona z wypisanym nazwiskiem lekarza w przypadku znalezienia pasującego do wytycznych rekordu
function checkTime($day, $time, $date, $idGabinetu) {

    $time = date_format($time, 'H:i:s');
    $date = date_format($date, 'Y-m-d');

    $queryCheckTime = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='" . $day . "' AND ID_gabinetu='" . $idGabinetu . "'";
    $resultCheckTime = mysql_query($queryCheckTime) or die('Błąd zapytania');
    if ($resultCheckTime) {
        unset($tabRecord);
        while ($line = mysql_fetch_assoc($resultCheckTime)) {
            if (($line['od_godziny'] <= $time) && ($line['do_godziny'] > $time) && ($line['od_dnia'] <= $date) && ($line['do_dnia'] >= $date)) {
                $queryDoc = "SELECT nazwisko FROM nazwiska WHERE id_nazwiska='" . $line['ID_nazwiska_Lek'] . "'";
                $docResult = mysql_query($queryDoc) or die('Błąd zapytania');
                $docData = mysql_fetch_assoc($docResult);
                $tabRecord = "<td  bgcolor=\"red\" style=\"text-align: center\">" . $docData['nazwisko'] . "</td>";
            }
        }
    }
    if (!isset($tabRecord)) {
        $tabRecord = "<td></td>";
    }
    echo $tabRecord;
}

// Funkcja reservationTable - odpowiadająca za
function reservationTable($day, $fromTime, $toTime, $sinceDate, $toDate, $docEmail, $break){

    date_modify($fromTime, '-'.$break);
    date_modify($toTime, '+'.$break);

    $reservationQuery = "SELECT ID_gabinetu FROM gabinety";
    $reservationResult = mysql_query($reservationQuery) or die('Błąd zapytania');
    if($reservationResult) {
        echo "<br><fieldset><legend>Wolne gabinety:</legend><div class=\"CSSTableGenerator\" ><table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>ID Gabinetu</td>";
        echo "<td>Dzień tygodnia</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Od dnia</td>";
        echo "<td>Do dnia</td>";
        echo "<td>Opcje</td>";
        echo "</tr>";
        // Informacja o specjalnosci doktora
        $infoDocQuery = "SELECT specjalizacja FROM nazwiska WHERE email='" . $docEmail . "'";
        $infoDocResult = mysql_query($infoDocQuery) or die('Błąd zapytania');
        $infoDoc = mysql_fetch_assoc($infoDocResult);
        $iterator = 0;
        $reservationTable[] = array();
        while($reservationLine = mysql_fetch_assoc($reservationResult)) {
            $reservationTable[$iterator] = $reservationLine['ID_gabinetu'];
            $iterator++;
        }
        sort($reservationTable);
        foreach($reservationTable as $key => $val) {
            $checkForReservationQuery = "SELECT od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE ID_gabinetu='" . $val . "' AND dzien_tyg='" . $day . "'";
            $checkForReservationResult = mysql_query($checkForReservationQuery);
            $numberOfLine = mysql_num_rows($checkForReservationResult);
            // Informacja o specjalnosci gabinetu
            $officeInfoQuery = "SELECT specjalnosc FROM gabinety WHERE ID_gabinetu='" . $val . "'";
            $officeInfoResult = mysql_query($officeInfoQuery) or die('Błąd zapytania');
            $officeInfo = mysql_fetch_assoc($officeInfoResult);
            // Sprawdzenie zgodności specjalności lekarza - w przypadku admina ('NULL') wypisujemy wszystkie dostępne dla danej daty
            if(($officeInfo['specjalnosc'] == $infoDoc['specjalizacja']) || ($infoDoc['specjalizacja'] == NULL)) {
                if ($numberOfLine) {
                    $iteratorReservation = 0;
                    $timeArrayOfReservation[] = array();
                    $dateArrayOfReservation[] = array();
                    while ($line = mysql_fetch_assoc($checkForReservationResult)) {
                        // Wszystkie if-else muszą być wewnątrz pętli while żeby wykryć wszystkie możliwe niedopasowania
                        // Sprawdzenie wszystkich możliwych zachodzeń godzin
                        if(($line['od_godziny'] <= $fromTime) && ($line['do_godziny'] >= $toTime)) {
                            $timeArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_godziny'] <= $fromTime) && ($line['do_godziny'] <= $toTime) && ($line['do_godziny'] >= $fromTime)) {
                            $timeArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_godziny'] >= $fromTime) && ($line['do_godziny'] >= $toTime) && ($line['od_godziny'] <= $toTime)) {
                            $timeArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_godziny'] >= $fromTime) && ($line['do_godziny'] <= $toTime)) {
                            $timeArrayOfReservation[$iteratorReservation] = "NotOk";
                        } else{
                            $timeArrayOfReservation[$iteratorReservation] = "Ok";
                        }
                        // Sprawdzenie wszystkich możliwych zachodzeń daty
                        $iteratorReservation = $iteratorReservation + 1;
                        if(($line['od_dnia'] <= $sinceDate) && ($line['do_dnia'] >= $toDate)) {
                            $dateArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_dnia'] <= $sinceDate) && ($line['do_dnia'] <= $toDate) && ($line['do_dnia'] >= $sinceDate)) {
                            $dateArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_dnia'] >= $sinceDate) && ($line['do_dnia'] >= $toDate) && ($line['od_dnia'] <= $toDate)) {
                            $dateArrayOfReservation[$iteratorReservation] = "NotOk";
                        } elseif(($line['od_dnia'] >= $sinceDate) && ($line['do_dnia'] <= $toDate)) {
                            $dateArrayOfReservation[$iteratorReservation] = "NotOk";
                        } else{
                            $dateArrayOfReservation[$iteratorReservation] = "Ok";
                        }
                        $iteratorReservation++;
                    }
                    $decisionDate = "Ok";
                    $decisionTime = "Ok";
                    // Sumaryczne sprawdzenie czy data jest ok
                    foreach($dateArrayOfReservation as $iterator => $value){
                        if($value == "NotOk"){
                            $decisionDate = "NotOk";
                        }
                    }
                    // W przypadku kiedy data nie jest ok, sprawdzamy czy czas jest ok - jeżeli jest to oznacza że w danym dniu może być ponowna rezerwacja danego gabinetu
                    // W przypadku kiedy data jest ok godziną się nawet nie przejmujemy
                    if($decisionDate == "NotOk"){
                        foreach($timeArrayOfReservation as $iterator => $value){
                            if($value == "NotOk"){
                                $decisionTime = "NotOk";
                            }
                        }
                    }
                    date_modify($fromTime, '+'.$break);
                    date_modify($toTime, '-'.$break);
                    if (($decisionDate == "Ok") || ($decisionTime == "Ok")) {
                        // Gabinet w podanym czasie w podanej dacie nie jest zajęty - można go zająć
                        echo "<tr>";
                        echo "<td>" . $val . "</td>";
                        echo "<td>" . $day . "</td>";
                        echo "<td>" . $fromTime . "</td>";
                        echo "<td>" . $toTime . "</td>";
                        echo "<td>" . $sinceDate . "</td>";
                        echo "<td>" . $toDate . "</td>";
                        echo "<td><form action = \"docOffices.php\" method=\"POST\"> ";
                        echo "<input type=\"hidden\" name=\"Day\" value=\"" . $day . "\">";
                        echo "<input type=\"hidden\" name=\"SinceDate\" value=\"" . $sinceDate . "\">";
                        echo "<input type=\"hidden\" name=\"ToDate\" value=\"" . $toDate . "\">";
                        echo "<input type=\"hidden\" name=\"FromTime\" value=\"" . $fromTime . "\">";
                        echo "<input type=\"hidden\" name=\"ToTime\" value=\"" . $toTime . "\">";
                        echo "<input type=\"hidden\" name=\"ID_Gabinetu\" value=\"" . $val . "\">";
                        echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
                        echo "</tr>";
                    }
                } else {
                    // Gabinet nie jest nigdy zajęty
                    echo "<tr>";
                    echo "<td>" . $val . "</td>";
                    echo "<td>" . $day . "</td>";
                    echo "<td>" . $fromTime . "</td>";
                    echo "<td>" . $toTime . "</td>";
                    echo "<td>" . $sinceDate . "</td>";
                    echo "<td>" . $toDate . "</td>";
                    echo "<td><form action = \"docOffices.php\" method=\"POST\"> ";
                    echo "<input type=\"hidden\" name=\"Day\" value=\"" . $day . "\">";
                    echo "<input type=\"hidden\" name=\"SinceDate\" value=\"" . $sinceDate . "\">";
                    echo "<input type=\"hidden\" name=\"ToDate\" value=\"" . $toDate . "\">";
                    echo "<input type=\"hidden\" name=\"FromTime\" value=\"" . $fromTime . "\">";
                    echo "<input type=\"hidden\" name=\"ToTime\" value=\"" . $toTime . "\">";
                    echo "<input type=\"hidden\" name=\"ID_Gabinetu\" value=\"" . $val . "\">";
                    echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
                    echo "</tr>";
                }
            }
        }
        echo "</table><br></fieldset>";

    }
    else{
        echo "Brak gabinetów w bazie danych";
    }
}

// Funkcja reservationQuery - odpowiada za wstawienie nowej rezerwacji gabinetu
function reservationQuery($docEmail, $officeID, $day, $sinceDate, $toDate, $fromTime, $toTime)
{
    $emailInfoQuery = "SELECT id_nazwiska, pozostalo_kontraktu FROM nazwiska WHERE email='" . $docEmail . "'";
    $infoResult = mysql_query($emailInfoQuery) or die('Błąd zapytania o ID nazwiska lekarza');
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
    $diff = strtotime($toDate, 0) - strtotime($sinceDate, 0);
    $weeks =  floor($diff / 604800);
    $hours = $infoLine['pozostalo_kontraktu'] - (($weeks + 1) * ($toTime - $fromTime));
    $contractUpdateQuery = "UPDATE nazwiska SET pozostalo_kontraktu='" . $hours . "' WHERE email='"  . $docEmail . "'";
    mysql_query($contractUpdateQuery) or die('Błąd zapytania uaktualnienia pozostałego czasu kontraktu');
}

// Funkcja reservationRemoveQuery - odpowiada za usunięcie rezerwacji gabinetu
function reservationRemoveQuery($docEmail, $officeID, $day, $fromTime, $toTime, $sinceDate, $toDate)
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
    mysql_query($removeReservationQuery) or die('Błąd zapytania usunięcia rezerwacji');
}

// Funkcja viewMyReservationTable - odpowiada za wyświetlenie tabeli ze wszystkimi rezerwacjami gabinetów na aktualnego użytkownika
function viewMyReservationTable($docEmail){

    // Usunięcie rezerwacja jeżeli w tabeli powstałej w ViewMyReservationTable zostaje kliknięty przycisk usuń
    if(isset($_POST['RemoveDay'])){
        reservationRemoveQuery($_SESSION['login'], $_POST['RemoveID_Gabinetu'], $_POST['RemoveDay'], $_POST['RemoveFromTime'], $_POST['RemoveToTime'], $_POST['RemoveSinceDate'], $_POST['RemoveToDate']);
    }

    echo "<br><fieldset><legend>Twoje najmy gabinetów:</legend>";
    $emailDocQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $infoDocResult = mysql_query($emailDocQuery) or die('Błąd zapytania od ID nazwiska lekarza');
    $infoDocLine = mysql_fetch_assoc($infoDocResult);
    $checkReservationQuery = "SELECT ID_gabinetu, dzien_tyg, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE id_nazwiska_Lek='" . $infoDocLine['id_nazwiska'] . "'";
    $checkReservationResult = mysql_query($checkReservationQuery) or die('Błąd zapytania o posiadane najmy');
    $numberOfRecords = mysql_num_rows($checkReservationResult);
    if($numberOfRecords > 0) {
        echo "<div class=\"CSSTableGenerator\" ><table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>ID Gabinetu</td>";
        echo "<td>Dzień tygodnia</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Od dnia</td>";
        echo "<td>Do dnia</td>";
        echo "<td>Opcje</td>";
        echo "</tr>";
        while($checkReservationLine = mysql_fetch_assoc($checkReservationResult)) {
            echo "<tr>";
            echo "<td>" . $checkReservationLine['ID_gabinetu'] . "</td>";
            echo "<td>" . $checkReservationLine['dzien_tyg'] . "</td>";
            echo "<td>" . $checkReservationLine['od_godziny'] . "</td>";
            echo "<td>" . $checkReservationLine['do_godziny'] . "</td>";
            echo "<td>" . $checkReservationLine['od_dnia'] . "</td>";
            echo "<td>" . $checkReservationLine['do_dnia'] . "</td>";
            echo "<td><form action = \"docOffices.php\" method=\"POST\"> ";
            echo "<input type=\"hidden\" name=\"RemoveDay\" value=\"" . $checkReservationLine['dzien_tyg'] . "\">";
            echo "<input type=\"hidden\" name=\"RemoveSinceDate\" value=\"" . $checkReservationLine['od_dnia'] . "\">";
            echo "<input type=\"hidden\" name=\"RemoveToDate\" value=\"" . $checkReservationLine['do_dnia'] . "\">";
            echo "<input type=\"hidden\" name=\"RemoveFromTime\" value=\"" . $checkReservationLine['od_godziny'] . "\">";
            echo "<input type=\"hidden\" name=\"RemoveToTime\" value=\"" . $checkReservationLine['do_godziny'] . "\">";
            echo "<input type=\"hidden\" name=\"RemoveID_Gabinetu\" value=\"" . $checkReservationLine['ID_gabinetu'] . "\">";
            echo "<button name=\"usun_najm\" type=\"submit\" \">Usuń</button>";
            echo "</form></td>";
        }
        echo "</table></div>";
    }
    else {
        echo "Nie posiadasz żadnych najmowanych gabinetów";
    }
    echo "</fieldset>";
}

function dateButtons($previousButton, $nextButton, $initialization){
    echo "<fieldset><legend>Czas:</legend>";
    if(isset($previousButton)){
        date_modify($_SESSION['date'], '-1 week');
        unset($previousButton);
    }
    elseif(isset($nextButton)) {
        date_modify($_SESSION['date'], '+1 week');
        unset($nextButton);
    }
    elseif(isset($initialization)){
        $_SESSION['date'] = new DateTime();
    }
    while (date_format($_SESSION['date'], 'l') != "Monday") {
        date_modify($_SESSION['date'], '-1 day');
    }
    $endDate = clone $_SESSION['date'];
    date_modify($endDate, '+4 day');
    echo "Początek tygodnia:" . date_format($_SESSION['date'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($endDate, 'Y-m-d') . "<br>";
    ?>

    <form action="docOffices.php" method="POST">
        <input type="submit" value="Tydzień wstecz" name="Wstecz" />
        <input type="submit" value="Tydzień w przód" name="Dalej" />
    </form>
    </fieldset>
<?
}

function rentOffice($dayPost, $openingHourPost, $closingHourPost, $fromDayPost,  $toDayPost, $login){

    echo "<br><fieldset><legend>Zajmij gabinet:</legend><form action=\"docOffices.php\" method=\"POST\">";
    $opening = date_create(OPENING_TIME);
    $closing = date_create(CLOSING_TIME);
    $closingMinWorkTimeCheck = clone $closing;
    date_modify($closingMinWorkTimeCheck,'-'.MIN_WORK_DURATION);

    if (isset($dayPost)) {
        $_SESSION['Dzien'] = $dayPost;
        echo "Godzina rozpoczęcia: " . "<select name=\"GodzinaRozpoczecia\">";
        generateDate($opening, $closingMinWorkTimeCheck);
        submitButton('Dalej');
    } elseif (isset($openingHourPost)) {
        $_SESSION['GodzinaRozpoczecia'] = $openingHourPost;
        $timeBegin = date_create($_SESSION['GodzinaRozpoczecia']);
        $maxWorkTime = clone $timeBegin;
        date_modify($timeBegin, '+'.MIN_WORK_DURATION);
        date_modify($maxWorkTime, '+'.MAX_WORK_DURATION);
        echo "Godzina zakończenia: " . "<select name=\"GodzinaZakonczenia\">";
        if($closing<$maxWorkTime) {
            generateDate($timeBegin, $closing);
        }else{
            generateDate($timeBegin, $maxWorkTime);
        }
        submitButton('Dalej');
    } elseif (isset($closingHourPost)) {
            $_SESSION['GodzinaZakonczenia'] = $closingHourPost;
            echo "Data rozpoczęcia najmu gabinetu:";
            $today = date_create();
            $maxDate = clone $today;
            date_modify($maxDate, '+'.MAX_DATE);
            generateDays($_SESSION['Dzien'], $today, $maxDate, 'OdDnia');
            submitButton('Dalej');
    } elseif (isset($fromDayPost)) {
        $_SESSION['OdDnia'] = $fromDayPost;
        echo "Data zakończenia najmu gabinetu:";
        $maxDate = date_create();
        $fromDay =date_create($fromDayPost);
        date_modify($maxDate, '+'.MAX_DATE);
        generateDays($_SESSION['Dzien'], $fromDay, $maxDate, 'DoDnia');
        submitButton('Zobacz gabinety');
    } elseif(isset($toDayPost)){
        daysSelection();
        reservationTable($_SESSION['Dzien'], $_SESSION['GodzinaRozpoczecia'], $_SESSION['GodzinaZakonczenia'], $_SESSION['OdDnia'], $toDayPost, $login, OFFICE_BREAK);
        unset($_SESSION['Dzien']);
        unset($_SESSION['GodzinaRozpoczecia']);
        unset($_SESSION['GodzinaZakonczenia']);
        unset($_SESSION['OdDnia']);
        unset($toDayPost);
    }else{
        daysSelection();
    }
    // Jeżeli reservationTable wyrzuciło dane to są one wpisywane do kwerendy
    if(isset($_POST['Day'])) {
        reservationQuery($_SESSION['login'], $_POST['ID_Gabinetu'], $_POST['Day'], $_POST['SinceDate'], $_POST['ToDate'], $_POST['FromTime'], $_POST['ToTime']);
    }
}

function generateDays($dayOfTheWeek, $start, $stop, $selectName){
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
    $dataFormula="";
    echo "<select name=\"$selectName\">";
    while($start<$stop){
        if(date_format($start,'l')==$dayOfTheWeek){
            $date = date_format($start,'Y-m-d');
            $dataFormula .= "<option value=" . $date . ">$date</option>";
            date_modify($start,'+1 week');
        }else{
            date_modify($start,'+1 day');
        }
    }
    echo $dataFormula;
    echo "</select>";
}

function submitButton ($value){
    echo "<br><input type=\"submit\" value=\"$value\" /></form></fieldset>";
}

function viewOffice(){
    $docOfficeViewQuery = "SELECT ID_gabinetu FROM zajetosc";
    $viewOfficeResult = mysql_query($docOfficeViewQuery) or die('Błąd zapytania');
    $docOfficeViewFrom = "<br><fieldset><legend>Przejrzyj zajętość gabinetów:</legend><form action = \"docOffices.php\" method=\"POST\">";
    $docOfficeViewFrom .= "Gabinet: <select name=\"ID_przegladany_gabinet\">";
    if($viewOfficeResult) {
        $iterator = 0;
        while($docOfficeViewLine = mysql_fetch_assoc($viewOfficeResult)) {
            $docOfficeTable[$iterator] = $docOfficeViewLine['ID_gabinetu'];
            $iterator = $iterator + 1;
        }
        sort($docOfficeTable);
        $docOfficeTable = array_unique($docOfficeTable);
        foreach($docOfficeTable as $key => $val){
            $docOfficeViewFrom .= "<option value=\"" . $val . "\">" . $val . "</option>";
        }
    }
    $docOfficeViewFrom .= "</select>";
    $docOfficeViewFrom .= "<input type=\"submit\" value=\"Przeglądaj gabinet\" >";
    $docOfficeViewFrom .= "</form>";
    echo $docOfficeViewFrom;
    if(isset($_POST['ID_przegladany_gabinet'])){
        $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
    }
    echo "Przeglądany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
    drawTable(clone $_SESSION['date'], $_SESSION['ID_przegladany_gabinet']);
    echo "</fieldset>";
}

function daysSelection(){

    echo "</select><br>";
    echo "<div class=\"CSSTableGenerator\" ><table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    echo "<td>Dzień tygodnia: </td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedziałek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">Środa</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Piątek<br></td>";
    echo "</tr></table></div><input type=\"submit\" value=\"Dalej\" /><br><br></form></fieldset>";
}

?>
