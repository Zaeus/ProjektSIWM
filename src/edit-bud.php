<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>

// Edycja budynków dla admina
<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] == 2) {
			echo "Posiadasz uprawnienia admina";
			// Edycja budynkóww dla admina
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