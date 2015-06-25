<?
session_start();
?>
<?php
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>
<?
if (isLoggedAdmin($hasloSql, $_SESSION['login'], $_SESSION['haslo'], $_SESSION['uprawnienia'])) {
    // Formularz zakresu dat z których ma zostaæ wygenerowany raport zajêto¶ci gabinetów
    $reportForm = "<br><fieldset><legend>Generuj raport z zajêto¶ci gabinetów w podanym zakresie dat</legend><form action = \"generateReport.php\" method=\"POST\"> ";
    $reportForm .= "Od: <input type=\"date\" name=\"lowDate\" \"><br>";
    $reportForm .= "Do: <input type=\"date\" name=\"highDate\" \"><br>";
    $reportForm .= "<input type=\"submit\" value=\"Generuj raport\" >";
    $reportForm .= "</form></fieldset>";
    echo $reportForm;
    if(isset($_POST['lowDate']) && isset($_POST['highDate']) && ($_POST['lowDate'] <= $_POST['highDate'])){
        echo "<br><fieldset><legend>Raport z zajêto¶ci gabinetów pomiêdzy " . $_POST['lowDate'] . " a " . $_POST['highDate'] . "</legend>";
        echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
        echo "<tr>";
        echo "<td>Dane lekarza</td>";
        echo "<td>Dzieñ tygodnia</td>";
        echo "<td>Od</td>";
        echo "<td>Do</td>";
        echo "<td>Od godziny</td>";
        echo "<td>Do godziny</td>";
        echo "<td>Gabinet</td>";
        echo "</tr>";
        $reportQuery = "SELECT * FROM zajetosc WHERE od_dnia >= '" . $_POST['lowDate'] . "' AND do_dnia <= '" . $_POST['highDate'] . "'";
        $reportResult = mysql_query($reportQuery) or die('B³±d zapytania o dane do raportu');
        while($reportLine = mysql_fetch_assoc($reportResult)){
            echo "<tr>";

            echo "</tr>";
        }
        echo "</table>";
        echo "</fieldset>";
    }
}
?>
<?
include("includes/footer.php");
?>
