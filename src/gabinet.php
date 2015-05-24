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
            <form action="gabinet.php" method="POST">
                Godzina rozpoczecia
                <select name="GodzinaRozpoczecia">
                    <?
                    $select_h = gmdate("H:i", 25200);
                    $select_30m = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
                    $form = "<option value=" . $select_h . ">7:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">7:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">8:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">8:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">9:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">9:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">10:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">10:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">11:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">11:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">12:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">12:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">13:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">13:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">14:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">14:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">15:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">15:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">16:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">16:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">17:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">17:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">18:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">18:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">19:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">19:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">20:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">20:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">21:00</option>";
                    echo $form;
                    ?>
                </select><br>
                Godzina zakonczenia
                <select name="GodzinaZakonczenia">
                    <?
                    $select_h = gmdate("H:i", 25200);
                    $select_30m = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form = "<option value=" . $select_h . ">7:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">8:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">8:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">9:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">9:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">10:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">10:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">11:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">11:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">12:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">12:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">13:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">13:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">14:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">14:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">15:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">15:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">16:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">16:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">17:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">17:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">18:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">18:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">19:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">19:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">20:00</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">20:30</option>";
                    $select_h = date("H:i", strtotime($select_h ) + $select_30m);
                    $form .= "<option value=" . $select_h . ">21:00</option>";
                    echo $form;
                    ?>
                </select><br>
                <table align="center" cellpadding="5" border="1">
                    <tr>
                        <td><input type="checkbox" name="Pon" value="Pon">Poniedzia³ek</td>
                        <td><input type="checkbox" name="Wto" value="Wto">Wtorek</td>
                        <td><input type="checkbox" name="Sro" value="Sro">¦roda</td>
                        <td><input type="checkbox" name="Czw" value="Czw">Czwartek</td>
                        <td><input type="checkbox" name="Pia" value="Pia">Pi±tek<br></td>
                    </tr>
                </table>
                <input type="submit" value="Zajmij" /><br><br>
            </form>

			<?
            // TODO Dorobiæ zapisy/modyfikacjê zajmowania gabinetów
            // TODO Kwerenda pobieraj±ca dane oparte o aktualny dzieñ tygodnia
            // TODO Nastêpnie pobrana data jest porównywana z aktualn± (czy siê mie¶ci - jak siê mie¶ci to sprawdzanie dalszej zajêto¶ci godziny)
            // TODO sprawdzana jest godzina czy nie jest zajêta
            // TODO jak po tym wszystkim nie jest zajêta to mo¿na dodaæ nowy rekord rezerwacji gabinetu
            $kwerenda_przegladania_gab = "SELECT ID_gabinetu FROM zajetosc";
            $wynik_gab = mysql_query($kwerenda_przegladania_gab) or die('B³±d zapytania');
            $forma_przegladania_gab = "<fieldset><legend>Przejrzyj zajêto¶æ gabinet:</legend><form action = \"gabinet.php\" method=\"POST\">";
            $forma_przegladania_gab .= "Gabinet: <select name=\"ID_przegladany_gabinet\">";
            if($wynik_gab) {
                while($wiersz_gab = mysql_fetch_assoc($wynik_gab)) {
                    if($poprzedni != $wiersz_gab['ID_gabinetu']) {
                        $forma_przegladania_gab .= "<option value=\"" . $wiersz_gab['ID_gabinetu'] . "\">" . $wiersz_gab['ID_gabinetu'] . "</option>";
                        $poprzedni = $wiersz_gab['ID_gabinetu'];
                    }
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
            </fieldset>
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
