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
			// Edycja gabinet�w dla admina
		}
		else {
			echo "Nie posiadasz uprawnie� admina";
		}
	}
	else {
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("stopka.php");
?>
