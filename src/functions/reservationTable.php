<?
function ReservationTable($day, $fromTime, $toTime, $sinceDate, $toDate){
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
        $iterator = 0;
        $reservationTable[] = array();
        while($reservationLine = mysql_fetch_assoc($reservationResult)) {
            $reservationTable[$iterator] = $reservationLine['ID_gabinetu'];
            $iterator = $iterator + 1;
        }
        sort($reservationTable);
        foreach($reservationTable as $key => $val){
            $checkForReservationQuery = "SELECT dzien_tyg, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE ID_gabinetu='" . $val . "'";
            $checkForReservationResult = mysql_query($checkForReservationQuery);
            $numberOfLine = mysql_num_rows($checkForReservationResult);
            if($numberOfLine){
                while($line = mysql_fetch_assoc($checkForReservationResult)) {
                    // TODO sprawdzenie specjalizacji lekarza i umo¿liwienie najmu gabinetów tylko z jego specjalizacj±
                    if (($line['od_godziny'] <= $fromTime) && ($line['do_godziny'] >= $toTime) && ($line['od_dnia'] <= $sinceDate) && ($line['do_dnia'] >= $toDate)){
                        // Gabinet pasuje do gabinetu w bazie danych - nie mo¿e byæ wy¶wietlony ten termin - IGNORUJEMY GO
                    }
                    else{
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
                }
            }
            else{
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
        echo "</table><br></fieldset>";
    }
    else{
        echo "Brak gabinetów w bazie danych";
    }
}
?>
