<?php
function isLoggedAdmin($hasloSql, $userLogin, $userPassword, $userPower){
    if (isset($userLogin) && ($userPassword == $hasloSql)) {
        if ($userPower == "admin") {
            return true;
        } else {
            return false;
        }
    }
    else{
        echo "Brak uprawnie� do tre�ci.<br>";
        return false;
    }
}
?>
