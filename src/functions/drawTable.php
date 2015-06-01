<?
function drawTable($date, $idGabinetu){
    $dateShowDay = clone $date;
    if(isset($idGabinetu)){
?>
    <br>
    <table align="center" cellpadding="5" border="1">
        <tr bgcolor>
            <td style="text-align: center;">Godzina</td>
            <td style="text-align: center;">Poniedzia³ek<br>
                <?
                echo date_format($dateShowDay, 'Y-m-d');
                ?>
            </td>
            <td style="text-align: center;">Wtorek<br>
                <?
                echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                ?>
            </td>
            <td style="text-align: center;">¦roda<br>
                <?
                echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                ?>
            </td>
            <td style="text-align: center;">Czwartek<br>
                <?
                echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                ?>
            </td>
            <td style="text-align: center;">Pi±tek<br>
                <?
                echo date_format(date_modify($dateShowDay, '+1 day'), 'Y-m-d');
                ?>
            </td>
        </tr>
        <?
        $time = date_create('7:00');
        while($time != date_create('21:30')) {
            ?>
            <tr bgcolor=white>
                <td width="100" style="text-align: center;">
                    <?
                    echo date_format($time,'H:i');
                    ?>
                </td>
                <?
                // Sprawdzenie poniedzia³ku
                checkTime("Pon", $time, $date, $idGabinetu);
                date_modify($date, '+1 day');
                // Sprawdzenie wtorku
                checkTime("Wto", $time, $date, $idGabinetu);
                date_modify($date, '+1 day');
                // Sprawdzenie ¶rody
                checkTime("Sro", $time, $date, $idGabinetu);
                date_modify($date, '+1 day');
                // Sprawdzenie czwartku
                checkTime("Czw", $time, $date, $idGabinetu);
                date_modify($date, '+1 day');
                // Sprawdzenie pi±tku
                checkTime("Pia", $time, $date, $idGabinetu);
                date_modify($date, '-4 days')
                ?>
            </tr>
            <?
            $time = date_modify($time, '+30 minutes');
        }
        ?>
    </table>
    <?
    }
}
