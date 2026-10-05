<?php
require_once __DIR__ . "/../../../logic/database.php";
require_once __DIR__ . "/../../../logic/registration/admin/resultDataManager.php";

#classes
$resultDataMgr = new resultDataManager($database);

$admin=true;$login=true;$csrf=true;$method='GET';
include __DIR__ . "/../../verifyHelper.php";

$acceptedPendaftar = $resultDataMgr->getAcceptedPendaftar();

header('Content-Type: text/csv; charset=UTF-8');

$output = fopen('php://output', 'w');

fputcsv($output, [
    'No',
    'Nama Lengkap',
    'Jabatan Diterima',
    'Kelas',
    'Nilai',
    'Keahlian'
]);

$index = 1;
foreach ($acceptedPendaftar as $data){
    fputcsv($output, [
        $index++,
        $data['fullName'],
        $data['jabatanName'],
        $data['kelasName'],
        $data['sekor'],
        $data['keahlian']
    ]);
}

fclose($output);
exit;
?>