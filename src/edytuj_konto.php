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
include("includes/stopka.php");
?>
