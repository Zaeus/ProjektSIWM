<?
	session_start();
?>
<?
    include("includes/header.php");
    include("includes/SQLConnection.php");
    include("includes/logQuery.php");
    include("functions/LoginPowerFunctions.php");
?>
<?
	if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
	    //Przegl±danie zapisów do lekarza
	}
	else{
		echo "Brak uprawnieñ do tre¶ci.<br>";
	}
?>
<?
include("includes/footer.php");
?>
