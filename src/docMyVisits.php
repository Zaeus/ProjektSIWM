<?
	session_start();
?>
<?
    include("includes/header.php");
    include("includes/SQLConnection.php");
    include("includes/logQuery.php");
    include("functions/LoginPowerFunctions.php");
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
include("includes/footer.php");
?>