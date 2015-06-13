<?
	session_start();
    include("includes/header.php");
    include("includes/SQLConnection.php");
    include("includes/logQuery.php");
    include("functions/LoginPowerFunctions.php");
    include("functions/GenerateDate.php");
    include("functions/DocOfficesFunctions.php");
    include("includes/Parameters.php");

    if(isLoggedDoctor($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])){

        echo "Posiadasz uprawnienia lekarza<br>";

        rentOffice($_POST['Dzien'], $_POST['GodzinaRozpoczecia'], $_POST['GodzinaZakonczenia'],$_POST['OdDnia'], $_POST['DoDnia'], $_SESSION['login'], $officeParameters);
        viewMyReservationTable($_SESSION['login']);
        dateButtons($_POST['Wstecz'], $_POST['Dalej'], $_POST['initGabinet']);
        viewOffice($officeParameters);

    }
include("includes/footer.php");
?>
