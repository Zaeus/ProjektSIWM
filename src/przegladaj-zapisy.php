<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>

//Przegl�danie zapis�w

<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		if(($_SESSION['uprawnienia'] == "lekarz") || ($_SESSION['uprawnienia'] == "admin")) {
            echo "Masz uprawnienia lekarza";
			//Przegl�danie zapis�w
		}
		else {
			echo "Nie posiadasz uprawnie� lekarza";
		}
	}
	else{
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("stopka.php");
?>