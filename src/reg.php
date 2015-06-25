<?
	session_start();
?>
<?
    include("includes/header.php");
    include("includes/SQLConnection.php");
    include("includes/Parameters.php");
    include("functions/ResourcesFunctions.php");

	if(isset($_POST['ImieReg']) && isset($_POST['NazwiskoReg']) && ($_POST['EmailReg'] == $_POST['EmailReg2']) && ($_POST['HasloReg'] == $_POST['HasloReg2']) && isset($_POST['Radio'])){
        if($_POST['Radio'] == "lekarz") {
            $form .= "<form action=\"reg.php\" method=\"post\">";
            $form .= "<input type=\"text\" name=\"ImieReg\" placeholder=\"Imię\" id=\"ImieReg\" value=\"" . $_POST['ImieReg'] . "\"/><br>";
            $form .= "<input type=\"text\" name=\"NazwiskoReg\" placeholder=\"Nazwisko\" id=\"NazwiskoReg\" value=\"" . $_POST['NazwiskoReg'] . "\"/><br>";
            $form .= "<input type=\"email\" name=\"EmailReg\" placeholder=\"Email\" id=\"EmailReg\" value=\"" . $_POST['EmailReg'] . "\"/><br>";
            $form .= "<input type=\"email\" name=\"EmailReg2\" placeholder=\"Powtórz Email\" id=\"EmailReg2\" value=\"" . $_POST['EmailReg2'] . "\"/><br>";
            $form .= "<input type=\"password\" name=\"HasloReg\" placeholder=\"Hasło\" id=\"HasloReg\" value=\"" . $_POST['HasloReg'] . "\"/><br>";
            $form .= "<input type=\"password\" name=\"HasloReg2\" placeholder=\"Powtórz Hasło\" id=\"HasloReg2\" value=\"" . $_POST['HasloReg2'] . "\"/><br>";
            $form .= "Lekarz: <input type=\"radio\" name=\"Radio\" id=\"Lekarz\" value=\"lekarz\" checked=\"checked\"/><br>";
            $form .= "Pacjent: <input type=\"radio\" name=\"Radio\" id=\"Pacjent\" value=\"pacjent\" disabled=\"disabled\"/><br><br>";
            echo $form;
            specialization(NULL,$specialization, 'Radio2', 'Specjalizacja:');
            ?>
            <input type="submit" value="Dokończ rejestrację" /><br><br>
            </form>
            <?
            if(isset($_POST['Radio2'])){
                $kwerenda_dodania = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia,specjalizacja) VALUES ";
                $kwerenda_dodania .= "(";
                $kwerenda_dodania .= "'" . $_POST['EmailReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . md5($_POST['HasloReg']) . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['NazwiskoReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['ImieReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['Radio'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['Radio2'] . "'";
                $kwerenda_dodania .= ")";
                // Dodanie lekarza do bazy danych według kwerendy
                $wynik = mysql_query($kwerenda_dodania);
                if (!$wynik) {
                    echo "Blad zapytania dodania! <br /><br />";
                } else {
                    echo "Dodano nowego pacjenta o parametrach: <br>";
                    echo "Imię lekarza: " . $_POST['ImieReg'] . "<br>";
                    echo "Nazwisko lekarza: " . $_POST['NazwiskoReg'] . "<br>";
                    echo "E-mail lekarza: " . $_POST['EmailReg'] . "<br>";
                    echo "Hasło lekarza: " . $_POST['HasloReg'] . " = " . md5($_POST['HasloReg']) . "<br>";
                    echo "Status: " . $_POST['Radio'] . "<br>";
                    echo "Specjalizacja lekarza: " . $_POST['Radio2'] . "<br><br>";
                }
            }
        }
        else {
            // Kwerenda zapisu do bazy użytkowników
            $kwerenda_dodania = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia,specjalizacja) VALUES ";
            $kwerenda_dodania .= "(";
            $kwerenda_dodania .= "'" . $_POST['EmailReg'] . "'" . ",";
            $kwerenda_dodania .= "'" . md5($_POST['HasloReg']) . "'" . ",";
            $kwerenda_dodania .= "'" . $_POST['NazwiskoReg'] . "'" . ",";
            $kwerenda_dodania .= "'" . $_POST['ImieReg'] . "'" . ",";
            $kwerenda_dodania .= "'" . $_POST['Radio'] . "'" . ",";
            $kwerenda_dodania .= "'" . NULL . "'";
            $kwerenda_dodania .= ")";
            // echo "Kwerenda dodania pacjenta: " . $kwerenda_dodania . "<br><br>";

            // Dodanie pacjenta do bazy danych według kwerendy
            $wynik = mysql_query($kwerenda_dodania);
            if (!$wynik) {
                echo "Blad zapytania dodania! <br /><br />";
            } else {
                echo "Dodano nowego pacjenta o parametrach: <br>";
                echo "Imię lekarza: " . $_POST['ImieReg'] . "<br>";
                echo "Nazwisko lekarza: " . $_POST['NazwiskoReg'] . "<br>";
                echo "E-mail lekarza: " . $_POST['EmailReg'] . "<br>";
                echo "Hasło lekarza: " . $_POST['HasloReg'] . " = " . md5($_POST['HasloReg']) . "<br>";
                echo "Status: " . $_POST['Radio'] . "<br><br>";
            }
        }
    }
    else {
        ?>
            <fieldset>
            <form action="reg.php" method="post">
                <input type="text" name="ImieReg" placeholder="Imię" id="ImieReg"/><br>
                <input type="text" name="NazwiskoReg" placeholder="Nazwisko" id="NazwiskoReg"/><br>
                <input type="email" name="EmailReg" placeholder="Email" id="EmailReg"/><br>
                <input type="email" name="EmailReg2" placeholder="Powtórz Email" id="EmailReg2"/><br>
                <input type="password" name="HasloReg" placeholder="Hasło" id="HasloReg"/><br>
                <input type="password" name="HasloReg2" placeholder="Powtórz Hasło" id="HasloReg2"/><br>
                Lekarz: <input type="radio" name="Radio" id="Lekarz" value="lekarz"/><br>
                Pacjent: <input type="radio" name="Radio" id="Pacjent" value="pacjent"/><br>
                <input type="submit" value="Zarejestruj" /><br><br>
            </form>
            </fieldset>
        <?
    }
?>	
<?
include("includes/footer.php");
?>
