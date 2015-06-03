<?php
include("GenerateDate.php");

function SignUpForDoc($officeSpecialization)
{
    // TODO tabele z wygenerowanymi parametrami gabinetów po pobraniu info o szukanej spracjalno¶ci gabinetu (ew. mie¶cie)
    echo "<br><fieldset><legend>Dostêpne gabinety:</legend>";
    $officeSpecQuery = "SELECT zajetosc.ID_nazwiska_Lek, zajetosc.dzien_tyg, zajetosc.od_dnia, zajetosc.do_dnia, zajetosc.od_godziny, zajetosc.do_godziny, budynki.miasto FROM gabinety ";
    $officeSpecQuery .= "INNER JOIN zajetosc ON gabinety.ID_gabinetu = zajetosc.ID_gabinetu ";
    $officeSpecQuery .= "INNER JOIN budynki ON gabinety.ID_budynku = budynki.ID_budynku ";
    $officeSpecQuery .= "WHERE gabinety.specjalnosc='" . $officeSpecialization . "'";
    $officeSpecResult = mysql_query($officeSpecQuery) or die('B³±d zapytania o gabinety o podanej specjalizacji');
    $howMuchLines = mysql_num_rows($officeSpecResult);
    if($howMuchLines > 0) {
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<td>Dane lekarza</td>";
        echo "<td>Miasto</td>";
        echo "<td>Dzieñ tygodnia</td>";
        echo "<td>Dostêpny od</td>";
        echo "<td>Dostêpny do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Opcje</td>";
        while($officeSpecLine = mysql_fetch_assoc($officeSpecResult)) {
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
            echo "<td><form action = \"zapis.php\" method=\"POST\"> ";
            // TODO tabela powinna mieæ selektor z mo¿liwymi godzinami do zaklepania (ew. pole ile ma trwaæ wizyta - wed³ug mnie przyjmujemy 30minut na wizytê)
            // TODO selektor ma usuniête godziny z zaklepanych godzin
            // TODO Poza selektoram powinien byæ wybór daty z zakresu najmu gabinetu
            // TODO przycisk zarezerwowania wizyty
            echo date_format($officeSpecLine['do_godziny'],'H:i');
            echo "<select name=\"godzinaRezerwacji\">";
            generateDate(date_create($officeSpecLine['od_godziny']), date_modify(date_create($officeSpecLine['do_godziny']), '-30 minutes'));
            echo "</select>";
            echo "<input type=\"submit\" value=\"Rezerwuj\" ></form></td>";
            echo "</tr>";
            echo "</tr>";
        }
        echo "</table>";
    } else {
        echo "Brak gabinetów o podanej specjalizacji";
    }
    echo "</fieldset><br>";
}
?>
