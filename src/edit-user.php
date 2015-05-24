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

            // Update danych w bazie SQL
            if(isset($_POST['nowe_haslo']) || isset($_POST['nowy_email']) || isset($_POST['nowe_nazwisko'])){
                $kwerenda_edycji = "UPDATE nazwiska SET email='" . $_POST['nowy_email'] . "', ";
                $kwerenda_edycji .= "nazwisko='" . $_POST['nowe_nazwisko'] . "', ";
                $kwerenda_edycji .= "imie='" . $_POST['nowe_imie'] . "', ";
                $kwerenda_edycji .= "specjalizacja=";
                if($_POST['nowa_specjalizacja'] == ""){
                    $kwerenda_edycji .= "NULL ";
                }
                else {
                    $kwerenda_edycji .= "'" . $_POST['nowa_specjalizacja'] . "' ";
                }
                $kwerenda_edycji .= "WHERE id_nazwiska='" . $_POST['id_nazwiska'] . "'";
                mysql_query($kwerenda_edycji) or die('B³±d zapytania edycji');
                echo "<i>Edytowano rekord u¿ytkownika: " . $_POST['nowe_nazwisko'] . " " . $_POST['nowe_imie'] . "</i>";
            }
            elseif(isset($_POST['dodane_nazwisko']) && isset($_POST['dodane_imie']) && isset($_POST['dodane_haslo']) && isset($_POST['dodany_email']) && isset($_POST['dodane_uprawnienia'])){
                $kwerenda_dodania = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia,specjalizacja) VALUES ";
                $kwerenda_dodania .= "(";
                $kwerenda_dodania .= "'" . $_POST['dodany_email'] . "'" . ",";
                $kwerenda_dodania .= "'" . md5($_POST['dodane_haslo']) . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodane_nazwisko'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodane_imie'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['dodane_uprawnienia'] . "'"  . ",";
                if($_POST['dodana_specjalizacja'] == ""){
                    $kwerenda_dodania .= "NULL";
                }
                elseif(($_POST['dodane_uprawnienia'] == "pacjent") || ($_POST['dodane_uprawnienia'] == "admin")){
                    $kwerenda_dodania .= "NULL";
                }
                else {
                    $kwerenda_dodania .= "'" . $_POST['dodana_specjalizacja'] . "'";
                }
                $kwerenda_dodania .= ")";
                mysql_query($kwerenda_dodania) or die('B³±d zapytania dodania');
                echo "<i>Dodano u¿ytkownika: " . $_POST['dodane_nazwisko'] . " " . $_POST['dodane_imie'] . "</i>";
            }
            elseif(isset($_POST['usun']) && ($_POST['usun'] != 1) && ($_POST['usun'] != 17)){
                $kwerenda_usuniecia = "DELETE FROM nazwiska WHERE id_nazwiska=" . $_POST['usun'] . ";";
                mysql_query($kwerenda_usuniecia) or die('B³±d zapytania usuniêcia');
                echo "<i>Usuniêto u¿ytkownika o ID_Nazwiska: " . $_POST['usun'] . "</i>";
            }

            echo "<br><br>";
            $forma_dodania = "<br><fieldset><legend>Dodaj u¿ytkownika o podanych parametrach - pamiêtaj ¿e pacjent/admin nie posiadaj± specjalizacji!</legend><form action = \"edit-user.php\" method=\"POST\"> ";
            $forma_dodania .= "<input type=\"text\" name=\"dodane_nazwisko\" placeholder=\"Nazwisko\"><br>";
            $forma_dodania .= "<input type=\"text\" name=\"dodane_imie\" placeholder=\"Imiê\"><br>";
            $forma_dodania .= "<input type=\"password\" name=\"dodane_haslo\" placeholder=\"Has³o\"><br>";
            $forma_dodania .= "<input type=\"email\" name=\"dodany_email\" placeholder=\"Email\"><br>";
            $forma_dodania .= "<fieldset><legend>Specjalizacja:</legend><input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"Interna\"/><label for=\"Internista\">Internista</label><br>";
            $forma_dodania .= "<input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"Ginekolog\"/><label for=\"Ginekolog\">Ginekologia</label><br>";
            $forma_dodania .= "<input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"USG\"/><label for=\"USG\">USG</label></fieldset><br>";
            $forma_dodania .= "<fieldset><legend>Status:</legend><input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"admin\"/><label for=\"admin\">Administrator</label><br>";
            $forma_dodania .= "<input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"lekarz\"/><label for=\"lekarz\">Lekarz</label><br>";
            $forma_dodania .= "<input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"pacjent\"/><label for=\"pacjent\">Pacjent</label></fieldset><br>";
            $forma_dodania .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $forma_dodania .= "<input type=\"reset\" value=\"Resetuj dane\" /></fieldset>";
            $forma_dodania .= "</form><br>";
            echo $forma_dodania;

            $kwerenda = "SELECT id_nazwiska, email, haslo, imie, nazwisko, specjalizacja, uprawnienia FROM nazwiska WHERE 1";
            $wynik = mysql_query($kwerenda) or die('B³±d zapytania');

            if($wynik){
                ?>
                <table align="center" cellpadding="5" border="1">
                    <tr>
                        <td style="text-align: center;">Rekord o ID_Nazwiska:</td>
                        <td style="text-align: center;">Dane u¿ytkowników znajduj±cych siê w bazie danych:</td>
                    </tr>
                <?
                while($wiersz = mysql_fetch_assoc($wynik)){
                    ?>
                    <tr>
                    <?
                    echo "<td>" . $wiersz['id_nazwiska'] . "</td>";
                    $forma_edycji = "<td><form action = \"edit-user.php\" method=\"POST\"> ";
                    $forma_edycji .= "<input type=\"hidden\" name=\"id_nazwiska\" value=\"" . $wiersz['id_nazwiska'] . "\">";
                    $forma_edycji .= "<input type=\"text\" name=\"nowe_nazwisko\" value=\"" . $wiersz['nazwisko'] . "\">";
                    $forma_edycji .= "<input type=\"text\" name=\"nowe_imie\" value=\"" . $wiersz['imie'] . "\">";
                    $forma_edycji .= "<input type=\"text\" name=\"nowe_haslo\" value=\"" . $wiersz['haslo'] . "\" disabled>";
                    $forma_edycji .= "<input type=\"email\" name=\"nowy_email\" value=\"" . $wiersz['email'] . "\">";
                    $forma_edycji .= "<select name=\"nowa_specjalizacja\"";
                    if(($wiersz['uprawnienia'] == "pacjent") || ($wiersz['uprawnienia'] == "admin")){
                        $forma_edycji .= " disabled";
                    }
                    $forma_edycji .= ">";
                    if($wiersz['specjalizacja'] == "USG"){
                        $forma_edycji .= "<option value=\"USG\" selected=\"selected\">USG</option>";
                        $forma_edycji .= "<option value=\"Interna\" >Interna</option>";
                        $forma_edycji .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($wiersz['specjalizacja'] == "Interna"){
                        $forma_edycji .= "<option value=\"USG\" >USG</option>";
                        $forma_edycji .= "<option value=\"Interna\" selected=\"selected\">Interna</option>";
                        $forma_edycji .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($wiersz['specjalizacja'] == "Ginekolog"){
                        $forma_edycji .= "<option value=\"USG\" >USG</option>";
                        $forma_edycji .= "<option value=\"Interna\" >Interna</option>";
                        $forma_edycji .= "<option value=\"Ginekolog\" selected=\"selected\">Ginekologia</option>";
                    }
                    else{
                        $forma_edycji .= "<option value=\"NULL\" ></option>";
                        $forma_edycji .= "<option value=\"USG\" >USG</option>";
                        $forma_edycji .= "<option value=\"Interna\" >Interna</option>";
                        $forma_edycji .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    $forma_edycji .= "</select>";
                    $forma_edycji .= "<input type=\"text\" name=\"uprawnienia\" value=\"" . $wiersz['uprawnienia'] . " \"disabled>";
                    $forma_edycji .= "<input type=\"submit\" value=\"Edytuj rekord\" >";
                    $forma_edycji .= "</form></td>";
                    echo $forma_edycji;

                    $form_usun = "<td><form action=\"edit-user.php\" method=\"POST\">";
                    $form_usun .= "<button name=\"usun\" type=\"submit\" value=\"" . $wiersz['id_nazwiska'] . "\">Usuñ rekord</button>";
                    $form_usun .= "</form></td>";
                    echo $form_usun;
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