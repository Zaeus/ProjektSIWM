<?
function ReservationTable($day, $fromTime, $toTime, $sinceDate, $toDate, $docEmail){
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
        foreach($reservationTable as $key => $val){
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
                    // W przypadku kiedy data nie jest ok, sprawdzamy czy czas jest ok - w przypadku kiedy data jest ok godzin± siê nawet nie przejmujemy
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
                        echo "<td><form action = \"gabinet.php\" method=\"POST\"> ";
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
                    echo "<td><form action = \"gabinet.php\" method=\"POST\"> ";
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
?>
