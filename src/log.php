<?
	session_start();
	if(($_SESSION['login'] == "") || ($_SESSION['haslo'] == "")){
		$_SESSION['login'] = $_POST['email'];
		$_SESSION['haslo'] = md5($_POST['haslo']);
	}
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>
<?
	$kwerenda = "SELECT email, haslo, nazwisko, uprawnienia FROM nazwiska WHERE email = \"" . $_SESSION['login'] . "\"";
	$logowanie = mysql_query($kwerenda)	or die('B³±d logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
		$_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
	}
	if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
		echo "Witaj, jeste¶ zalogowany jako: <b>" . $_SESSION['login'] . "</b></b><br><br>";
		?>
		<form action="index.php" method="POST">
			<input type="submit" value="Wyloguj siê" />
		</form><br>
        <form action="editMyAccount.php" method="POST">
            <input type="submit" value="Edytuj swoje konto" />
        </form>
        <?
		echo "<br><b>Posiadasz dostêp do opcji pacjenta: </b><br>";
		?>		
		<form action="signUpForDoc.php" method="POST">
			<input type="submit" value="Zapisz siê" /> Zapisz siê do gabinetu jako pacjent 
		</form>
			<form action="editMyVisits.php" method="POST">
			<input type="submit" value="Edytuj swoje zapisy" /><br>
		</form>
		<?
		if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
			echo "<br><b>Posiadasz dostêp do opcji lekarza: </b><br>";
			// TODO Zajmij gabinet, zwolnij gabinet
			// TODO Uzupe³nij/popraw kontrakt
			// TODO Przegl±daj zapisy
			?>			
			<form action="docOffices.php" method="POST">
				<input type="submit" value="Gabinety" name="initGabinet" /> Przejd¼ do strony rezerwacji oraz modyfikacji Twoich gabinetów
			</form>
			<form action="docMyVisits.php" method="POST">
				<input type="submit" value="Zapisy" /> Przejd¼ do strony przegl±dania i edytowania zapisów do Twoich gabinetów
			</form>			
			<?
			if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
				echo "<br><b>Posiadasz dostêp do opcji administratora: </b><br>";
				?>
				<form action="adminEditResources.php" method="POST">
					<input type="submit" value="Budynki i gabinety" /> Przejd¼ do strony edytowania, dodawania i usuwania wszystkich budynków i gabinetów
				</form>
				<form action="adminEditUsers.php" method="POST">
					<input type="submit" value="U¿ytkownicy" /> Przejd¼ do strony edytowania i modyfikowania wszystkich u¿ytkowników
				</form>
                <form action="adminAllResources.php" method="POST">
                    <input type="submit" value="Zasoby bazy danych" /> Przejd¼ do strony przegl±dania wszystkich zasobów w bazie danych
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
		<form action="log.php" method="POST">
		e-mail: <input type="text" name="email" /><br />
		haslo: <input type="password" name="haslo" /><br />
		<input type="submit" value="Loguj" />
		</form>
		<?
	}
?>
<?
include("includes/footer.php");
?>
