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
		if($_SESSION['uprawnienia'] >= 1) {
			echo "Posiadasz uprawnienia lekarza<br>"; // To do późniejszego wywalenia
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