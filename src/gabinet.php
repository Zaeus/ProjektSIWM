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
			?>
			<br><table cellpadding="5" border="1">
			<tr bgcolor>
			<td><center>Godzina</center></td>
			<td><center>Poniedziałek</center></td>
			<td><center>Wtorek</center></td>
			<td><center>Środa</center></td>
			<td><center>Czwartek</center></td>
			<td><center>Piątek</center></td>
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
				<td width="100"><center> 
				<?
				echo $daty;
				?>
				</center></td>
				<td width="100"><center>A</center></td>
				<td width="100"><center>B</center></td>
				<td width="100"><center>C</center></td>
				<td width="100"><center>D</center></td>
				<td width="100"><center>E</center></td>
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