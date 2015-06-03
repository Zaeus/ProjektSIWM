<?php
function isLoggedPatient($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        return true;
    }
    else{
        echo "Brak uprawnień do treści.<br>";
        return false;
    }
}
?>