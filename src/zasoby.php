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

//Przegl�danie zasob�w bazy danych
// TODO funkcja wypisuj�ca tablice z danymi przyjmuj�ca jako argument nazw� docelowej tabeli

<?
if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
    // TODO funkcja wypisuj�ca tablice z danymi przyjmuj�ca jako argument nazw� docelowej tabeli
}
else{
    echo "Brak uprawnie� do tre�ci.<br>";
}
?>
<?
include("includes/stopka.php");
?>

