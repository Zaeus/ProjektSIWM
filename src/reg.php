<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
?>
	<h4>Rejestracja nowego pacjenta/lekarza:</h4>
<?
	if(isset($_POST['ImieReg']) && isset($_POST['NazwiskoReg']) && ($_POST['EmailReg'] == $_POST['EmailReg2']) && ($_POST['HasloReg'] == $_POST['HasloReg2']) && isset($_POST['Radio'])){
        if($_POST['Radio'] == "lekarz") {
            // TODO Doko�czenie rejestracji poprzez wyb�r specjalno�ci w przypadku lekarza
            $form = "<div id=\"RegForm\">";
            $form .= "<form action=\"reg.php\" method=\"post\">";
            $form .= "<label for=\"Imi�\"></label><input type=\"text\" name=\"ImieReg\" placeholder=\"Imi�\" id=\"ImieReg\" value=\"" . $_POST['ImieReg'] . "\"/><br>";
            $form .= "<label for=\"Nazwisko\"></label><input type=\"text\" name=\"NazwiskoReg\" placeholder=\"Nazwisko\" id=\"NazwiskoReg\" value=\"" . $_POST['NazwiskoReg'] . "\"/><br>";
            $form .= "<label for=\"Email\"></label><input type=\"email\" name=\"EmailReg\" placeholder=\"Email\" id=\"EmailReg\" value=\"" . $_POST['EmailReg'] . "\"/><br>";
            $form .= "<label for=\"Powt�rz Email\"></label><input type=\"email\" name=\"EmailReg2\" placeholder=\"Powt�rz Email\" id=\"EmailReg2\" value=\"" . $_POST['EmailReg2'] . "\"/><br>";
            $form .= "<label for=\"Has�o\"></label><input type=\"password\" name=\"HasloReg\" placeholder=\"Has�o\" id=\"HasloReg\" value=\"" . $_POST['HasloReg'] . "\"/><br>";
            $form .= "<label for=\"Powt�rz has�o\"></label><input type=\"password\" name=\"HasloReg2\" placeholder=\"Powt�rz Has�o\" id=\"HasloReg2\" value=\"" . $_POST['HasloReg2'] . "\"/><br>";
            $form .= "<input type=\"radio\" name=\"Radio\" id=\"Lekarz\" value=\"lekarz\" checked=\"checked\"/><label for=\"Lekarz\">Lekarz</label><br>";
            $form .= "<input type=\"radio\" name=\"Radio\" id=\"Pacjent\" value=\"pacjent\" disabled=\"disabled\"/><label for=\"Pacjent\">Pacjent</label><br><br>";
            echo $form;
            ?>
                    <input type="radio" name="Radio2" id="Spec" value="Interna" checked="checked" /><label for="Internista">Internista</label><br>
                    <input type="radio" name="Radio2" id="Spec" value="Ginekolog"/><label for="Ginekolog">Ginekologia</label><br>
                    <input type="radio" name="Radio2" id="Spec" value="USG"/><label for="USG">USG</label><br>
                    <input type="submit" value="Doko�cz rejestracj�" /><br><br>
                </form>
            </div>
            <?
            if(($_POST['Radio2'] == "Ginekologia") || ($_POST['Radio2'] == "Interna") || ($_POST['Radio2'] == "USG")){
                $kwerenda_dodania = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia,specjalizacja) VALUES ";
                $kwerenda_dodania .= "(";
                $kwerenda_dodania .= "'" . $_POST['EmailReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . md5($_POST['HasloReg']) . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['NazwiskoReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['ImieReg'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['Radio'] . "'" . ",";
                $kwerenda_dodania .= "'" . $_POST['Radio2'] . "'";
                $kwerenda_dodania .= ")";

                // Dodanie lekarza do bazy danych wed�ug kwerendy
                $wynik = mysql_query($kwerenda_dodania);
                if (!$wynik) {
                    echo "Blad zapytania dodania! <br /><br />";
                } else {
                    echo "Dodano nowego pacjenta o parametrach: <br>";
                    echo "Imi� lekarza: " . $_POST['ImieReg'] . "<br>";
                    echo "Nazwisko lekarza: " . $_POST['NazwiskoReg'] . "<br>";
                    echo "E-mail lekarza: " . $_POST['EmailReg'] . "<br>";
                    echo "Has�o lekarza: " . $_POST['HasloReg'] . " = " . md5($_POST['HasloReg']) . "<br>";
                    echo "Status: " . $_POST['Radio'] . "<br>";
                    echo "Specjalizacja lekarza: " . $_POST['Radio2'] . "<br><br>";
                }
            }
        }
        else {
            // Kwerenda zapisu do bazy u�ytkownik�w
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

            // Dodanie pacjenta do bazy danych wed�ug kwerendy
            $wynik = mysql_query($kwerenda_dodania);
            if (!$wynik) {
                echo "Blad zapytania dodania! <br /><br />";
            } else {
                echo "Dodano nowego pacjenta o parametrach: <br>";
                echo "Imi� lekarza: " . $_POST['ImieReg'] . "<br>";
                echo "Nazwisko lekarza: " . $_POST['NazwiskoReg'] . "<br>";
                echo "E-mail lekarza: " . $_POST['EmailReg'] . "<br>";
                echo "Has�o lekarza: " . $_POST['HasloReg'] . " = " . md5($_POST['HasloReg']) . "<br>";
                echo "Status: " . $_POST['Radio'] . "<br><br>";
            }
        }
    }
    else {
        ?>
        <div id="RegForm">
            <form action="reg.php" method="post">
                <label for="Imi�"></label><input type="text" name="ImieReg" placeholder="Imi�" id="ImieReg"/><br>
                <label for="Nazwisko"></label><input type="text" name="NazwiskoReg" placeholder="Nazwisko" id="NazwiskoReg"/><br>
                <label for="Email"></label><input type="email" name="EmailReg" placeholder="Email" id="EmailReg"/><br>
                <label for="Powt�rz Email"></label><input type="email" name="EmailReg2" placeholder="Email2" id="EmailReg2"/><br>
                <label for="Has�o"></label><input type="password" name="HasloReg" placeholder="Has�o" id="HasloReg"/><br>
                <label for="Powt�rz has�o"></label><input type="password" name="HasloReg2" placeholder="Has�o2" id="HasloReg2"/><br>
                <input type="radio" name="Radio" id="Lekarz" value="lekarz"/><label for="Lekarz">Lekarz</label><br>
                <input type="radio" name="Radio" id="Pacjent" value="pacjent"/><label for="Pacjent">Pacjent</label><br>
                <input type="submit" value="Zarejestruj" /><br><br>
            </form>
        </div>
        <?
    }
?>	
<?
	include("stopka.php");
?>
