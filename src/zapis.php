<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>

//Zapis do lekarza

<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		// Edycja istniejących zapisów z możliwością ich usuwania
	}
	else{
		echo "Brak uprawnień do treści.<br>";
	}
?>
<?
	include("stopka.php");
?>

