<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>

// Mo�liwo�� edycji danych konta - email, has�o, imi�, nazwisko, etc.

<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    // TODO funkcja wypisuj�ca tablice z danymi u�ytkownika z mo�liwo�ci� ich edycji
}
else{
    echo "Brak uprawnie� do tre�ci.<br>";
}
?>
<?
include("includes/footer.php");
?>
