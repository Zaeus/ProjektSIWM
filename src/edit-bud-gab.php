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
                $kwerenda_dodania = "INSERT INTO budynki (Miasto,Ulica,Numer,Kod_pocztowy) VALUES ";
                $kwerenda_dodania .= "(";
                $kwerenda_dodania .= "'" . $_POST['dodane_miasto'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodana_ulica'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodany_numer'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodany_kod_pocztowy'] . "'";
                $kwerenda_dodania .= ")";
                mysql_query($kwerenda_dodania) or die('B��d zapytania dodania');
                echo "<i>Dodano budynek: " . $_POST['dodane_miasto'] . " " . $_POST['dodana_ulica'] . " " . $_POST['dodany_numer'] . "</i>";
            }
            elseif(isset($_POST['Kod_pocztowy']) || isset($_POST['Miasto']) || isset($_POST['Ulica']) || isset($_POST['Numer'])){
                // TODO kwerenda edycji budynk�w
                echo "Edycja";
            }
            elseif(isset($_POST['usun']) && ($_POST['usun'] != 1)){
                // TODO kwerenda usuni�cia budynk�w
                // TODO ostrze�enie przed usuwaniem - zbyt powi�zane dane spowoduj� usuni�cie du�ej cz�ci danych
                // TODO usuni�cie bydunk�w powinno wywo�a� kaskadowe usuni�cie wszystkich gabinet�w w nim zawartych, oraz wszystkich wizyt i zaj�� gabinet�w
                echo "Usuni�cie";
            }
            // TODO kwerenda edycji gabinet�w
            // TODO kwerenda dodania gabinet�w
            // TODO kwerenda usuni�cia gabinet�w
            // TODO tabela gabinet�w
            // TODO kontrakt na gabinet (p� roku lub rok od dnia przed�u�enia, plu� wy�wietlenie tej daty)

            echo "<br><br>";
            $forma_dodania_bud = "<fieldset><legend>Dodaj budynek:</legend><form action = \"edit-bud-gab.php\" method=\"POST\">";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodane_miasto\" placeholder=\"Miasto\"><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodana_ulica\" placeholder=\"Ulica\"><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodany_numer\" placeholder=\"Numer budynku\" maxlength='5'><br>";
            $forma_dodania_bud .= "<input type=\"text\" name=\"dodany_kod_pocztowy\" placeholder=\"Kod pocztowy 00-000\" pattern=\"[0-9]{2}-[0-9]{3}\"><br>";
            $forma_dodania_bud .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $forma_dodania_bud .= "<input type=\"reset\" value=\"Resetuj dane\" />";
            $forma_dodania_bud .= "</form></fieldset><br><br>";
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
            $forma_dodania_gab .= "Kontrakt do: <input type=\"date\" name=\"data_kontraktu\" placeholder=\"Data zako�czenia kontraktu\">";
            // TODO value radio button zmieni� na dat� dzisiejsz� plus odpowiednia liczba dni - najpewniej przez date modification i strtodate
            $forma_dodania_gab .= " lub przez: <input type=\"radio\" name=\"data_kontraktu\" value=\"180\">P� roku";
            $forma_dodania_gab .= " lub: <input type=\"radio\" name=\"data_kontraktu\" value=\"365\">Rok<br>";
            $forma_dodania_gab .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $forma_dodania_gab .= "<input type=\"reset\" value=\"Resetuj dane\" />";
            $forma_dodania_gab .= "</form></fieldset><br><br>";
            echo $forma_dodania_gab;

            $kwerenda = "SELECT ID_budynku, Miasto, Ulica, Numer, Kod_pocztowy FROM budynki WHERE 1";
            $wynik = mysql_query($kwerenda) or die('B��d zapytania');
            if($wynik) {
                ?>
                <table align="center" cellpadding="5" border="1">
                    <tr>
                        <td style="text-align: center;">Budynek o ID_Budynku:</td>
                        <td style="text-align: center;">Dane budynk�w znajduj�cych si� w bazie danych:</td>
                    </tr>
                    <?
                    while($wiersz = mysql_fetch_assoc($wynik)) {
                        ?>
                        <tr>
                        <?
                        echo "<td>" . $wiersz['ID_budynku'] . "</td>";
                        $form_bud = "<td><form action = \"edit-bud-gab.php\" method=\"POST\"> ";
                        $form_bud .= "<input type=\"hidden\" name=\"ID_budynku\" value=\"" . $wiersz['ID_budynku'] . "\">";
                        $form_bud .= "Miasto:<input type=\"text\" name=\"Miasto\" value=\"" . $wiersz['Miasto'] . "\">";
                        $form_bud .= " Ulica:<input type=\"text\" name=\"Ulica\" value=\"" . $wiersz['Ulica'] . "\">";
                        $form_bud .= " Numer:<input type=\"text\" name=\"Numer\" value=\"" . $wiersz['Numer'] . "\">";
                        $form_bud .= " Kod pocztowy:<input type=\"text\" name=\"Kod_pocztowy\" pattern=\"[0-9]{2}-[0-9]{3}\" value=\"" . $wiersz['Kod_pocztowy'] . "\">";
                        $form_bud .= "<input type=\"submit\" value=\"Edytuj rekord\" >";
                        $form_bud .= "</form></td>";
                        echo $form_bud;

                        $form_bud_usun = "<td><form action=\"edit-bud-gab.php\" method=\"POST\">";
                        $form_bud_usun .= "<button name=\"usun\" type=\"submit\" value=\"" . $wiersz['ID_budynku'] . "\">Usu� rekord</button>";
                        $form_bud_usun .= "</form></td>";
                        echo $form_bud_usun;
                        ?>
                        </tr>
                        <?
                    }
                    ?>
                </table>
                <?
            }
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