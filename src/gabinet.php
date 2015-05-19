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
            date_modify($dataKoniec, '+4 day');
            echo "Początek tygodnia:".date_format($_SESSION['data'], 'Y-m-d')."<br>Koniec tygodnia:".date_format($dataKoniec, 'Y-m-d')."<br>";
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
                <select name="Dzień">
                    <option value="Monday">Poniedziałek</option>
                    <option value="Tuesday">Wtorek</option>
                    <option value="Wednesday">Środa</option>
                    <option value="Thursday">Czwartek</option>
                    <option value="Friday">Piątek</option>
                </select><br>
                <input type="submit" value="Zajmij" /><br><br>
            </form>


<?
			 // To do późniejszego wywalenia
			// TODO Dorobić wyświetlenie aktualnej daty (zakresu dat dla tygodnia)
			// TODO Dorobić przyciski tydzień w przód/tył
			// TODO Dorobić zapisy/modyfikację zajmowania gabinetów
			?>
			<br><table cellpadding="5" border="1">
			<tr bgcolor>
			<td style="text-align: center;">Godzina</td>
			<td style="text-align: center;">Poniedziałek</td>
			<td style="text-align: center;">Wtorek</td>
			<td style="text-align: center;">Środa</td>
			<td style="text-align: center;">Czwartek</td>
			<td style="text-align: center;">Piątek</td>
			</tr>
			<?
			$Godzina = 8;
			// Dat nie ruszać bo się zjebią
			$daty = gmdate("H:i", 28800);	
			$half = gmdate("H:i", 1800);
			$half = strtotime(gmdate("H:i", 1800)) - strtotime("00:00");
			while($Godzina < 35) {
				// TODO Kwerendy z zapytaniami dla danej godziny dla danego dnia
				// TODO Kolorowanie(?) wolnych/zajętych miejsc - z wypisaniem nazwiska lekarza zajmującego gabinet
				?>
				<tr bgcolor=white>
				<td width="100" style="text-align: center;">
				<?
				echo $daty;
				?>
				</td>
				<td width="100" style="text-align: center;">A</td>
				<td width="100" style="text-align: center;">B</td>
				<td width="100" style="text-align: center;">C</td>
				<td width="100" style="text-align: center;">D</td>
				<td width="100" style="text-align: center;">E</td>
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
			echo "Nie posiadasz uprawnień lekarza";
		}
	}
	else{
		echo "Brak uprawnień do treści.<br>";
	}
?>
<?
	include("stopka.php");
?>