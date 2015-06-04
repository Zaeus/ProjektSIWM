<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>

// Mo¿liwo¶æ edycji danych konta - email, has³o, imiê, nazwisko, etc.

<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    // TODO funkcja wypisuj±ca tablice z danymi u¿ytkownika z mo¿liwo¶ci± ich edycji
}
else{
    echo "Brak uprawnieñ do tre¶ci.<br>";
}
?>
<?
include("includes/footer.php");
?>
