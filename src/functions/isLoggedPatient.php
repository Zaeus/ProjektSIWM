<?php
function isLoggedPatient($hasloSql, $userLogin, $userPassword){
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        return true;
    }
    else{
        echo "Brak uprawnie� do tre�ci.<br>";
        return false;
    }
}
?>
