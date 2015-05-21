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
			    <input type="submit" value="Wstecz" name="Wstecz" />
                <input type="submit" value="Dalej" name="Dalej" />
            </form>
            <br> Zajmij gabinet <br>
            <form action="gabinet.php" method="POST">
                Godzina rozpoczecia
                <select name="GodzinaRozpoczecia">
                    <option>7:00</option>
                    <option>7:30</option>
                    <option>8:00</option>
                    <option>8:30</option>
                    <option>9:00</option>
                    <option>9:30</option>
                    <option>10:00</option>
                    <option>10:30</option>
                    <option>11:00</option>
                    <option>11:30</option>
                    <option>12:00</option>
                    <option>12:30</option>
                    <option>13:00</option>
                    <option>13:30</option>
                    <option>14:00</option>
                    <option>14:30</option>
                    <option>15:00</option>
                    <option>15:30</option>
                    <option>16:00</option>
                    <option>16:30</option>
                    <option>17:00</option>
                    <option>17:30</option>
                    <option>18:00</option>
                    <option>18:30</option>
                    <option>19:00</option>
                    <option>19:30</option>
                    <option>20:00</option>
                    <option>20:30</option>
                    <option>21:00</option>
                </select><br>
                Godzina zakonczenia
                <select name="GodzinaZakonczenia">
                    <option>7:00</option>
                    <option>7:30</option>
                    <option>8:00</option>
                    <option>8:30</option>
                    <option>9:00</option>
                    <option>9:30</option>
                    <option>10:00</option>
                    <option>10:30</option>
                    <option>11:00</option>
                    <option>11:30</option>
                    <option>12:00</option>
                    <option>12:30</option>
                    <option>13:00</option>
                    <option>13:30</option>
                    <option>14:00</option>
                    <option>14:30</option>
                    <option>15:00</option>
                    <option>15:30</option>
                    <option>16:00</option>
                    <option>16:30</option>
                    <option>17:00</option>
                    <option>17:30</option>
                    <option>18:00</option>
                    <option>18:30</option>
                    <option>19:00</option>
                    <option>19:30</option>
                    <option>20:00</option>
                    <option>20:30</option>
                    <option>21:00</option>
                </select><br>
                <select name="Dzieñ">
                    <option value="Monday">Poniedzia³ek</option>
                    <option value="Tuesday">Wtorek</option>
                    <option value="Wednesday">¦roda</option>
                    <option value="Thursday">Czwartek</option>
                    <option value="Friday">Pi±tek</option>
                </select><br>
                <input type="submit" value="Zajmij" /><br><br>
            </form>

			<?
            // TODO przerobiæ select dni na checkboxy!
			// TODO Dorobiæ wy¶wietlenie aktualnej daty (zakresu dat dla tygodnia)
			// TODO Dorobiæ zapisy/modyfikacjê zajmowania gabinetów
			?>
			<br><table cellpadding="5" border="1">
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
			$daty = gmdate("H:i", 25200);
			$half = gmdate("H:i", 1800);
			$half = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
			while($Godzina < 37) {
				// TODO Kwerendy z zapytaniami dla danej godziny dla danego dnia
				// TODO Kolorowanie(?) wolnych/zajêtych miejsc - z wypisaniem nazwiska lekarza zajmuj±cego gabinet
                // TODO rozwiniêcie powy¿szego pomys³u - sprawdzenie kwerendy zajêto¶ci gabinetu i dopiero wygenerowanie odpowiedniego koloru t³a
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