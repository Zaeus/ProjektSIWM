<?
	session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
include("functions/DocVisitsFunctions.php");
?>
<?
	if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){
        if(isset($_POST['removeVisitOfficeID'], $_POST['removePatientID'], $_POST['removeVisitDate'], $_POST['removeVisitTime'])){
            removeVisitToMyOffice($_SESSION['login'], $_POST['removePatientID'], $_POST['removeVisitOfficeID'], $_POST['removeVisitDate'], $_POST['removeVisitTime']);
        }
        allMyVisitsTable($_SESSION['login']);
	}
	else{
		echo "Brak uprawnień do treści.<br>";
	}
?>
<?
include("includes/footer.php");
?>
