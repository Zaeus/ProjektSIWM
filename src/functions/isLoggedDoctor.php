<?php
function isLoggedDoctor($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        if ($_SESSION['uprawnienia'] == "lekarz" || $_SESSION['uprawnienia'] == "admin") {
            return true;
        } else {
            echo "Nie posiadasz uprawnień lekarza";
            return false;
        }
    }
    else{
        echo "Brak uprawnień do treści.<br>";
        return false;
    }
}
?>