<?
	session_start();
?>
<?
	include("includes/naglowek.php");
	include("includes/polaczenieSQL.php");
	include("includes/kwerenda_log.php");
    include("functions/isLoggedPatient.php");
    include("functions/isLoggedDoctor.php");
    include("functions/isLoggedAdmin.php");
?>

//Przegl�danie zapis�w

<?
	if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
	    //Przegl�danie zapis�w
	}
	else{
		echo "Brak uprawnie� do tre�ci.<br>";
	}
?>
<?
	include("includes/stopka.php");
?>
