<?php
require_once "config.php";

require_once "config.php";


$sql = "SELECT COUNT(*) AS MensajesEnCola FROM [ICR450].[dbo].[ENTRADA]"; 
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

$mensajesEnCola = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $mensajesEnCola = $row['MensajesEnCola'];
}

sqlsrv_free_stmt($stmt);
$sql = "SELECT COUNT(nrotrans) AS ErroresFactura FROM fam450.dbo.TRANSAC WHERE CODSUC=5 AND CODCMP='FB' AND NROTRANSELIM IS NULL AND NUMERO=0";
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

$erroresFactura = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $erroresFactura = $row['ErroresFactura'];
}

sqlsrv_free_stmt($stmt);

$sql = "{CALL SinFacturarMeli}"; 
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

$facturasMeli = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $facturasMeli = $row[0] ?? $row['Column1'] ?? 0;
}

sqlsrv_free_stmt($stmt);

$sql = "{CALL SinFacturarVtex}"; 
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

$facturasVtex = 0;

while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $facturasVtex = $row[0] ?? $row['Column1'] ?? 0;
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

$response = array(
    "MensajesEnCola" => $mensajesEnCola,
    "ErroresFactura" => $erroresFactura,
    "FacturasMeli" => $facturasMeli,
    "FacturasVtex" => $facturasVtex
);

echo json_encode($response);
?>

