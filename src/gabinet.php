<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
//Edycja gabinetów<br>

<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] == "lekarz" || $_SESSION['uprawnienia'] == "admin") {
			echo "Posiadasz uprawnienia lekarza<br>";
            if(isset($_POST['Wstecz'])){
                date_modify($_SESSION['data'], '-1 week');
                unset($_POST['Wstecz']);
             }else if(isset($_POST['Dalej'])) {
                date_modify($_SESSION['data'], '+1 week');
                unset($_POST['Dalej']);
            }else {
                $_SESSION['data'] = new DateTime();
            }
            while((date_format($_SESSION['data'],'l'))!="Monday"){
                date_modify($_SESSION['data'], '-1 day');
            }
            $dataKoniec = clone $_SESSION['data'];
			$dataDni = clone $_SESSION['data'];
            date_modify($dataKoniec, '+4 day');
            echo "Pocz±tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($dataKoniec, 'Y-m-d') . "<br>";
            ?>
            <form action="gabinet.php" method="POST">
			    <input type="submit" value="Tydzieñ wstecz" name="Wstecz" />
                <input type="submit" value="Tydzieñ w przód" name="Dalej" />
            </form>
            <br> Zajmij gabinet <br>
            <?
            echo "<form action=\"gabinet.php\" method=\"POST\">";
            if(isset($_POST['Dzien'])){
                $_SESSION['Dzien'] = $_POST['Dzien'];
                echo "Godzina rozpoczêcia "."<select name=\"GodzinaRozpoczecia\">";
                generateDate(date_create('7:00'), date_create('19:00'));
                echo "<br><input type=\"submit\" value=\"Dalej\" /></form>";
            }elseif(isset($_POST['GodzinaRozpoczecia'])){
                $_SESSION['GodzinaRozpoczecia'] = $_POST['GodzinaRozpoczecia'];
                $poczatek = date_create($_SESSION['GodzinaRozpoczecia']);
                echo "</select>"."Godzina zakoñczenia "."<select name=\"GodzinaZakonczenia\">";
                generateDate(date_modify($poczatek,'+2 hours'), date_create('21:00'));
                echo "<br><input type=\"submit\" value=\"Zajmij\" /></form>";
            }else{
                if(isset($_POST['GodzinaZakonczenia'])){
                echo $_SESSION['Dzien']."<br>";
                echo $_SESSION['GodzinaRozpoczecia']."<br>";
                echo $_POST['GodzinaZakonczenia'];
                //TODO Tutaj ma byæ obs³u¿enie formularza, bo wychodz±c z tego ifa niszczymy dane
                unset ($_SESSION['Dzien']);
                unset ($_SESSION['GodzinaRozpoczecia']);
                unset ($_POST['GodzinaZakonczenia']);
            }
                echo "</select><br>";
                echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
                echo "<tr>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pon\">Poniedzia³ek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Wto\">Wtorek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Sro\">¦roda</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Czw\">Czwartek</td>";
                echo "<td><input type=\"radio\" name=\"Dzien\" value=\"Pia\">Pi±tek<br></td>";
                echo "</tr></table><input type=\"submit\" value=\"Dalej\" /><br><br></form>";
            }

            // TODO Dorobiæ zapisy/modyfikacjê zajmowania gabinetów
            // TODO Kwerenda pobieraj±ca dane oparte o aktualny dzieñ tygodnia
            // TODO Nastêpnie pobrana data jest porównywana z aktualn± (czy siê mie¶ci - jak siê mie¶ci to sprawdzanie dalszej zajêto¶ci godziny)
            // TODO sprawdzana jest godzina czy nie jest zajêta
            // TODO jak po tym wszystkim nie jest zajêta to mo¿na dodaæ nowy rekord rezerwacji gabinetu
            // TODO zapytanie o wszystkie gabinety od dni do dnia z wy¶wietleniem jego specjalizacji - je¿eli jest wolny siê wy¶wietli, w przeciwnym wypadku ignoruj
            // TODO zajêcie gabinetu w danym dniu nie krócej ni¿ 2h nie d³u¿ej ni¿ 8h
            $kwerenda_przegladania_gab = "SELECT ID_gabinetu FROM zajetosc";
            $wynik_gab = mysql_query($kwerenda_przegladania_gab) or die('B³±d zapytania');
            $forma_przegladania_gab = "<fieldset><legend>Przejrzyj zajêto¶æ gabinet:</legend><form action = \"gabinet.php\" method=\"POST\">";
            $forma_przegladania_gab .= "Gabinet: <select name=\"ID_przegladany_gabinet\">";
            if($wynik_gab) {
                $iterator = 0;
                while($wiersz_gab = mysql_fetch_assoc($wynik_gab)) {
                    $tablica_gabinetow[$iterator] = $wiersz_gab['ID_gabinetu'];
                    $iterator = $iterator + 1;
                }
                sort($tablica_gabinetow);
                $tablica_gabinetow = array_unique($tablica_gabinetow);
                foreach($tablica_gabinetow as $key => $val){
                    $forma_przegladania_gab .= "<option value=\"" . $val . "\">" . $val . "</option>";
                }
            }
            $forma_przegladania_gab .= "</select>";
            $forma_przegladania_gab .= "<input type=\"submit\" value=\"Przegl±daj gabinet\" >";
            $forma_przegladania_gab .= "</form>";
            echo $forma_przegladania_gab;
            if(isset($_POST['ID_przegladany_gabinet']) || isset($_SESSION['ID_przegladany_gabinet'])) {
                if(isset($_POST['ID_przegladany_gabinet'])){
                    if($_POST['ID_przegladany_gabinet'] != $_SESSION['ID_przegladany_gabinet']){
                        $_SESSION['ID_przegladany_gabinet'] = $_POST['ID_przegladany_gabinet'];
                    }
                }
                echo "Przegl±dany gabinet: " . $_SESSION['ID_przegladany_gabinet'];
                ?>
                <br>
                <table align="center" cellpadding="5" border="1">
                    <tr bgcolor>
                        <td style="text-align: center;">Godzina</td>
                        <td style="text-align: center;">Poniedzia³ek<br>
                        <?
                        echo date_format($dataDni, 'Y-m-d');
                        ?>
                        </td>
                        <td style="text-align: center;">Wtorek<br>
                        <?
                        echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
                        ?>
                        </td>
                        <td style="text-align: center;">¦roda<br>
                        <?
                        echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
                        ?>
                        </td>
                        <td style="text-align: center;">Czwartek<br>
                        <?
                        echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
                        ?>
                        </td>
                        <td style="text-align: center;">Pi±tek<br>
                        <?
                        echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
                        ?>
                        </td>
                    </tr>
                <?
                $Godzina = 8;
                // Dat nie ruszaæ bo siê zjebi±
                $godzina_tab = gmdate("H:i", 25200);
                $godzina_tab_prim = gmdate("H:i:s", 25200);
                $half = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
                while($Godzina < 37) {
                    // TODO Kwerendy z zapytaniami dla danej godziny dla danego dnia - jak jest to kolorowanie czerwony dla zajêtego
                    // TODO plus pobranie nazwiska lekarza i jego wy¶wietlenie na polu
                    // TODO w przeciwnym wypadku kolor zielony bez niczego
                    ?>
                    <tr bgcolor=white>
                        <td width="100" style="text-align: center;">
                        <?
                        echo $godzina_tab;
                        ?>
                        </td>
                        <?
                        // Sprawdzenie poniedzia³ku
                        $kwerenda_PON = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Pon' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
                        $wynik_PON = mysql_query($kwerenda_PON) or die('B³±d zapytania');
                        if($wynik_PON) {
                            unset($tab_wiersz_PON);
                            while($wiersz_PON = mysql_fetch_assoc($wynik_PON)) {
                                if(($wiersz_PON['od_godziny'] <= $godzina_tab_prim) && ($wiersz_PON['do_godziny'] >= $godzina_tab_prim)){
                                    $tab_wiersz_PON = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $wiersz_PON['ID_nazwiska_Lek'] . "</td>";
                                }
                            }
                        }
                        if(!isset($tab_wiersz_PON)){
                            $tab_wiersz_PON = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
                        }
                        echo $tab_wiersz_PON;
                        ?>
                        <?
                        // Sprawdzenie wtorku
                        $kwerenda_WTO = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Wto' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
                        $wynik_WTO = mysql_query($kwerenda_WTO) or die('B³±d zapytania');
                        if($wynik_WTO) {
                            unset($tab_wiersz_WTO);
                            while($wiersz_WTO = mysql_fetch_assoc($wynik_WTO)) {
                                if(($wiersz_WTO['od_godziny'] <= $godzina_tab_prim) && ($wiersz_WTO['do_godziny'] >= $godzina_tab_prim)){
                                    $tab_wiersz_WTO = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $wiersz_WTO['ID_nazwiska_Lek'] . "</td>";
                                }
                            }
                        }
                        if(!isset($tab_wiersz_WTO)){
                            $tab_wiersz_WTO = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
                        }
                        echo $tab_wiersz_WTO;
                        ?>
                        <?
                        // Sprawdzenie ¶rody
                        $kwerenda_SRO = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Sro' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
                        $wynik_SRO = mysql_query($kwerenda_SRO) or die('B³±d zapytania');
                        if($wynik_SRO) {
                            unset($tab_wiersz_SRO);
                            while($wiersz_SRO = mysql_fetch_assoc($wynik_SRO)) {
                                if(($wiersz_SRO['od_godziny'] <= $godzina_tab_prim) && ($wiersz_SRO['do_godziny'] >= $godzina_tab_prim)){
                                    $tab_wiersz_SRO = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $wiersz_SRO['ID_nazwiska_Lek'] . "</td>";
                                }
                            }
                        }
                        if(!isset($tab_wiersz_SRO)){
                            $tab_wiersz_SRO = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
                        }
                        echo $tab_wiersz_SRO;
                        ?>
                        <?
                        // Sprawdzenie czwartku
                        $kwerenda_CZW = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Czw' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
                        $wynik_CZW = mysql_query($kwerenda_CZW) or die('B³±d zapytania');
                        if($wynik_CZW) {
                            unset($tab_wiersz_CZW);
                            while($wiersz_CZW = mysql_fetch_assoc($wynik_CZW)) {
                                if(($wiersz_CZW['od_godziny'] <= $godzina_tab_prim) && ($wiersz_CZW['do_godziny'] >= $godzina_tab_prim)){
                                    $tab_wiersz_CZW = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $wiersz_CZW['ID_nazwiska_Lek'] . "</td>";
                                }
                            }
                        }
                        if(!isset($tab_wiersz_CZW)){
                            $tab_wiersz_CZW = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
                        }
                        echo $tab_wiersz_CZW;
                        ?>
                        <?
                        // Sprawdzenie pi±tku
                        $kwerenda_PIA = "SELECT ID_nazwiska_Lek, od_dnia, do_dnia, od_godziny, do_godziny FROM zajetosc WHERE dzien_tyg='Pia' AND ID_gabinetu='" . $_SESSION['ID_przegladany_gabinet'] . "'";
                        $wynik_PIA = mysql_query($kwerenda_PIA) or die('B³±d zapytania');
                        if($wynik_PIA) {
                            unset($tab_wiersz_PIA);
                            while($wiersz_PIA = mysql_fetch_assoc($wynik_PIA)){
                                if(($wiersz_PIA['od_godziny'] <= $godzina_tab_prim) && ($wiersz_PIA['do_godziny'] >= $godzina_tab_prim)){
                                    $tab_wiersz_PIA = "<td width=\"100\" bgcolor=\"red\" style=\"text-align: center\">" . $wiersz_PIA['ID_nazwiska_Lek'] . "</td>";
                                }
                            }
                        }
                        if(!isset($tab_wiersz_PIA)){
                            $tab_wiersz_PIA = "<td width=\"100\" bgcolor=\"green\" style=\"text-align: center\"></td>";
                        }
                        echo $tab_wiersz_PIA;
                        ?>
                    </tr>
                    <?
                    $Godzina = $Godzina + 1;
                    $godzina_tab = date("H:i", strtotime($godzina_tab ) + $half);
                    $godzina_tab_prim = date("H:i:s", strtotime($godzina_tab_prim ) + $half);
                }
                ?>
                </table>
            <?
            }
            ?>
        <?
		}
		else {
			echo "Nie posiadasz uprawnieñ lekarza";
		}
	}
	else{
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("stopka.php");
?>

<?
function generateDate($start, $stop){
    $dataFormula = "";
    for($current=$start; $current!=$stop; date_modify($current,'+30 minutes')){
        $formatedData = date_format($current,'H:i');
        $dataFormula .="<option value=" . $formatedData . ">$formatedData</option>";
    }
    $formatedData = date_format($stop,'H:i');
    $dataFormula .= "<option value=" . $formatedData . ">$formatedData</option>";
    echo $dataFormula;
}
?>