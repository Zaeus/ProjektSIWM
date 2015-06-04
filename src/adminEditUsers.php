<?
	session_start();
?>
<?
    include("includes/header.php");
    include("includes/SQLConnection.php");
    include("includes/logQuery.php");
    include("functions/LoginPowerFunctions.php");
?>
<?
	if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
		if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
			echo "<b>Posiadasz uprawnienia admina</b><br><br>";

            // Update danych w bazie SQL
            if(isset($_POST['nowe_haslo']) || isset($_POST['nowy_email']) || isset($_POST['nowe_nazwisko'])){
                $editQuery = "UPDATE nazwiska SET email='" . $_POST['nowy_email'] . "', ";
                $editQuery .= "nazwisko='" . $_POST['nowe_nazwisko'] . "', ";
                $editQuery .= "imie='" . $_POST['nowe_imie'] . "', ";
                $editQuery .= "specjalizacja=";
                if($_POST['nowa_specjalizacja'] == ""){
                    $editQuery .= "NULL ";
                }
                else {
                    $editQuery .= "'" . $_POST['nowa_specjalizacja'] . "' ";
                }
                $editQuery .= "WHERE id_nazwiska='" . $_POST['id_nazwiska'] . "'";
                mysql_query($editQuery) or die('B³±d zapytania edycji');
                echo "<i>Edytowano rekord u¿ytkownika: " . $_POST['nowe_nazwisko'] . " " . $_POST['nowe_imie'] . "</i>";
            }
            elseif(isset($_POST['dodane_nazwisko']) && isset($_POST['dodane_imie']) && isset($_POST['dodane_haslo']) && isset($_POST['dodany_email']) && isset($_POST['dodane_uprawnienia'])){
                $addQuery = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia,specjalizacja) VALUES ";
                $addQuery .= "(";
                $addQuery .= "'" . $_POST['dodany_email'] . "'" . ",";
                $addQuery .= "'" . md5($_POST['dodane_haslo']) . "'" . ",";
                $addQuery .= "'" . $_POST['dodane_nazwisko'] . "'" . ",";
                $addQuery .= "'" . $_POST['dodane_imie'] . "'" . ",";
                $addQuery .= "'" . $_POST['dodane_uprawnienia'] . "'"  . ",";
                if($_POST['dodana_specjalizacja'] == ""){
                    $addQuery .= "NULL";
                }
                elseif(($_POST['dodane_uprawnienia'] == "pacjent") || ($_POST['dodane_uprawnienia'] == "admin")){
                    $addQuery .= "NULL";
                }
                else {
                    $addQuery .= "'" . $_POST['dodana_specjalizacja'] . "'";
                }
                $addQuery .= ")";
                mysql_query($addQuery) or die('B³±d zapytania dodania');
                echo "<i>Dodano u¿ytkownika: " . $_POST['dodane_nazwisko'] . " " . $_POST['dodane_imie'] . "</i>";
            }
            elseif(isset($_POST['usun']) && ($_POST['usun'] != 1) && ($_POST['usun'] != 17)){
                $removeQuery = "DELETE FROM nazwiska WHERE id_nazwiska=" . $_POST['usun'] . ";";
                mysql_query($removeQuery) or die('B³±d zapytania usuniêcia');
                echo "<i>Usuniêto u¿ytkownika o ID_Nazwiska: " . $_POST['usun'] . "</i>";
            }

            echo "<br><br>";
            $addForm = "<br><fieldset><legend>Dodaj u¿ytkownika o podanych parametrach:</legend><form action = \"adminEditUsers.php\" method=\"POST\"> ";
            $addForm .= "<input type=\"text\" name=\"dodane_nazwisko\" placeholder=\"Nazwisko\"><br>";
            $addForm .= "<input type=\"text\" name=\"dodane_imie\" placeholder=\"Imiê\"><br>";
            $addForm .= "<input type=\"password\" name=\"dodane_haslo\" placeholder=\"Has³o\"><br>";
            $addForm .= "<input type=\"email\" name=\"dodany_email\" placeholder=\"Email\"><br>";
            $addForm .= "<fieldset><legend>Specjalizacja (pamiêtaj ¿e pacjent/admin nie posiadaj± specjalizacji!):</legend><input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"Interna\"/><label for=\"Internista\">Internista</label><br>";
            $addForm .= "<input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"Ginekolog\"/><label for=\"Ginekolog\">Ginekologia</label><br>";
            $addForm .= "<input type=\"radio\" name=\"dodana_specjalizacja\" id=\"dodana_specjalizacja\" value=\"USG\"/><label for=\"USG\">USG</label></fieldset><br>";
            $addForm .= "<fieldset><legend>Status:</legend><input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"admin\"/><label for=\"admin\">Administrator</label><br>";
            $addForm .= "<input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"lekarz\"/><label for=\"lekarz\">Lekarz</label><br>";
            $addForm .= "<input type=\"radio\" name=\"dodane_uprawnienia\" id=\"dodane_uprawnienia\" value=\"pacjent\"/><label for=\"pacjent\">Pacjent</label></fieldset><br>";
            $addForm .= "<input type=\"submit\" value=\"Dodaj rekord\" >";
            $addForm .= "<input type=\"reset\" value=\"Resetuj dane\" /></fieldset>";
            $addForm .= "</form><br>";
            echo $addForm;

            $query = "SELECT id_nazwiska, email, haslo, imie, nazwisko, specjalizacja, uprawnienia FROM nazwiska WHERE 1";
            $result = mysql_query($query) or die('B³±d zapytania');

            if($result){
                ?>
                <table align="center" cellpadding="5" border="1">
                    <tr>
                        <td style="text-align: center;">Rekord o ID_Nazwiska:</td>
                        <td style="text-align: center;">Dane u¿ytkowników znajduj±cych siê w bazie danych:</td>
                    </tr>
                <?
                while($line = mysql_fetch_assoc($result)){
                    ?>
                    <tr>
                    <?
                    echo "<td>" . $line['id_nazwiska'] . "</td>";
                    $editForm = "<td><form action = \"adminEditUsers.php\" method=\"POST\"> ";
                    $editForm .= "<input type=\"hidden\" name=\"id_nazwiska\" value=\"" . $line['id_nazwiska'] . "\">";
                    $editForm .= "<input type=\"text\" name=\"nowe_nazwisko\" value=\"" . $line['nazwisko'] . "\">";
                    $editForm .= "<input type=\"text\" name=\"nowe_imie\" value=\"" . $line['imie'] . "\">";
                    $editForm .= "<input type=\"text\" name=\"nowe_haslo\" value=\"" . $line['haslo'] . "\" disabled>";
                    $editForm .= "<input type=\"email\" name=\"nowy_email\" value=\"" . $line['email'] . "\">";
                    $editForm .= "<select name=\"nowa_specjalizacja\"";
                    if(($line['uprawnienia'] == "pacjent") || ($line['uprawnienia'] == "admin")){
                        $editForm .= " disabled";
                    }
                    $editForm .= ">";
                    if($line['specjalizacja'] == "USG"){
                        $editForm .= "<option value=\"USG\" selected=\"selected\">USG</option>";
                        $editForm .= "<option value=\"Interna\" >Interna</option>";
                        $editForm .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($line['specjalizacja'] == "Interna"){
                        $editForm .= "<option value=\"USG\" >USG</option>";
                        $editForm .= "<option value=\"Interna\" selected=\"selected\">Interna</option>";
                        $editForm .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    elseif($line['specjalizacja'] == "Ginekolog"){
                        $editForm .= "<option value=\"USG\" >USG</option>";
                        $editForm .= "<option value=\"Interna\" >Interna</option>";
                        $editForm .= "<option value=\"Ginekolog\" selected=\"selected\">Ginekologia</option>";
                    }
                    else{
                        $editForm .= "<option value=\"NULL\" ></option>";
                        $editForm .= "<option value=\"USG\" >USG</option>";
                        $editForm .= "<option value=\"Interna\" >Interna</option>";
                        $editForm .= "<option value=\"Ginekolog\" >Ginekologia</option>";
                    }
                    $editForm .= "</select>";
                    $editForm .= "<input type=\"text\" name=\"uprawnienia\" value=\"" . $line['uprawnienia'] . " \"disabled>";
                    $editForm .= "<input type=\"submit\" value=\"Edytuj rekord\" >";
                    $editForm .= "</form></td>";
                    echo $editForm;

                    $removeForm = "<td><form action=\"adminEditUsers.php\" method=\"POST\">";
                    $removeForm .= "<button name=\"usun\" type=\"submit\" value=\"" . $line['id_nazwiska'] . "\">Usuñ rekord</button>";
                    $removeForm .= "</form></td>";
                    echo $removeForm;
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
include("includes/footer.php");
?>
