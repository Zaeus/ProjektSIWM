<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] == "admin") {
			echo "Posiadasz uprawnienia admina";
			// Edycja gabinetów dla admina
		}
		else {
			echo "Nie posiadasz uprawnieñ admina";
		}
	}
	else {
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("stopka.php");
?>
