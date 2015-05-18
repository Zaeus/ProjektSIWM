<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
?>
	<h4>Rejestracja nowego pacjenta/lekarza</h4>
	<div id="RegForm">
		<form action="reg.php" method="post">
			<label for="Imię"></label><input type="text" name="ImieReg" placeholder="Imię" id="ImieReg"/><br>
			<label for="Nazwisko"></label><input type="text" name="NazwiskoReg" placeholder="Nazwisko" id="NazwiskoReg"/><br>
			<label for="Email"></label><input type="email" name="EmailReg" placeholder="Email" id="EmailReg"/><br>
			<label for="Hasło"></label><input type="text" name="HasloReg" placeholder="Hasło" id="HasloReg"/><br>
			<input type="radio" name="Radio" id="Lekarz" value="lekarz"/><label for="Lekarz">Lekarz</label><br>
			<input type="radio" name="Radio" id="Pacjent" value="pacjent"/><label for="Pacjent">Pacjent</label><br>
			<input type="submit" value="Zarejestruj" /><br><br>
		</form>
	</div>
<?
	if(isset($_POST['EmailReg']) ){
		if(isset($_POST['ImieReg']) && isset($_POST['NazwiskoReg']) && isset($_POST['EmailReg']) && isset($_POST['HasloReg']) && isset($_POST['Radio'])){
			echo "Rejestrujesz się danymi:<br>";
			echo $_POST['ImieReg'] . "<br>";
			echo $_POST['NazwiskoReg'] . "<br>";
			echo $_POST['EmailReg'] . "<br>";
			echo $_POST['HasloReg'] . " = " . md5($_POST['HasloReg']) ."<br>";
			echo $_POST['Radio'] . "<br><br>";
			
			// Kwerenda zapisu do bazy użytkowników
			$kwerenda_dodania = "INSERT INTO nazwiska (email,haslo,nazwisko,imie,uprawnienia) VALUES ";
			$kwerenda_dodania .= "(";
			$kwerenda_dodania .= "'" . $_POST['EmailReg'] . "'" . ",";
			$kwerenda_dodania .= "'" . md5($_POST['HasloReg']) . "'" . ",";
			$kwerenda_dodania .= "'" . $_POST['NazwiskoReg'] . "'" . ",";
			$kwerenda_dodania .= "'" . $_POST['ImieReg'] . "'" . ",";
			$kwerenda_dodania .= "'" . $_POST['Radio'] . "'";
			$kwerenda_dodania .= ")";
			echo "Kwerenda dodania użytkownika: " . $kwerenda_dodania . "<br><br>";
			
			// Dodanie użytkownika do bazy danych według kwerendy
			$wynik = mysql_query($kwerenda_dodania);
			if(!$wynik) {
				echo "Blad zapytania! <br /><br />";
			}
			else {
				echo "Dodano nowy rekord o podanych parametrach <br>";
			}
		}
		else {
			echo "Błąd rejestracji - wprowadź raz jeszcze dane";
		}
	}
?>	
<?
	include("stopka.php");
?>