<?
	session_start();
?>
<?
	include("naglowek.php");	
	include("polaczenieSQL.php");
	include("kwerenda_log.php");
?>
//Edycja zapis�w do gabinet�w
<?
	if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
		// Edycja istniej�cych zapis�w z mo�liwo�ci� ich usuwania
	}
	else{
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("stopka.php");
?>