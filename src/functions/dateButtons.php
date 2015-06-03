<?php
function dateButtons($_POST){

    if(isset($_POST['Wstecz'])){
        date_modify($_SESSION['data'], '-1 week');
        unset($_POST['Wstecz']);
    }
    else if(isset($_POST['Dalej'])) {
        date_modify($_SESSION['data'], '+1 week');
        unset($_POST['Dalej']);
    }
    elseif(!isset($_POST)||isset($_POST['initGabinet'])){
        $_SESSION['data'] = new DateTime();
    }
    while((date_format($_SESSION['data'],'l'))!="Monday"){
        date_modify($_SESSION['data'], '-1 day');
    }

    $endDate = clone $_SESSION['data'];
    date_modify($endDate, '+4 day');
    echo "Pocz±tek tygodnia:" . date_format($_SESSION['data'], 'Y-m-d') . "<br>Koniec tygodnia:" . date_format($endDate, 'Y-m-d') . "<br>";
    ?>

    <form action="gabinet.php" method="POST">
        <input type="submit" value="Tydzieñ wstecz" name="Wstecz" />
        <input type="submit" value="Tydzieñ w przód" name="Dalej" />
    </form>
<?}