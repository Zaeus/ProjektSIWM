<?
session_start();
?>
<?
include("includes/naglowek.php");
include("includes/polaczenieSQL.php");
include("includes/kwerenda_log.php");
?>

//Przegl±danie zasobów bazy danych
// TODO funkcja wypisuj±ca tablice z danymi przyjmuj±ca jako argument nazwê docelowej tabeli

<?
if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
    // TODO funkcja wypisuj±ca tablice z danymi przyjmuj±ca jako argument nazwê docelowej tabeli
}
else{
    echo "Brak uprawnieñ do tre¶ci.<br>";
}
?>
<?
include("includes/stopka.php");
?>

