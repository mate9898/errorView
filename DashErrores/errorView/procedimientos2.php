<?php
require_once "config.php";

require_once "config.php";

// Query to count messages in ICR queue
$sql = "SELECT COUNT(*) AS MensajesEnCola FROM [ICR450].[dbo].[ENTRADA]"; 
$params = array();

$stmt = sqlsrv_query($conn, $sql, $params);

if ($stmt === false) {
    echo json_encode(array("error" => sqlsrv_errors()));
    exit();
}

// Initialize variable with default value
$mensajesEnCola = 0;

// Fetch the count result
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

// Initialize variable with default value
$erroresFactura = 0;

// Fetch the count result
while ($row = sqlsrv_fetch_array($stmt, SQLSRV_FETCH_ASSOC)) {
    $erroresFactura = $row['ErroresFactura'];
}

sqlsrv_free_stmt($stmt);
sqlsrv_close($conn);

// Prepare response
$response = array(
    "MensajesEnCola" => $mensajesEnCola,
    "ErroresFactura" => $erroresFactura
);

echo json_encode($response);
?>

