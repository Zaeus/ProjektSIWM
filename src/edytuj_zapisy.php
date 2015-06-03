<?
session_start();
?>
<?
include("includes/naglowek.php");
include("includes/polaczenieSQL.php");
include("includes/kwerenda_log.php");
?>
//Edycja zapisów do gabinetów
<?
if(isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)){
    // Edycja istniejących zapisów z możliwością ich usuwania
}
else{
    echo "Brak uprawnień do treści.<br>";
}
?>
<?
include("includes/stopka.php");
?>
