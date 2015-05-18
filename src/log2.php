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
	$logowanie = mysql_query($kwerenda)	or die('Błąd logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
		$_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
	}
	
	if(isset($_SESSION['login']) && ($hasloSql == $_SESSION['haslo'])){
		echo "Witaj, jesteś zalogowany jako: " . $_SESSION['login'] . "<br><br>";
		?>
		<form action="index.php" method="POST">
			<input type="submit" value="Wyloguj się" />
		</form>		
		<?
		echo "<br>Posiadasz dostęp do opcji pacjenta: <br>";		
		?>		
		<form action="zapis.php" method="POST">
			<input type="submit" value="Zapisz się" /> Zapisz się do gabinetu jako pacjent 
		</form>
			<form action="edycja.php" method="POST">
			<input type="submit" value="Edytuj swoje zapisy" /><br>
		</form>
		<?
		if(($_SESSION['uprawnienia'] == "lekarz") || ($_SESSION['uprawnienia'] == "admin")) {
			echo "<br>Posiadasz dostęp do opcji lekarza: <br>";
			// TODO Zajmij gabinet, zwolnij gabinet
			// TODO Uzupełnij/popraw kontrakt
			// TODO Przeglądaj zapisy
			?>			
			<form action="gabinet.php" method="POST">
				<input type="submit" value="Gabinety" /> Przejdź do strony rezerwacji oraz modyfikacji Twoich gabinetów
			</form>
			<form action="przegladaj-zapisy.php" method="POST">
				<input type="submit" value="Zapisy" /> Przejdź do strony przeglądania i edytowania zapisów do Twoich gabinetów
			</form>			
			<?
			if($uprawnieniaSql == "admin") {
				echo "<br>Posiadasz dostęp do opcji administratora: <br>";
				// TODO Dodaj gabinet, edytuj gabinet, usuń gabinet
				// TODO Dodaj budynek, edytuj budynek, usuń budynek
				// TODO Dodaj użytkownika, edytuj użytkownika, usuń użytkownika 
				?>
				<form action="edit-gab.php" method="POST">
					<input type="submit" value="Gabinety" /> Przejdź do strony edytowania i modyfikowania wszystkich gabinetów
				</form>
				<form action="edit-bud.php" method="POST">
					<input type="submit" value="Budynki" /> Przejdź do strony edytowania i modyfikowania wszystkich budynków
				</form>
				<form action="edit-user.php" method="POST">
					<input type="submit" value="Użytkownicy" /> Przejdź do strony edytowania i modyfikowania wszystkich użytkowników
				</form>
				<?
			}
		}
	}
	else{
		// Wyczyszczenie sesji jeżeli jest złe logowanie
		$_SESSION = array();
		session_unset();
		session_destroy();
		?>
		Nie jesteś zalogowany. Zaloguj się:<br>		
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