<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] == "admin") {
            echo "<b>Posiadasz uprawnienia admina</b><br><br>";

            if(isset($_POST['dodane_miasto']) && isset($_POST['dodana_ulica']) && isset($_POST['dodany_numer']) && isset($_POST['dodany_kod_pocztowy'])){
                $kwerenda_dodania_bud = "INSERT INTO budynki (Miasto,Ulica,Numer,Kod_pocztowy) VALUES ";
                $kwerenda_dodania_bud .= "(";
                $kwerenda_dodania_bud .= "'" . $_POST['dodane_miasto'] . "'" . ",";
                $kwerenda_dodania_bud .= "'" . $_POST['dodana_ulica'] . "'" . ",";
                $kwerenda_dodania_bud .= "'" . $_POST['dodany_numer'] . "'" . ",";
                $kwerenda_dodania_bud .= "'" . $_POST['dodany_kod_pocztowy'] . "'";
                $kwerenda_dodania_bud .= ")";
                mysql_query($kwerenda_dodania_bud) or die('B��d zapytania dodania');
                echo "<i>Dodano budynek: " . $_POST['dodane_miasto'] . " " . $_POST['dodana_ulica'] . " " . $_POST['dodany_numer'] . "</i>";
            }
            elseif(isset($_POST['']) && isset($_POST['']) && isset($_POST['']) && isset($_POST[''])){
                // TODO kwerenda dodania gabinet�w
                // TODO je�eli jest zaznaczony radio button to ignorujemy dat� wpisan� w pole o dodajemy tyle ile wynika z radio
                $kwerenda_dodania_gab = "INSERT INTO gabinety (ID_budynku,specjalnosc,kontrakt_od,kontrakt_do) VALUES ";
                $kwerenda_dodania_gab .= "(";
                $kwerenda_dodania_gab .= "'" . $_POST['dodane_miasto'] . "'" . ",";
                $kwerenda_dodania_gab .= "'" . $_POST['dodana_ulica'] . "'" . ",";
                $kwerenda_dodania_gab .= "'" . $_POST['dodany_numer'] . "'" . ",";
                $kwerenda_dodania_gab .= "'" . $_POST['dodany_kod_pocztowy'] . "'";
                $kwerenda_dodania_gab .= ")";
                mysql_query($kwerenda_dodania_gab) or die('B��d zapytania dodania');
                echo "<i>Dodano gabinet w budynku o ID: " . $_POST[''] . " " . $_POST[''] . " " . $_POST[''] . "</i>";
            }
            elseif(isset($_POST['nowe_Miasto']) || isset($_POST['nowa_Ulica']) || isset($_POST['nowy_Numer']) || isset($_POST['nowy_Kod_pocztowy'])){
                $kwerenda_edycji_bud = "UPDATE budynki SET Miasto='" . $_POST['nowe_Miasto'] . "', ";
                $kwerenda_edycji_bud .= "Ulica='" . $_POST['nowa_Ulica'] . "', ";
                $kwerenda_edycji_bud .= "Numer='" . $_POST['nowy_Numer'] . "', ";
                $kwerenda_edycji_bud .= "Kod_pocztowy='" . $_POST['nowy_Kod_pocztowy'] . "' ";
                $kwerenda_edycji_bud .= "WHERE ID_budynku='" . $_POST['nowe_ID_budynku'] . "'";
                mysql_query($kwerenda_edycji_bud) or die('B��d zapytania edycji');
                echo "<i>Edytowano rekord budynku o ID: " . $_POST['nowe_ID_budynku'] . " w mie�cie: " . $_POST['nowe_Miasto'] . "</i>";
            }
            elseif(isset($_POST['nowy_kontrakt_od']) || isset($_POST['nowy_kontrakt_do']) || isset($_POST['nowa_specjalnosc'])){
                $kwerenda_edycji_gab = "UPDATE gabinety SET ID_gabinetu='" . $_POST['nowe_ID_gabinetu'] . "', ";
                $kwerenda_edycji_gab .= "kontrakt_od='" . $_POST['nowy_kontrakt_od'] . "', ";
                $kwerenda_edycji_gab .= "kontrakt_do='" . $_POST['nowy_kontrakt_do'] . "', ";
                $kwerenda_edycji_gab .= "specjalnosc='" . $_POST['nowa_specjalnosc'] . "' ";
                $kwerenda_edycji_gab .= "WHERE ID_gabinetu='" . $_POST['nowe_ID_gabinetu'] . "'";
                mysql_query($kwerenda_edycji_gab) or die('B��d zapytania edycji');
                echo "<i>Edytowano rekord gabinetu o ID: " . $_POST['nowe_ID_gabinetu'] . " o specjalno�ci: " . $_POST['nowa_specjalnosc'] . "</i>";
            }
            elseif(isset($_POST['usun_bud']) && ($_POST['usun_bud'] != 1)){
                // TODO kwerenda usuni�cia budynk�w
                // TODO ostrze�enie przed usuwaniem - zbyt powi�zane dane spowoduj� usuni�cie du�ej cz�ci danych
                // TODO usuni�cie bydunk�w powinno wywo�a� kaskadowe usuni�cie wszystkich gabinet�w w nim zawartych, oraz wszystkich wizyt i zaj�� gabinet�w
                echo "Usuni�cie budynku";
            }
            elseif(isset($_POST['usun_gab']) && ($_POST['usun_gab'] != 1)){
                // TODO kwerenda usuni�cia gabinet�w
                // TODO usuni�cie gabinetu powinno usun�� wszystkie klepni�te w nim wizyty i zaj�� gabinet�w przez lekarzy
                echo "Usuni�cie budynku";
            }

            echo "<br><br>";
            $forma_dodania_bud = "<fieldset><legend>Dodaj budynek:</legend><form action = \"edit-bud-gab.php\" method=\"POST\">";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodane_miasto\" placeholder=\"Miasto\"><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodana_ulica\" placeholder=\"Ulica\"><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodany_numer\" placeholder=\"Numer budynku\" maxlength='5'><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodany_kod_pocztowy\" placeholder=\"Kod pocztowy 00-000\" pattern=\"[0-9]{2}-[0-9]{3}\"><br>";
            $forma_dodania_bud .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $forma_dodania_bud .= "<input type=\"reset\" value=\"Resetuj dane\" />";
            $forma_dodania_bud .= "</form></fieldset><br>";
            echo $forma_dodania_bud;

            $kwerenda_dodania_gab = "SELECT ID_budynku FROM budynki WHERE 1";
            $wynik_dodania_gab = mysql_query($kwerenda_dodania_gab) or die('B��d zapytania');

            $forma_dodania_gab = "<fieldset><legend>Dodaj gabinet:</legend><form action = \"edit-bud-gab.php\" method=\"POST\">";
            $forma_dodania_gab .= "Budynek: <select name=\"id_budynku\">";
            if($wynik_dodania_gab) {
                while($wiersz_dodania_gab = mysql_fetch_assoc($wynik_dodania_gab)) {
                    $forma_dodania_gab .= "<option value=\"". $wiersz_dodania_gab['ID_budynku'] ."\">" . $wiersz_dodania_gab['ID_budynku'] ."</option>";
                }
            }
            $forma_dodania_gab .= "</select><br>";
            $forma_dodania_gab .= "Specjalizacja gabinetu: <select name=\"specjalizacja_gabinetu\">";
            $forma_dodania_gab .= "<option value=\"USG\">USG</option>";
            $forma_dodania_gab .= "<option value=\"Interna\" >Interna</option>";
            $forma_dodania_gab .= "<option value=\"Ginekolog\" >Ginekologia</option>";
            $forma_dodania_gab .= "</select><br>";
            $forma_dodania_gab .= "Kontrakt od: <input type=\"date\" name=\"data_kontraktu_od\" placeholder=\"Data rozpocz�cia kontraktu\" value=\"" . date_format(new DateTime(), 'Y-m-d') . "\"><br>";
            $forma_dodania_gab .= "Kontrakt do: <input type=\"date\" name=\"data_kontraktu_do1\" placeholder=\"Data zako�czenia kontraktu\" value=\"" . date_format(date_modify(new DateTime(), '+7 day'), 'Y-m-d') . "\"><br>";
            $forma_dodania_gab .= " Lub przez: <input type=\"radio\" name=\"data_kontraktu_do2\" value=\"" . date_format(date_modify(new DateTime(), '+183 day'), 'Y-m-d') . "\">P� roku";
            $forma_dodania_gab .= " lub: <input type=\"radio\" name=\"data_kontraktu_do2\" value=\"" . date_format(date_modify(new DateTime(), '+365 day'), 'Y-m-d') . "\">Rok<br>";
            $forma_dodania_gab .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $forma_dodania_gab .= "<input type=\"reset\" value=\"Resetuj dane\" />";
            $forma_dodania_gab .= "</form></fieldset><br>";
            echo $forma_dodania_gab;

            $kwerenda_bud = "SELECT ID_budynku, Miasto, Ulica, Numer, Kod_pocztowy FROM budynki WHERE 1";
            $wynik_bud = mysql_query($kwerenda_bud) or die('B��d zapytania');
            $kwerenda_gab = "SELECT ID_gabinetu, ID_budynku, specjalnosc, kontrakt_od, kontrakt_do FROM gabinety WHERE 1";
            $wynik_gab = mysql_query($kwerenda_gab) or die('B��d zapytania');
            ?>
            <fieldset><legend>Istniej�ce gabinety i budynki:</legend><table align="center" cellpadding="5" border="1">
                <tr>
                    <td style="text-align: center">Budynek o ID_Budynku:</td>
                    <td style="text-align: center">Dane budynk�w znajduj�cych si� w bazie danych:</td>
                </tr>
            <?
            if($wynik_bud) {
                while($wiersz_bud = mysql_fetch_assoc($wynik_bud)) {
                    ?>
                    <tr>
                    <?
                    echo "<td>" . $wiersz_bud['ID_budynku'] . "</td>";
                    $form_bud = "<td style=\"text-align: right\"><form action = \"edit-bud-gab.php\" method=\"POST\"> ";
                    $form_bud .= "<input type=\"hidden\" name=\"nowe_ID_budynku\" value=\"" . $wiersz_bud['ID_budynku'] . "\">";
                    $form_bud .= "Miasto:<input type=\"text\" name=\"nowe_Miasto\" value=\"" . $wiersz_bud['Miasto'] . "\">";
                    $form_bud .= " Ulica:<input type=\"text\" name=\"nowa_Ulica\" value=\"" . $wiersz_bud['Ulica'] . "\">";
                    $form_bud .= " Numer:<input type=\"text\" name=\"nowy_Numer\" value=\"" . $wiersz_bud['Numer'] . "\">";
                    $form_bud .= " Kod pocztowy:<input type=\"text\" name=\"nowy_Kod_pocztowy\" pattern=\"[0-9]{2}-[0-9]{3}\" value=\"" . $wiersz_bud['Kod_pocztowy'] . "\">";
                    $form_bud .= "<input type=\"submit\" value=\"Edytuj rekord\" >";
                    $form_bud .= "</form></td>";
                    echo $form_bud;

                    $form_bud_usun = "<td><form action=\"edit-bud-gab.php\" method=\"POST\">";
                    $form_bud_usun .= "<button name=\"usun_bud\" type=\"submit\" value=\"" . $wiersz_bud['ID_budynku'] . "\">Usu� rekord</button>";
                    $form_bud_usun .= "</form></td>";
                    echo $form_bud_usun;
                    ?>
                    </tr>
                    <?
                }

            }
            ?>
                <tr>
                    <td style="text-align: center">Gabinet o ID_Gabinetu:</td>
                    <td style="text-align: center">Dane gabinet�w znajduj�cych si� w bazie danych:</td>
                </tr>
            <?
            if($wynik_gab)
            {
                while($wiersz_gab = mysql_fetch_assoc($wynik_gab)) {
                    echo "<td>" . $wiersz_gab['ID_gabinetu'] . "</td>";
                    $form_gab = "<td style=\"text-align: right\"><form action = \"edit-bud-gab.php\" method=\"POST\"> ";
                    $form_gab .= "<input type=\"hidden\" name=\"nowe_ID_gabinetu\" value=\"" . $wiersz_gab['ID_gabinetu'] . "\">";
                    $form_gab .= "Budynek: <input type=\"text\" name=\"nowe_ID_budynku\" value=\"" . $wiersz_gab['ID_budynku'] . "\" disabled>";
                    // TODO lista rozwijana specjalno�ci gabinetu z warto�ci� domy�ln� ustawion� na pobrany z bazy
                    $form_gab .= " Specjalno�� gabinetu: <select name=\"nowa_specjalnosc\">";
                    if($wiersz_gab['specjalnosc'] == "USG"){
                        $form_gab .= "<option value=\"USG\" selected=\"selected\">USG</option>";
                        $form_gab .= "<option value=\"Interna\" >Interna</option>";
                        $form_gab .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($wiersz_gab['specjalnosc'] == "Interna"){
                        $form_gab .= "<option value=\"USG\" >USG</option>";
                        $form_gab .= "<option value=\"Interna\" selected=\"selected\">Interna</option>";
                        $form_gab .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($wiersz_gab['specjalnosc'] == "Ginekolog"){
                        $form_gab .= "<option value=\"USG\">USG</option>";
                        $form_gab .= "<option value=\"Interna\" >Interna</option>";
                        $form_gab .= "<option value=\"Ginekolog\" selected=\"selected\">Ginekologia</option>";
                    }
                    $form_gab .= "</select>";
                    $form_gab .= " Kotrakt gabinetu od<input type=\"date\" name=\"nowy_kontrakt_od\" value=\"" . $wiersz_gab['kontrakt_od'] . "\">";
                    $form_gab .= " do<input type=\"date\" name=\"nowy_kontrakt_do\" value=\"" . $wiersz_gab['kontrakt_do'] . "\">";
                    $form_gab .= "<input type=\"submit\" value=\"Edytuj rekord\" >";
                    $form_gab .= "</form></td>";
                    echo $form_gab;

                    $form_gab_usun = "<td><form action=\"edit-bud-gab.php\" method=\"POST\">";
                    $form_gab_usun .= "<button name=\"usun_gab\" type=\"submit\" value=\"" . $wiersz_gab['ID_gabinetu'] . "\">Usu� rekord</button>";
                    $form_gab_usun .= "</form></td>";
                    echo $form_gab_usun;
                }
            }
            ?>
            </table></fieldset>
            <?
        }
        else {
			echo "Nie posiadasz uprawnie� admina";
		}
	}
	else {
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("stopka.php");
?>
