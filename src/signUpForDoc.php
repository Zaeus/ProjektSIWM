<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/SignUpForDocFunctions.php");
include("functions/LoginPowerFunctions.php");
include("includes/Parameters.php");
include("functions/ResourcesFunctions.php");
?>
<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    echo "<fieldset><legend><b>Specjalizacja gabinetu:</b></legend>";
    echo "<form action = \"signUpForDoc.php\" method=\"POST\"> ";
    specialization(NULL,$specialization, 'specjalizacjaGabinetu','');
    echo "<input type=\"submit\" value=\"Wybierz\" >";
    echo "</form></td>";
    if(isset($_POST['specjalizacjaGabinetu'])) {
        signUpForDoc($_POST['specjalizacjaGabinetu']);
        $_SESSION['specjalizacjaGabinetu'] = $_POST['specjalizacjaGabinetu'];
    } elseif(isset($_POST['regDate'])) {
        signUpForDoc2($_SESSION['specjalizacjaGabinetu'], $_POST['regDate'], $_POST['officeID'], $_POST['fromTime'], $_POST['sinceDate']);
        unset($_SESSION['specjalizacjaGabinetu']);
    }
    if(isset($_POST['godzinaRezerwacji'], $_POST['finalRegDate'], $_POST['officeID'], $_POST['docID'])){
        // Wpisanie nowej rezerwacji do bazy danych
        regVisit($_POST['godzinaRezerwacji'], $_POST['finalRegDate'], $_POST['officeID'], $_POST['docID'], $_SESSION['login']);
    }
    if(isset($_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime'])){
        // Usunięcie rezerwacji z systemu
        removeMyVisit($_SESSION['login'], $_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime']);
    }
    echo "</fieldset>";
    viewMyVisit($_SESSION['login']);
}
else{
    echo "Brak uprawnień do treści.<br>";
}
?>
<?
include("includes/footer.php");
?>
