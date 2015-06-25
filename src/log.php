<?
// Utworzenie nowej sesji i zapisanie danych z logowania
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
// Pobranie danych dla emaila użytego w logowaniu oraz zapisanie pobranego zakodowanego hasła oraz uprawnień z bazy danych
$kwerenda = "SELECT email, haslo, nazwisko, uprawnienia FROM nazwiska WHERE email = \"" . $_SESSION['login'] . "\"";
$logowanie = mysql_query($kwerenda)	or die('Błąd logowania');
if($logowanie){
    $wiersz = mysql_fetch_assoc($logowanie);
    $hasloSql = $wiersz['haslo'];
    $_SESSION['uprawnienia'] = $wiersz['uprawnienia'];
}
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    echo "<fieldset><legend>Witaj, jesteś zalogowany jako:<b> " . $_SESSION['login'] . "</b></legend>";
    echo "<fieldset><legend><b>Opcje:</b></legend>";
    ?>
		<form  action="index.php" method="POST">
			<input  type="submit" value="Wyloguj się" />
		</form><br>
        <form  action="editMyAccount.php" method="POST">
            <input type="submit" value="Edytuj swoje konto" />
        </form></fieldset>
        <?
    echo "<br><fieldset><legend><b>Posiadasz dostęp do opcji pacjenta: </b></legend>";
    ?>
		<form style="text-align: left" action="signUpForDoc.php" method="POST">
		<label for="wizyta">Przejdź do strony z zapisami do gabinetu lekarza oraz wszystkimi twoimi wizytami</label>
			<input type="submit" value="Zapisz się na wizytę" name="wizyta" />
		</form></fieldset>
		<?
    if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
        echo "<br><fieldset><legend><b>Posiadasz dostęp do opcji lekarza: </b></legend>";
        ?><br>
			<form style="text-align: left" action="docOffices.php" method="POST">
			<label for="initGabinet">Przejdź do strony rezerwacji oraz modyfikacji Twoich gabinetów</label>
				<input type="submit" value="Gabinety" name="initGabinet" />
			</form><br>
			<form style="text-align: left" action="docMyVisits.php" method="POST">
			<label for="Zapisy">Przejdź do strony przeglądania i edytowania zapisów do Twoich gabinetów</label>
				<input type="submit" value="Zapisy" name="Zapisy" />
			</form><br></fieldset>
			<?
        if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
            echo "<br><fieldset><legend><b>Posiadasz dostęp do opcji administratora: </b></legend>";
            ?><br>
				<form style="text-align: left" action="adminEditResources.php" method="POST">
				<label for="budynki">Przejdź do strony edytowania, dodawania i usuwania wszystkich budynków i gabinetów</label>
					<input type="submit" value="Budynki i gabinety" name="budynki" />
				</form><br>
				<form style="text-align: left" action="adminEditUsers.php" method="POST">
				<label for="użytkownicy">Przejdź do strony edytowania i modyfikowania wszystkich użytkowników</label>
					<input type="submit" value="Użytkownicy" name="użytkownicy" />
				</form><br>
                <form style="text-align: left" action="adminAllResources.php" method="POST">
                <label for="zasoby">Przejdź do strony przeglądania wszystkich zasobów w bazie danych</label>
                    <input type="submit" value="Zasoby bazy danych" name="zasoby" />
                </form><br></fieldset>
				<?
        }
        echo "</fieldset>";
    }
}
else{
    // Wyczyszczenie sesji jeżeli jest niepoprawne logowanie
    $_SESSION = array();
    session_unset();
    session_destroy();
    ?>
    <fieldset><legend><b>Nie jesteś zalogowany. Zaloguj się:</b></legend><br>
        <form action="log.php" method="POST">
            e-mail: <input type="text" name="email" /><br />
            haslo: <input type="password" name="haslo" /><br />
            <input type="submit" value="Loguj" />
        </form></fieldset>
<?
}
?>
<?
include("includes/footer.php");
?>