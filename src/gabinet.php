<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
//Edycja gabinet�w<br>

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
            echo "Pocz�tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($dataKoniec, 'Y-m-d') . "<br>";
            ?>
            <form action="gabinet.php" method="POST">
			    <input type="submit" value="Tydzie� wstecz" name="Wstecz" />
                <input type="submit" value="Tydzie� w prz�d" name="Dalej" />
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
                        <td><input type="checkbox" name="Pon" value="Pon">Poniedzia�ek</td>
                        <td><input type="checkbox" name="Wto" value="Wto">Wtorek</td>
                        <td><input type="checkbox" name="Sro" value="Sro">�roda</td>
                        <td><input type="checkbox" name="Czw" value="Czw">Czwartek</td>
                        <td><input type="checkbox" name="Pia" value="Pia">Pi�tek<br></td>
                    </tr>
                </table>
                <input type="submit" value="Zajmij" /><br><br>
            </form>

			<?
            // TODO Dorobi� zapisy/modyfikacj� zajmowania gabinet�w
            // TODO Kwerenda pobieraj�ca dane oparte o aktualny dzie� tygodnia
            // TODO Nast�pnie pobrana data jest por�wnywana z aktualn� (czy si� mie�ci - jak si� mie�ci to sprawdzanie dalszej zaj�to�ci godziny)
            // TODO sprawdzana jest godzina czy nie jest zaj�ta
            // TODO jak po tym wszystkim nie jest zaj�ta to mo�na doda� nowy rekord rezerwacji gabinetu
			?>
			<br><table align="center" cellpadding="5" border="1">
			<tr bgcolor>
			<td style="text-align: center;">Godzina</td>
			<td style="text-align: center;">Poniedzia�ek<br>
			<?
			echo date_format($dataDni, 'Y-m-d');
			?>
			</td>
			<td style="text-align: center;">Wtorek<br>
			<?
			echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
			?>
			</td>
			<td style="text-align: center;">�roda<br>
			<?
			echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
			?>
			</td>
			<td style="text-align: center;">Czwartek<br>
			<?
			echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
			?>
			</td>
			<td style="text-align: center;">Pi�tek<br>
			<?
			echo date_format(date_modify($dataDni, '+1 day'), 'Y-m-d');
			?>
			</td>
			</tr>
			<?
			$Godzina = 8;
			// Dat nie rusza� bo si� zjebi�
			$daty = gmdate("H:i", 25200);
			$half = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
			while($Godzina < 37) {
				// TODO Kwerendy z zapytaniami dla danej godziny dla danego dnia - jak jest to kolorowanie czerwony dla zaj�tego
                // TODO plus pobranie nazwiska lekarza i jego wy�wietlenie na polu
                // TODO w przeciwnym wypadku kolor zielony bez niczego
				?>
				<tr bgcolor=white>
                    <td width="100" style="text-align: center;">
                    <?
                    echo $daty;

                    ?>
                    </td>
                    <td width="100" bgcolor="green" style="text-align: center;">A</td>
                    <td width="100" bgcolor="green" style="text-align: center;">B</td>
                    <td width="100" bgcolor="green" style="text-align: center;">C</td>
                    <td width="100" bgcolor="green" style="text-align: center;">D</td>
                    <td width="100" bgcolor="red" style="text-align: center;">E</td>
				</tr>
				<?
				$Godzina = $Godzina + 1;
				$daty = date("H:i", strtotime($daty ) + $half);
			}			
			?>
			</table>
			<?
		}
		else {
			echo "Nie posiadasz uprawnie� lekarza";
		}
	}
	else{
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("stopka.php");
?>
