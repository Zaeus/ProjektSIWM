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
                mysql_query($kwerenda_dodania) or die('B³±d zapytania dodania');
                echo "<i>Dodano budynek: " . $_POST['dodane_miasto'] . " " . $_POST['dodana_ulica'] . " " . $_POST['dodany_numer'] . "</i>";
            }
            elseif(isset($_POST['Kod_pocztowy']) || isset($_POST['Miasto']) || isset($_POST['Ulica']) || isset($_POST['Numer'])){
                // TODO kwerenda edycji budynków
                echo "Edycja";
            }
            elseif(isset($_POST['usun']) && ($_POST['usun'] != 1)){
                // TODO kwerenda usuniêcia budynków
                echo "Usuniêcie";
            }
            // TODO kwerenda edycji gabinetów
            // TODO kwerenda dodania gabinetów
            // TODO kwerenda usuniêcia gabinetów
            // TODO tabela gabinetów
            // TODO kontrakt na gabinet (pó³ roku lub rok od dnia przed³u¿enia, plu¶ wy¶wietlenie tej daty)

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

            $kwerenda = "SELECT ID_budynku, Miasto, Ulica, Numer, Kod_pocztowy FROM budynki WHERE 1";
            $wynik = mysql_query($kwerenda) or die('B³±d zapytania');
            if($wynik) {
                ?>
                <table align="center" cellpadding="5" border="1">
                    <tr>
                        <td style="text-align: center;">Budynek o ID_Budynku:</td>
                        <td style="text-align: center;">Dane budynków znajduj±cych siê w bazie danych:</td>
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
                        $form_bud_usun .= "<button name=\"usun\" type=\"submit\" value=\"" . $wiersz['ID_budynku'] . "\">Usuñ rekord</button>";
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
			echo "Nie posiadasz uprawnieñ admina";
		}
	}
	else {
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("stopka.php");
?>