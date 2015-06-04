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
	$logowanie = mysql_query($kwerenda)	or die('B��d logowania');
	if($logowanie){
		$wiersz = mysql_fetch_assoc($logowanie);
		$hasloSql = $wiersz['haslo'];
		$_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
	}
	if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
		echo "Witaj, jeste� zalogowany jako: <b>" . $_SESSION['login'] . "</b></b><br><br>";
		?>
		<form action="index.php" method="POST">
			<input type="submit" value="Wyloguj si�" />
		</form><br>
        <form action="editMyAccount.php" method="POST">
            <input type="submit" value="Edytuj swoje konto" />
        </form>
        <?
		echo "<br><b>Posiadasz dost�p do opcji pacjenta: </b><br>";
		?>		
		<form action="signUpForDoc.php" method="POST">
			<input type="submit" value="Zapisz si�" /> Zapisz si� do gabinetu jako pacjent 
		</form>
			<form action="editMyVisits.php" method="POST">
			<input type="submit" value="Edytuj swoje zapisy" /><br>
		</form>
		<?
		if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
			echo "<br><b>Posiadasz dost�p do opcji lekarza: </b><br>";
			// TODO Zajmij gabinet, zwolnij gabinet
			// TODO Uzupe�nij/popraw kontrakt
			// TODO Przegl�daj zapisy
			?>			
			<form action="docOffices.php" method="POST">
				<input type="submit" value="Gabinety" name="initGabinet" /> Przejd� do strony rezerwacji oraz modyfikacji Twoich gabinet�w
			</form>
			<form action="docMyVisits.php" method="POST">
				<input type="submit" value="Zapisy" /> Przejd� do strony przegl�dania i edytowania zapis�w do Twoich gabinet�w
			</form>			
			<?
			if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
				echo "<br><b>Posiadasz dost�p do opcji administratora: </b><br>";
				?>
				<form action="adminEditResources.php" method="POST">
					<input type="submit" value="Budynki i gabinety" /> Przejd� do strony edytowania, dodawania i usuwania wszystkich budynk�w i gabinet�w
				</form>
				<form action="adminEditUsers.php" method="POST">
					<input type="submit" value="U�ytkownicy" /> Przejd� do strony edytowania i modyfikowania wszystkich u�ytkownik�w
				</form>
                <form action="adminAllResources.php" method="POST">
                    <input type="submit" value="Zasoby bazy danych" /> Przejd� do strony przegl�dania wszystkich zasob�w w bazie danych
                </form>
				<?
			}
		}
	}
	else{
		// Wyczyszczenie sesji je�eli jest z�e logowanie
		$_SESSION = array();
		session_unset();
		session_destroy();
		?>
		Nie jeste� zalogowany. Zaloguj si�:<br>		
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
