<?php
require_once "config.php";

$sql = "{CALL EstadoErrorICR}"; 
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

// Initialize variables with default values
$FacICR = 0;
$RiICR = 0;
$ReICR = 0;
$CmpInt = 0;
$OpICR = 0;
$DepBanICR = 0;
$RcICR = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $FacICR = $row['Facturas'];
    $RiICR = $row['RemitosIngreso'];
    $ReICR = $row['RemitosEgreso'];
    $CmpInt = $row['CompInterno'];
    $OpICR = $row['OrdenPago'];
    $DepBanICR = $row['DepBancario'];
    $RcICR = $row['Recibo'];
}

sqlsrv_free_stmt($stmt);


$sql = "{CALL Y_ProcesosAuto}"; 
$params = array();
$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

$dataRBTS = array();
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    
    $codRBTS[] = $row['Codigo'];
    $estadRBTS[] = $row['Estado'];
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

$response = array(
    "EstadoErrorICR" => array($FacICR, $RiICR, $ReICR, $CmpInt, $OpICR, $DepBanICR, $RcICR),
    "0" => array($FacICR),
    "1" => array($RiICR),
    "2" => array($ReICR),
    "3" => array($CmpInt),
    "4" => array($OpICR),
    "5" => array($DepBanICR),
    "6" => array($RcICR),
    "Y_ProcesosAuto" => $codRBTS, $estadRBTS
);

echo json_encode($response);
?>