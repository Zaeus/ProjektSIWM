<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/SignUpForDocFunctions.php");
include("functions/LoginPowerFunctions.php");
?>
<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    echo "<fieldset><legend>Specjalizacja gabinetu:</legend>";
    echo "<form action = \"signUpForDoc.php\" method=\"POST\"> ";
    echo "<select name=\"specjalizacjaGabinetu\">";
    echo "<option value=\"USG\" >USG</option>";
    echo "<option value=\"Interna\" >Interna</option>";
    echo "<option value=\"Ginekolog\" >Ginekologia</option>";
    echo "</select>";
    echo "<input type=\"submit\" value=\"Wybierz\" >";
    echo "</form></td>";
    echo "</fieldset>";
    if(isset($_POST['specjalizacjaGabinetu'])) {
        signUpForDoc($_POST['specjalizacjaGabinetu'], NULL);
        $_SESSION['specjalizacjaGabinetu'] = $_POST['specjalizacjaGabinetu'];
    } elseif(isset($_POST['regDate'])) {
        signUpForDoc($_SESSION['specjalizacjaGabinetu'], $_POST['regDate']);
        unset($_SESSION['specjalizacjaGabinetu']);
    }
    if(isset($_POST['godzinaRezerwacji'], $_POST['finalRegDate'], $_POST['officeID'])){
        // Wpisanie nowej rezerwacji do bazy danych
        regVisit($_POST['godzinaRezerwacji'], $_POST['finalRegDate'], $_POST['officeID'], $_SESSION['login']);
    }
    if(isset($_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime'])){
        // Usuni�cie rezerwacji z systemu
        removeMyVisit($_SESSION['login'], $_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime']);
    }
    viewMyVisit($_SESSION['login']);
}
else{
    echo "Brak uprawnie� do tre�ci.<br>";
}
?>
<?
include("includes/footer.php");
?>