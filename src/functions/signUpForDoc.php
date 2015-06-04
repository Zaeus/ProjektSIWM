<?php
include("GenerateDate.php");
function SignUpForDoc($officeSpecialization)
{
    // TODO tabele z wygenerowanymi parametrami gabinet�w po pobraniu info o szukanej spracjalno�ci gabinetu (ew. mie�cie)
    echo "<br><fieldset><legend>Dost�pne gabinety o specjalizacji: " . $officeSpecialization . "</legend>";
    $officeSpecQuery = "SELECT zajetosc.ID_gabinetu, zajetosc.ID_nazwiska_Lek, zajetosc.dzien_tyg, zajetosc.od_dnia, zajetosc.do_dnia, zajetosc.od_godziny, zajetosc.do_godziny, budynki.miasto FROM gabinety ";
    $officeSpecQuery .= "INNER JOIN zajetosc ON gabinety.ID_gabinetu = zajetosc.ID_gabinetu ";
    $officeSpecQuery .= "INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
    $officeSpecQuery .= "WHERE gabinety.specjalnosc='" . $officeSpecialization . "'";
    $officeSpecResult = mysql_query($officeSpecQuery) or die('B��d zapytania o gabinety o podanej specjalizacji');
    $howMuchLines = mysql_num_rows($officeSpecResult);
    if($howMuchLines > 0) {
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Dane lekarza</td>";
        echo "<td>Miasto</td>";
        echo "<td>Dzie� tygodnia</td>";
        echo "<td>Dost�pny od</td>";
        echo "<td>Dost�pny do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Opcje</td>";
        while($officeSpecLine = mysql_fetch_assoc($officeSpecResult)) {
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
            $timeVisitQuery = "SELECT godzina FROM wizyty WHERE ID_gabinetu='" . $officeSpecLine['ID_gabinetu'] . "'";
            $timeVisitResult = mysql_query($timeVisitQuery) or die('B��d zapytania o nazwisko lekarza');
            $iterator = 0;
            while($timeVisitLine = mysql_fetch_assoc($timeVisitResult)){
                // Sprawdzenie czy godzina mie�cie si� w pracy gabinetu
                if(($officeSpecLine['od_godziny'] <= $timeVisitLine['godzina']) && ($officeSpecLine['do_godziny'] >= $timeVisitLine['godzina'])) {
                    $occupiedHours[$iterator] = date_create($timeVisitLine['godzina']);
                    $iterator = $iterator + 1;
                }
            }
            echo "<td><form action = \"zapis.php\" method=\"POST\"> ";
            // TODO selektor ma usuni�te godziny z zaklepanych godzin w danym dniu!! �eby zaklepanie w jednym dniu nie powodowa�o zablokowania danej godziny na wszystkie dni
            // TODO niewy�wietla� gabinet�w kt�rych data dost�pu ju� min�a
            // TODO data powinna si� pokrywa� z dniem tygodnia i uniemo�liwia� zaklepanie daty nie b�d�cej dniem tygodnia pracy gabinetu
            echo "<select name=\"godzinaRezerwacji\">";
            $start = date_create($officeSpecLine['od_godziny']);
            $stop = date_create($officeSpecLine['do_godziny']);
            $stop = date_modify($stop, '-30 minutes');
            if(isset($occupiedHours)) {
                // Je�eli w bazie danych s� godziny zaj�tych wizyt to wchodzimy w tego if'a
                $temp = clone($occupiedHours[0]);
                generateDate($start, date_modify($temp, '-30 minutes'));
                for ($i = 0; $i < count($occupiedHours); $i++) {
                    $temp = clone($occupiedHours[$i]);
                    if(isset($occupiedHours[$i+1])) {
                        echo "tutaj";
                        $temp2 = clone($occupiedHours[$i+1]);
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
            echo "<input type=\"date\" name=\"regDate\" value=\"" . date_format(new DateTime(), 'Y-m-d') . "\"> ";
            echo "<input type=\"hidden\" name=\"officeID\" value=\"" . $officeSpecLine['ID_gabinetu'] . "\">";
            echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
            echo "</tr>";
            unset($temp, $temp2, $start, $stop, $occupiedHours);
        }
        echo "</table>";
    } else {
        echo "Brak gabinet�w o podanej specjalizacji";
    }
    echo "</fieldset>";
}
?>
