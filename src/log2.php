<?
	session_start();
	if(($_SESSION['login'] == "") || ($_SESSION['haslo'] == "")){
		$_SESSION['login'] = $_POST['email'];
		$_SESSION['haslo'] = md5($_POST['haslo']);
	}
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
?>
<?
	$kwerenda = "SELECT email, haslo, nazwisko, uprawnienia FROM nazwiska WHERE email = \"" . $_SESSION['login'] . "\"";
	$logowanie = mysql_query($kwerenda)	or die('B³±d logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
		$_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
	}
	
	if(isset($_SESSION['login']) && ($hasloSql == $_SESSION['haslo'])){
		echo "Witaj, jeste¶ zalogowany jako: <b>" . $_SESSION['login'] . "</b></b><br><br>";
		?>
		<form action="index.php" method="POST">
			<input type="submit" value="Wyloguj siê" />
		</form>		
		<?
		echo "<br><b>Posiadasz dostêp do opcji pacjenta: </b><br>";
		?>		
		<form action="zapis.php" method="POST">
			<input type="submit" value="Zapisz siê" /> Zapisz siê do gabinetu jako pacjent 
		</form>
			<form action="edycja.php" method="POST">
			<input type="submit" value="Edytuj swoje zapisy" /><br>
		</form>
		<?
		if(($_SESSION['uprawnienia'] == "lekarz") || ($_SESSION['uprawnienia'] == "admin")) {
			echo "<br><b>Posiadasz dostêp do opcji lekarza: </b><br>";
			// TODO Zajmij gabinet, zwolnij gabinet
			// TODO Uzupe³nij/popraw kontrakt
			// TODO Przegl±daj zapisy
			?>			
			<form action="gabinet.php" method="POST">
				<input type="submit" value="Gabinety" /> Przejd¼ do strony rezerwacji oraz modyfikacji Twoich gabinetów
			</form>
			<form action="przegladaj-zapisy.php" method="POST">
				<input type="submit" value="Zapisy" /> Przejd¼ do strony przegl±dania i edytowania zapisów do Twoich gabinetów
			</form>			
			<?
			if($_SESSION['uprawnienia'] == "admin") {
				echo "<br><b>Posiadasz dostêp do opcji administratora: </b><br>";
				?>
				<form action="edit-bud-gab.php" method="POST">
					<input type="submit" value="Budynki i gabinety" /> Przejd¼ do strony edytowania, dodawania i usuwania wszystkich budynków i gabinetów
				</form>
				<form action="edit-user.php" method="POST">
					<input type="submit" value="U¿ytkownicy" /> Przejd¼ do strony edytowania i modyfikowania wszystkich u¿ytkowników
				</form>
				<?
			}
		}
	}
	else{
		// Wyczyszczenie sesji je¿eli jest z³e logowanie
		$_SESSION = array();
		session_unset();
		session_destroy();
		?>
		Nie jeste¶ zalogowany. Zaloguj siê:<br>		
		<form action="log2.php" method="POST">
		e-mail: <input type="text" name="email" /><br />
		haslo: <input type="password" name="haslo" /><br />
		<input type="submit" value="Loguj" />
		</form>
		<?
	}
?>
<?
	include("stopka.php");
?>
