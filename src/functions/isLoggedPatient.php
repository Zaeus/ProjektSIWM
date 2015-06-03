<?php
function isLoggedPatient($hasloSql, $_SESSION){
    if (isset($_SESSION['login']) && ($_SESSION['haslo'] == $hasloSql)) {
        return true;
    }
    else{
        echo "Brak uprawnieñ do tre¶ci.<br>";
        return false;
    }
}
?>