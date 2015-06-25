<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
include("functions/ResourcesFunctions.php");
include("includes/Parameters.php")
?>
<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    if(isset($_POST['uprawnienia']) || isset($_POST['id_nazwiska']) || isset($_POST['nazwisko']) || isset($_POST['imie']) || isset($_POST['email']) || isset($_POST['specjalizacja']) || (isset($_POST['haslo1']) && isset($_POST['haslo2']))) {
        $patientUpdateQuery = "UPDATE nazwiska SET email='" . $_POST['email'] . "', ";
        $patientUpdateQuery .= "nazwisko='" . $_POST['nazwisko'] . "', ";
        $patientUpdateQuery .= "imie='" . $_POST['imie'] . "', ";
        if(($_POST['haslo1'] == $_POST['haslo2']) && ($_POST['haslo1'] != "") && ($_POST['haslo2'] != "")){
            $patientUpdateQuery .= "haslo='" . md5($_POST['haslo1']) . "', ";
        }
        $patientUpdateQuery .= "specjalizacja=";
        if($_POST['specjalizacja'] == ""){
            $patientUpdateQuery .= "NULL ";
        }
        else {
            $patientUpdateQuery .= "'" . $_POST['specjalizacja'] . "' ";
        }
        $patientUpdateQuery .= "WHERE id_nazwiska='" . $_POST['id_nazwiska'] . "'";
        mysql_query($patientUpdateQuery) or die('Błąd zapytania edycji');
        echo "<i>Edytowano rekord użytkownika: " . $_POST['nazwisko'] . " " . $_POST['imie'] . "</i>";
    }
    $patientInfoQuery = "SELECT * FROM nazwiska WHERE email='" . $_SESSION['login'] . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('Bład zapytania o ID nazwiska lekarza');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    echo "<br><fieldset><legend>Dane użytkownika w bazie</legend>";
    echo "Mooesz edytować dane swojego konta - takie jak:<br><b>imię</b>, <b>nazwisko</b>, <b>hasło</b>, <b>email</b> oraz jeżeli jesteś lekarzem swoją <b>specjalizację</b><br><br>";
    echo "<form action = \"editMyAccount.php\" method=\"POST\">";
    echo "Uprawnienia użytkownika: <input type=\"text\" name=\"uprawnienia\" value=\"" . $patientInfoLine['uprawnienia'] . "\" disabled><br>";
    echo "<input type=\"hidden\" name=\"id_nazwiska\" value=\"" . $patientInfoLine['id_nazwiska'] . "\" >";
    echo "Nazwisko użytkownika: <input type=\"text\" name=\"nazwisko\" value=\"" . $patientInfoLine['nazwisko'] . "\" ><br>";
    echo "Imię użytkownika: <input type=\"text\" name=\"imie\" value=\"" . $patientInfoLine['imie'] . "\" ><br>";
    echo "Email użytkownika: <input type=\"email\" name=\"email\" value=\"" . $patientInfoLine['email'] . "\" ><br>";
    echo "Nowe hasło użytkownika: <input type=\"pass\" name=\"haslo1\" placeholder=\"Nowe Hasło\"\" ><br>";
    echo "Powtórz hasło: <input type=\"pass\" name=\"haslo2\" placeholder=\"Powtórz Hasło\"\"><br>";
    specialization($patientInfoLine['specjalizacja'], $specialization, 'specjalizacja','Specjalizacja użytkownika', $patientInfoLine['uprawnienia']);
    echo "<br>";
    echo "<input type=\"submit\" value=\"Edytuj\" ></form>";
    echo "</fieldset>";
}
else{
    echo "Brak uprawnień do treści.<br>";
}
?>
<?
include("includes/footer.php");
?>
