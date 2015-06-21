<?php

// Funkcja drawTable - odpowiada za
function drawTable($date, $idGabinetu, $officeParameters){
    $officeParameters['closingTime'] = date_modify($officeParameters['closingTime'],'+'.$officeParameters['visitDuration']);
    $dateShowDay = clone $date;
    if(isset($idGabinetu)){
        ?>
        <br>
        <table align="center" cellpadding="5" border="1">
            <tr bgcolor>
                <td style="text-align: center;">Godzina</td>
                <td style="text-align: center;">Poniedzia³ek<br>
                    <?
                    echo date_format($dateShowDay, 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Wtorek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">¦roda<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Czwartek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
                <td style="text-align: center;">Pi±tek<br>
                    <?
                    echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                    ?>
                </td>
            </tr>
            <?
            $time = $officeParameters['openingTime'];
            while($time != $officeParameters['closingTime']) {
                ?>
                <tr bgcolor=white>
                    <td width="100" style="text-align: center;">
                        <?
                        echo date_format($time,'H:i');
                        ?>
                    </td>
                    <?
                    // Sprawdzenie poniedzia³ku
                    checkTime("Pon", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie wtorku
                    checkTime("Wto", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie ¶rody
                    checkTime("Sro", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie czwartku
                    checkTime("Czw", $time, $date, $idGabinetu);
                    date_modify($date, '+1 day');
                    // Sprawdzenie pi±tku
                    checkTime("Pia", $time, $date, $idGabinetu);
                    date_modify($date, '-4 days')
                    ?>
                </tr>
                <?
                $time = date_modify($time, '+'.$officeParameters['visitDuration']);
            }
            ?>
        </table>
    <?
    }
}

// Funkcja checkTime - sprawdzaj±ca zajêto¶æ dnia oraz wy¶wietlaj±ca t± wynik w postaci pokolorowanej komórki tabeli
// Komórka jest zielona w przypadku braku znalezienia pasuj±cego do wytycznych rekordu
// Komórka jest czerwona z wypisanym nazwiskiem lekarza w przypadku znalezienia pasuj±cego do wytycznych rekordu
function checkTime($day, $time, $date, $idGabinetu) {

    $time = date_format($time, 'H:i:s');
    $date = date_format($date, 'Y-m-d');

    $queryCheckTime = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='" . $day . "' AND ID_gabinetu='" . $idGabinetu . "'";
    $resultCheckTime = mysql_query($queryCheckTime) or die('B³±d zapytania');
    if ($resultCheckTime) {
        unset($tabRecord);
        while ($line = mysql_fetch_assoc($resultCheckTime)) {
            if (($line['od_godziny'] <= $time) && ($line['do_godziny'] > $time) && ($line['od_dnia'] <= $date) && ($line['do_dnia'] >= $date)) {
                $queryDoc = "SELECT nazwisko FROM nazwiska WHERE id_nazwiska='" . $line['ID_nazwiska_Lek'] . "'";
                $docResult = mysql_query($queryDoc) or die('B³±d zapytania');
                $docData = mysql_fetch_assoc($docResult);
                $tabRecord = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $docData['nazwisko'] . "</td>";
            }
        }
    }
    if (!isset($tabRecord)) {
        $tabRecord = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
    }
    echo $tabRecord;
}

// Funkcja reservationTable - odpowiadaj±ca za
function reservationTable($day, $fromTime, $toTime, $sinceDate, $toDate, $docEmail, $break){

    date_modify($fromTime, '-'.$break);
    date_modify($toTime, '+'.$break);

    $reservationQuery = "SELECT ID_gabinetu FROM gabinety";
    $reservationResult = mysql_query($reservationQuery) or die('B³±d zapytania');
    if($reservationResult) {
        echo "<br><fieldset><legend>Wolne gabinety:</legend><table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>ID Gabinetu</td>";
        echo "<td>Dzieñ tygodnia</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Od dnia</td>";
        echo "<td>Do dnia</td>";
        echo "<td>Opcje</td>";
        echo "</tr>";
        // Informacja o specjalnosci doktora
        $infoDocQuery = "SELECT specjalizacja FROM nazwiska WHERE email='" . $docEmail . "'";
        $infoDocResult = mysql_query($infoDocQuery) or die('B³±d zapytania');
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
            $officeInfoQuery = "SELECT specjalnosc FROM zajetosc INNER JOIN gabinety ON zajetosc.ID_gabinetu = gabinety.ID_gabinetu WHERE  zajetosc.ID_gabinetu='" . $val . "'";
            $officeInfoResult = mysql_query($officeInfoQuery) or die('B³±d zapytania');
            $officeInfo = mysql_fetch_assoc($officeInfoResult);
            // Sprawdzenie zgodno¶ci specjalno¶ci lekarza - w przypadku admina ('NULL') wypisujemy wszystkie dostêpne dla danej daty
            if(($officeInfo['specjalnosc'] == $infoDoc['specjalizacja']) || ($infoDoc['specjalizacja'] == NULL)) {
                if ($numberOfLine) {
                    $iteratorReservation = 0;
                    $timeArrayOfReservation[] = array();
                    $dateArrayOfReservation[] = array();
                    while ($line = mysql_fetch_assoc($checkForReservationResult)) {
                        // Wszystkie if-else musz± byæ wewn±trz pêtli while ¿eby wykryæ wszystkie mo¿liwe niedopasowania
                        // Sprawdzenie wszystkich mo¿liwych zachodzeñ godzin
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
                        // Sprawdzenie wszystkich mo¿liwych zachodzeñ daty
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
                    // W przypadku kiedy data nie jest ok, sprawdzamy czy czas jest ok - je¿eli jest to oznacza ¿e w danym dniu mo¿e byæ ponowna rezerwacja danego gabinetu
                    // W przypadku kiedy data jest ok godzin± siê nawet nie przejmujemy
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
                        // Gabinet w podanym czasie w podanej dacie nie jest zajêty - mo¿na go zaj±æ
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
                    // Gabinet nie jest nigdy zajêty
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
    $emailInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $infoResult = mysql_query($emailInfoQuery) or die('B³±d zapytania o ID nazwiska lekarza');
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
    mysql_query($reservationQuery) or die('B³±d zapytania nowej rezerwacji gabinetu');
    echo "<br>Wpisanie danych rezerwacj dla gabinetu o ID: " . $officeID . " do bazy danych w godzinach: <br>" . $fromTime . "-" . $toTime . "<br> od-do: <br>" . $sinceDate . "-" . $toDate . "<br>";
}

// Funkcja reservationRemoveQuery - odpowiada za usuniêcie rezerwacji gabinetu
function reservationRemoveQuery($docEmail, $officeID, $day, $fromTime, $toTime, $sinceDate, $toDate)
{
    $removeInfoQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $removeInfoResult = mysql_query($removeInfoQuery) or die('B³±d zapytania od ID nazwiska lekarza');
    $removeInfoLine = mysql_fetch_assoc($removeInfoResult);
    $removeReservationQuery = "DELETE FROM zajetosc WHERE ";
    $removeReservationQuery .= "ID_nazwiska_Lek='" . $removeInfoLine['id_nazwiska'] . "' AND ";
    $removeReservationQuery .= "ID_gabinetu='" . $officeID . "' AND ";
    $removeReservationQuery .= "dzien_tyg='" . $day . "' AND " ;
    $removeReservationQuery .= "od_godziny='" . $fromTime . "' AND ";
    $removeReservationQuery .= "do_godziny='" . $toTime . "' AND ";
    $removeReservationQuery .= "od_dnia='" . $sinceDate . "' AND ";
    $removeReservationQuery .= "do_dnia='" . $toDate . "'";
    mysql_query($removeReservationQuery) or die('B³±d zapytania usuniêcia rezerwacji');
}

// Funkcja viewMyReservationTable - odpowiada za wy¶wietlenie tabeli ze wszystkimi rezerwacjami gabinetów na aktualnego u¿ytkownika
function viewMyReservationTable($docEmail){

    // Usuniêcie rezerwacja je¿eli w tabeli powsta³ej w ViewMyReservationTable zostaje klikniêty przycisk usuñ
    if(isset($_POST['RemoveDay'])){
        reservationRemoveQuery($_SESSION['login'], $_POST['RemoveID_Gabinetu'], $_POST['RemoveDay'], $_POST['RemoveFromTime'], $_POST['RemoveToTime'], $_POST['RemoveSinceDate'], $_POST['RemoveToDate']);
    }

    echo "<br><fieldset><legend>Twoje najmy gabinetów:</legend>";
    $emailDocQuery = "SELECT id_nazwiska FROM nazwiska WHERE email='" . $docEmail . "'";
    $infoDocResult = mysql_query($emailDocQuery) or die('B³±d zapytania od ID nazwiska lekarza');
    $infoDocLine = mysql_fetch_assoc($infoDocResult);
    $checkReservationQuery = "SELECT ID_gabinetu, dzien_tyg, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE id_nazwiska_Lek='" . $infoDocLine['id_nazwiska'] . "'";
    $checkReservationResult = mysql_query($checkReservationQuery) or die('B³±d zapytania o posiadane najmy');
    $numberOfRecords = mysql_num_rows($checkReservationResult);
    if($numberOfRecords > 0) {
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>ID Gabinetu</td>";
        echo "<td>Dzieñ tygodnia</td>";
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
            echo "<button name=\"usun_najm\" type=\"submit\" \">Usuñ</button>";
            echo "</form></td>";
        }
        echo "</table>";
    }
    else {
        echo "Nie posiadasz ¿adnych najmowanych gabinetów";
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
    echo "Pocz±tek tygodnia:" . date_format($_SESSION['date'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($endDate, 'Y-m-d') . "<br>";
    ?>

    <form action="docOffices.php" method="POST">
        <input type="submit" value="Tydzieñ wstecz" name="Wstecz" />
        <input type="submit" value="Tydzieñ w przód" name="Dalej" />
    </form>
    </fieldset>
<?
}

function rentOffice($dayPost, $openingHourPost, $closingHourPost, $fromDayPost,  $toDayPost, $login, $officeParameters){

    echo "<br><fieldset><legend>Zajmij gabinet:</legend><form action=\"docOffices.php\" method=\"POST\">";
    $opening = $officeParameters['openingTime'];
    $closing = $officeParameters['closingTime'];
    $closingMinWorkTimeCheck = clone $closing;
    date_modify($closingMinWorkTimeCheck,'-'.$officeParameters['minDurationOfWork']);

    if (isset($dayPost)) {
        $_SESSION['Dzien'] = $dayPost;
        echo "Godzina rozpoczêcia: " . "<select name=\"GodzinaRozpoczecia\">";
        generateDate($opening, $closingMinWorkTimeCheck, $officeParameters['visitDuration']);
        submitButton('Dalej');
    } elseif (isset($openingHourPost)) {
        $_SESSION['GodzinaRozpoczecia'] = $openingHourPost;
        $timeBegin = date_create($_SESSION['GodzinaRozpoczecia']);
        $maxWorkTime = clone $timeBegin;
        date_modify($timeBegin, '+'.$officeParameters['minDurationOfWork']);
        date_modify($maxWorkTime, '+'.$officeParameters['maxDurationOfWork']);
        echo "Godzina zakoñczenia: " . "<select name=\"GodzinaZakonczenia\">";
        if($closing<$maxWorkTime) {
            generateDate($timeBegin, $closing, $officeParameters['visitDuration']);
        }else{
            generateDate($timeBegin, $maxWorkTime, $officeParameters['visitDuration']);
        }
        submitButton('Dalej');
    } elseif (isset($closingHourPost)) {
            $_SESSION['GodzinaZakonczenia'] = $closingHourPost;
            echo "Data rozpoczêcia najmu gabinetu:";
            $today = date_create();
            $maxDate = clone $today;
            date_modify($maxDate, '+'.$officeParameters['maxDate']);
            generateDays($_SESSION['Dzien'], $today, $maxDate, 'OdDnia');
            submitButton('Dalej');
    } elseif (isset($fromDayPost)) {
        $_SESSION['OdDnia'] = $fromDayPost;
        echo "Data zakoñczenia najmu gabinetu:";
        $maxDate = date_create();
        $fromDay =date_create($fromDayPost);
        date_modify($maxDate, '+'.$officeParameters['maxDate']);
        generateDays($_SESSION['Dzien'], $fromDay, $maxDate, 'DoDnia');
        submitButton('Zobacz gabinety');
    } elseif(isset($toDayPost)){
        daysSelection();
        reservationTable($_SESSION['Dzien'], $_SESSION['GodzinaRozpoczecia'], $_SESSION['GodzinaZakonczenia'], $_SESSION['OdDnia'], $toDayPost, $login, $officeParameters['officeBreak']);
        unset($_SESSION['Dzien']);
        unset($_SESSION['GodzinaRozpoczecia']);
        unset($_SESSION['GodzinaZakonczenia']);
        unset($_SESSION['OdDnia']);
        unset($toDayPost);
    }else{
        daysSelection();
    }
    // Je¿eli reservationTable wyrzuci³o dane to s± one wpisywane do kwerendy
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

function viewOffice($officeParameters){
    $docOfficeViewQuery = "SELECT ID_gabinetu FROM zajetosc";
    $viewOfficeResult = mysql_query($docOfficeViewQuery) or die('B³±d zapytania');
    $docOfficeViewFrom = "<br><fieldset><legend>Przejrzyj zajêto¶æ gabinet:</legend><form action = \"docOffices.php\" method=\"POST\">";
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
    $docOfficeViewFrom .= "<input type=\"submit\" value=\"Przegl±daj gabinet\" >";
    $docOfficeViewFrom .= "</form>";
    echo $docOfficeViewFrom;
    if(isset($_POST['ID_przegladany_gabinet'])){
        $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
    }
    echo "Przegl±dany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
    drawTable(clone $_SESSION['date'], $_SESSION['ID_przegladany_gabinet'], $officeParameters);
    echo "</fieldset>";
}

function daysSelection(){

    echo "</select><br>";
    echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    echo "<td>Dzieñ tygodnia: </td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedzia³ek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">¦roda</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
    echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Pi±tek<br></td>";
    echo "</tr></table><input type=\"submit\" value=\"Dalej\" /><br><br></form></fieldset>";
}

?>
