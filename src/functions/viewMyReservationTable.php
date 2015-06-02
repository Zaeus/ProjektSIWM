<?php
function ViewMyReservationTable($docEmail)
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
            echo "<td><form action = \"gabinet.php\" method=\"POST\"> ";
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
