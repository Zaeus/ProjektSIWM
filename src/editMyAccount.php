<?
session_start();
?>
<?
include("includes/header.php");
include("includes/SQLConnection.php");
include("includes/logQuery.php");
include("functions/LoginPowerFunctions.php");
?>
<?
if(isLoggedPatient($hasloSql, $_SESSION['login'], $_SESSION['haslo'])){
    if(isset($_POST['uprawnienia']) || isset($_POST['id_nazwiska']) || isset($_POST['nazwisko']) || isset($_POST['imie']) || isset($_POST['email']) || isset($_POST['specjalizacja']) || (isset($_POST['haslo1']) && isset($_POST['haslo2']))) {
        $patientUpdateQuery = "UPDATE nazwiska SET email='" . $_POST['email'] . "', ";
        $patientUpdateQuery .= "nazwisko='" . $_POST['nazwisko'] . "', ";
        $patientUpdateQuery .= "imie='" . $_POST['imie'] . "', ";
        if(($_POST['haslo1'] == $_POST['haslo2']) && ($_POST['haslo1'] != "") && ($_POST['haslo2'] != "")){
            $patientUpdateQuery .= "haslo='" . md5($_POST['haslo1']) . "', ";
        }
        $patientUpdateQuery .= "specjalizacja=";
        if($_POST['specjalizacja'] == ""){
            $patientUpdateQuery .= "NULL ";
        }
        else {
            $patientUpdateQuery .= "'" . $_POST['specjalizacja'] . "' ";
        }
        $patientUpdateQuery .= "WHERE id_nazwiska='" . $_POST['id_nazwiska'] . "'";
        mysql_query($patientUpdateQuery) or die('B��d zapytania edycji');
        echo "<i>Edytowano rekord u�ytkownika: " . $_POST['nazwisko'] . " " . $_POST['imie'] . "</i>";
    }
    $patientInfoQuery = "SELECT * FROM nazwiska WHERE email='" . $_SESSION['login'] . "'";
    $patientInfoResult = mysql_query($patientInfoQuery) or die('B��d zapytania o ID nazwiska lekarza');
    $patientInfoLine = mysql_fetch_assoc($patientInfoResult);
    echo "<br><fieldset><legend>Dane u�ytkownika w bazie</legend>";
    echo "Mo�esz edytowa� dane swojego konta - takie jak:<br><b>imi�</b>, <b>nazwisko</b>, <b>has�o</b>, <b>email</b> oraz je�eli jeste� lekarzem swoj� <b>specjalizacj�</b><br><br>";
    echo "<form action = \"editMyAccount.php\" method=\"POST\">";
    echo "Uprawnienia u�ytkownika: <input type=\"text\" name=\"uprawnienia\" value=\"" . $patientInfoLine['uprawnienia'] . "\" disabled><br>";
    echo "<input type=\"hidden\" name=\"id_nazwiska\" value=\"" . $patientInfoLine['id_nazwiska'] . "\" >";
    echo "Nazwisko u�ytkownika: <input type=\"text\" name=\"nazwisko\" value=\"" . $patientInfoLine['nazwisko'] . "\" ><br>";
    echo "Imi� u�ytkownika: <input type=\"text\" name=\"imie\" value=\"" . $patientInfoLine['imie'] . "\" ><br>";
    echo "Email u�ytkownika: <input type=\"email\" name=\"email\" value=\"" . $patientInfoLine['email'] . "\" ><br>";
    echo "Nowe has�o u�ytkownika: <input type=\"pass\" name=\"haslo1\" placeholder=\"Nowe Has�o\"\" ><br>";
    echo "Powt�rz has�o: <input type=\"pass\" name=\"haslo2\" placeholder=\"Powt�rz Has�o\"\"><br>";
    echo "Specjalizacja u�ytkownika <select name=\"specjalizacja\"";
    if(($patientInfoLine['uprawnienia'] == "pacjent") || ($patientInfoLine['uprawnienia'] == "admin")){
        echo " disabled";
    }
    echo ">";
    if($patientInfoLine['specjalizacja'] == "USG"){
        echo "<option value=\"USG\" selected=\"selected\">USG</option>";
        echo "<option value=\"Interna\" >Interna</option>";
        echo "<option value=\"Ginekolog\" >Ginekologia</option>";
    }
    elseif($patientInfoLine['specjalizacja'] == "Interna"){
        echo "<option value=\"USG\" >USG</option>";
        echo "<option value=\"Interna\" selected=\"selected\">Interna</option>";
        echo "<option value=\"Ginekolog\" >Ginekologia</option>";
    }
    elseif($patientInfoLine['specjalizacja'] == "Ginekolog"){
        echo "<option value=\"USG\" >USG</option>";
        echo "<option value=\"Interna\" >Interna</option>";
        echo "<option value=\"Ginekolog\" selected=\"selected\">Ginekologia</option>";
    }
    else{
        echo "<option value=\"NULL\" ></option>";
        echo "<option value=\"USG\" >USG</option>";
        echo "<option value=\"Interna\" >Interna</option>";
        echo "<option value=\"Ginekolog\" >Ginekologia</option>";
    }
    echo "</select><br>";
    echo "<input type=\"submit\" value=\"Edytuj\" ></form>";
    echo "</fieldset>";
}
else{
    echo "Brak uprawnie� do tre�ci.<br>";
}
?>
<?
include("includes/footer.php");
?>
