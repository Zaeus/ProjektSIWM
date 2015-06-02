<?
	session_start();
?>
<?
	include("includes/naglowek.php");
	include("includes/polaczenieSQL.php");
	include("includes/kwerenda_log.php");
?>

//Zapis do lekarza

<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		// Edycja istniej±cych zapisów z mo¿liwo¶ci± ich usuwania
	}
	else{
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
	include("includes/stopka.php");
?>

