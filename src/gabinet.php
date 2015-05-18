<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
//Edycja gabinetów

<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if($_SESSION['uprawnienia'] >= 1) {
			echo "Posiadasz uprawnienia lekarza";
			//Edycja gabinetów
		}
		else {
			echo "Nie posiadasz uprawnień lekarza";
		}
	}
	else{
		echo "Brak uprawnień do treści.<br>";
	}
?>
<?
	include("stopka.php");
?>