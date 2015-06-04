<?
	session_start();
?>
<?
	include("includes/naglowek.php");
	include("includes/polaczenieSQL.php");
	include("includes/kwerenda_log.php");
    include("functions/signUpForDoc.php");
    include("functions/regVisit.php");
    include("functions/viewMyVisit.php");
    include("functions/removeMyVisit.php");
    include("functions/isLoggedPatient.php");
    include("functions/isLoggedDoctor.php");
    include("functions/isLoggedAdmin.php");
?>
<?
	if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
        echo "<fieldset><legend>Specjalizacja gabinetu:</legend>";
        echo "<form action = \"zapis.php\" method=\"POST\"> ";
        echo "<select name=\"specjalizacjaGabinetu\">";
        echo "<option value=\"USG\" >USG</option>";
        echo "<option value=\"Interna\" >Interna</option>";
        echo "<option value=\"Ginekolog\" >Ginekologia</option>";
        echo "</select>";
        echo "<input type=\"submit\" value=\"Wybierz\" >";
        echo "</form></td>";
        echo "</fieldset>";
        if(isset($_POST['specjalizacjaGabinetu'])) {
            SignUpForDoc($_POST['specjalizacjaGabinetu']);
        }
        if(isset($_POST['godzinaRezerwacji']) && isset($_POST['regDate']) && isset($_POST['officeID'])){
            // Wpisanie nowej rezerwacji do bazy danych
            RegVisit($_POST['godzinaRezerwacji'], $_POST['regDate'], $_POST['officeID'], $_SESSION['login']);
        }
        if(isset($_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime'])){
            removeMyVisit($_SESSION['login'], $_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime']);
        }
        // TODO Edycja istniej±cych zapisów z mo¿liwo¶ci± ich usuwania nie pó¼niej ni¿ 24h przed
        ViewMyVisit($_SESSION['login']);
	}
	else{
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("includes/stopka.php");
?>
