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
include("includes/stopka.php");
?>
