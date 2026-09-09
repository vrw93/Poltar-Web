<?php
include "components/insertPilihan.php";
include "components/insertPendaftaran.php";
include "components/updatePilihan.php";
include "components/updatePendaftaran.php";

function saveChoice($database, $Choices, $keahlian, $userId){
    $now = new DateTime();
    $currentDate = $now->format('y-m-d H:i:s');

    $database->begin_transaction();
    echo("<pre>");
    echo("transaction begin");
    try{
        echo("Inserting Pendaftaran Data");
        insertPendaftaran($database, $userId, 1, $currentDate, $keahlian);
        $pendaftaranId = getPendaftaranIdByUserId($database, $userId);
        echo("Done");

        echo("Inserting Pilihan Data");
        foreach($Choices as $item){
            echo("Inserting Pilihan Data : " . $item['id']);
            try{
                insertPilihan($database, $pendaftaranId, $item['id'], $item['prio'], 4);
            }catch (Exception $e){
                echo($e);
                $database->rollback();
            }
        }
        echo("Done");
        $database->commit();
        return true;
    }catch (mysqli_sql_exception $e){
        if($e->getCode() === 1062){
            $pendaftaranId = getPendaftaranIdByUserId($database, $userId);
            updatePendaftaranById($database, $keahlian, $currentDate, 3, $pendaftaranId);
            updatePilihan($database, $Choices, $pendaftaranId);
            $database->commit();
            return true;
        }else{
            $database->rollback();
            return false;
        }
    }
}
?>