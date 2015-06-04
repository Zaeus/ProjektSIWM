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
include("includes/stopka.php");
?>

