<?php

$serverName = "puntocentral.dyndns.org,43034";
$uid = "DATA";
$pwd = "6,02x1023";

$connectionInfo = array("UID"=>$uid,
                        "PWD"=>$pwd,
                        "Database"=>"FAM450",
                        "Characterset"=>"UTF-8");

$conn = sqlsrv_connect( $serverName, $connectionInfo);

if( $conn === false ) {
    echo "Conexión no se pudo establecer.<br />";
    die( print_r( sqlsrv_errors(), true));
}
?>