<?php
// Funkcja drawAllResourcesTable najpierw pobiera wszystki nazwy kolumn z tabeli o podanej nazwie, a nastêpnie w
// oparciu o nie jest budowana kwerenda pobieraj±ca wszystki dane z tej¿e tabeli
function drawAllResourcesTable($tableName)
{
    $columnNameQuery = "SELECT COLUMN_NAME FROM information_schema.columns WHERE table_name = '" . $tableName . "' ORDER BY ordinal_position";
    $columnNameResult = mysql_query($columnNameQuery) or die('B³±d zapytania o nazwy kolumn');
    $columnCount = mysql_num_rows($columnNameResult);
    $allResourcesQuery = "SELECT ";
    $iterator = 0;
    echo "<fieldset><legend>Tabela: " . $tableName . "</legend>";
    echo "<table align=\"center\" cellpadding=\"5\" border=\"1\">";
    echo "<tr>";
    while($columnNameLine = mysql_fetch_assoc($columnNameResult)) {
        if($iterator < $columnCount-1) {
            echo "<td>" . $columnNameLine['COLUMN_NAME'] . "</td>";
            $allResourcesQuery .= $columnNameLine['COLUMN_NAME'] . ", ";
            $iterator++;
        } else {
            echo "<td>" . $columnNameLine['COLUMN_NAME'] . "</td>";
            $allResourcesQuery .= $columnNameLine['COLUMN_NAME'] . " FROM " . $tableName;
        }
    }
    echo "</tr>";
    $allResourcesQuery .= " WHERE 1";
    $allResourcesResult = mysql_query($allResourcesQuery) or die('B³±d zapytania o zawarto¶æ tabeli');
    while($allResourcesLine = mysql_fetch_assoc($allResourcesResult)) {
        echo "<tr>";
        foreach($allResourcesLine as $key => $value){
            echo "<td>" . $value . "</td>";
        }
        echo "</tr>";
    }
    echo "</table></fieldset><br>";
}
?>
