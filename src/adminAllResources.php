<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
include("functions/ResourcesFunctions.php");
?>
<?
if(isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
    drawAllResourcesTable("nazwiska");
    drawAllResourcesTable("gabinety");
    drawAllResourcesTable("budynki");
    drawAllResourcesTable("wizyty");
    drawAllResourcesTable("zajetosc");
}
else{
    echo "Brak uprawnień do treści.<br>";
}
?>
<?
include("includes/footer.php");
?>

