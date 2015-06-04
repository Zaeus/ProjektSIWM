<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>

//Przegl±danie zasobów bazy danych
// TODO funkcja wypisuj±ca tablice z danymi przyjmuj±ca jako argument nazwê docelowej tabeli

<?
if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
    // TODO funkcja wypisuj±ca tablice z danymi przyjmuj±ca jako argument nazwê docelowej tabeli
}
else{
    echo "Brak uprawnieñ do tre¶ci.<br>";
}
?>
<?
include("includes/footer.php");
?>

