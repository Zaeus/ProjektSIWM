<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>
//Edycja zapis�w do gabinet�w
<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    // Edycja istniej�cych zapis�w z mo�liwo�ci� ich usuwania
}
else{
    echo "Brak uprawnie� do tre�ci.<br>";
}
?>
<?
include("includes/footer.php");
?>
