<?
function drawTable($data, $idGabinetu){
    $dataWyswietlanieDni = clone $data;
    if(isset($idGabinetu)){
?>
<br>
<table align="center" cellpadding="5" border="1">
    <tr bgcolor>
        <td style="text-align: center;">Godzina</td>
        <td style="text-align: center;">Poniedzia³ek<br>
            <?
            echo date_format($dataWyswietlanieDni, 'Y-m-d');
            ?>
        </td>
        <td style="text-align: center;">Wtorek<br>
            <?
            echo date_format(date_modify($dataWyswietlanieDni, '+1 day'), 'Y-m-d');
            ?>
        </td>
        <td style="text-align: center;">¦roda<br>
            <?
            echo date_format(date_modify($dataWyswietlanieDni, '+1 day'), 'Y-m-d');
            ?>
        </td>
        <td style="text-align: center;">Czwartek<br>
            <?
            echo date_format(date_modify($dataWyswietlanieDni, '+1 day'), 'Y-m-d');
            ?>
        </td>
        <td style="text-align: center;">Pi±tek<br>
            <?
            echo date_format(date_modify($dataWyswietlanieDni, '+1 day'), 'Y-m-d');
            ?>
        </td>
    </tr>
    <?
    $godzina = date_create('7:00');
    while($godzina!=date_create('21:30')) {
        // TODO Kwerendy z zapytaniami dla danej godziny dla danego dnia - jak jest to kolorowanie czerwony dla zajêtego
        // TODO plus pobranie nazwiska lekarza i jego wy¶wietlenie na polu
        // TODO w przeciwnym wypadku kolor zielony bez niczego
        ?>
        <tr bgcolor=white>
            <td width="100" style="text-align: center;">
                <?
                echo date_format($godzina,'H:i');
                ?>
            </td>
            <?
            // Sprawdzenie poniedzia³ku
            checkTime("Pon",$godzina,$data,$idGabinetu);
            date_modify($data, '+1 day');
            // Sprawdzenie wtorku
            checkTime("Wto",$godzina,$data,$idGabinetu);
            date_modify($data, '+1 day');
            // Sprawdzenie ¶rody
            checkTime("Sro",$godzina,$data,$idGabinetu);
            date_modify($data, '+1 day');
            // Sprawdzenie czwartku
            checkTime("Czw",$godzina,$data,$idGabinetu);
            date_modify($data, '+1 day');
            // Sprawdzenie pi±tku
            checkTime("Pia",$godzina,$data,$idGabinetu);
            date_modify($data, '-4 days')
            ?>
        </tr>
        <?
        $godzina = date_modify($godzina, '+30 minutes');
    }
    ?>
</table>
<?}}