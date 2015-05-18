<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
// Edycja gabinetów dla admina
<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] == "admin") {
			echo "Posiadasz uprawnienia admina";
			// Edycja gabinetów dla admina
		}
		else {
			echo "Nie posiadasz uprawnień admina";
		}
	}
	else {
		echo "Brak uprawnień do treści.<br>";
	}
?>
<?
	include("stopka.php");
?>