<?php
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
            if (($line['od_godziny'] <= $time) && ($line['do_godziny'] >= $time) && ($line['od_dnia'] <= $date) && ($line['do_dnia'] >= $date)) {
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

// Funkcja drawTable - odpowiada za
function drawTable($date, $idGabinetu){
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
            $time = date_create('7:00');
            while($time != date_create('21:30')) {
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
                $time = date_modify($time, '+30 minutes');
            }
            ?>
        </table>
    <?
    }
}

// Funkcja reservationTable - odpowiadaj±ca za
function reservationTable($day, $fromTime, $toTime, $sinceDate, $toDate, $docEmail){
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
            $iterator = $iterator + 1;
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
                        $iteratorReservation = $iteratorReserv + 1;
                    }
                    $desisionDate = "Ok";
                    $desisionTime = "Ok";
                    // Sumaryczne sprawdzenie czy data jest ok
                    foreach($dateArrayOfReservation as $iterator => $value){
                        if($value == "NotOk"){
                            $desisionDate = "NotOk";
                        }
                    }
                    // W przypadku kiedy data nie jest ok, sprawdzamy czy czas jest ok - je¿eli jest to oznacza ¿e w danym dniu mo¿e byæ ponowna rezerwacja danego gabinetu
                    // W przypadku kiedy data jest ok godzin± siê nawet nie przejmujemy
                    if($desisionDate == "NotOk"){
                        foreach($timeArrayOfReservation as $iterator => $value){
                            if($value == "NotOk"){
                                $desisionTime = "NotOk";
                            }
                        }
                    }
                    if (($desisionDate == "Ok") || ($desisionTime == "Ok")) {
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
function viewMyReservationTable($docEmail)
{
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
?>
