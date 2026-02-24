🖥️ Operations Error Dashboard – Real-Time System Monitor
Dashboard de monitoreo operativo en tiempo real para entornos retail con integración a SQL Server. Visualiza el estado de robots de sincronización, colas de mensajes, errores ICR y facturación electrónica desde una única pantalla, actualizándose automáticamente cada 15 segundos sin recargar la página.

📸 ¿Qué monitorea?
PanelDescripción🤖 Robots BalconyEstado del robot SSPB (sincronización con Balcony)🛒 Robots VTEXEstado de robots AEPA, APCM, IPVX, APVV, SV01, SV17, SV19📦 Sincro Stock WEBEstado del robot ITWB de sincronización de stock web📨 Cola de Errores ICRCantidad de mensajes pendientes en la cola ICR450.dbo.ENTRADA⚠️ Estado Error ICRErrores por tipo: Facturas, Remitos, Comprobantes Internos, Órdenes de Pago, Depósitos, Recibos🧾 Factura ElectrónicaFacturas con número 0 pendientes de procesar en FAM450

🚀 Features

Actualización automática -
Indicadores visuales por severidad: critical, warning, info, elec -
Reloj en tiempo real en el header - 
Arquitectura desacoplada: lógica de datos en endpoints PHP separados (procedimientos.php, procedimientos2.php) -
Consumo de stored procedures SQL Server vía sqlsrv.


🛠️ Stack

Backend: PHP 8+ con extensión sqlsrv
Base de datos: SQL Server — bases FAM450 e ICR450
Frontend: HTML5, CSS3 (styles.css), JavaScript vanilla (fetch + setInterval)
Stored Procedures usados: EstadoErrorICR, Y_ProcesosAuto


📁 Estructura del proyecto
/
├── index.php            # Dashboard principal (UI)
├── procedimientos.php   # Endpoint: robots + errores ICR
├── procedimientos2.php  # Endpoint: cola ICR + factura electrónica
├── config.php           # Conexión a SQL Server
├── styles.css           # Estilos del dashboard
└── imagenes/
    └── favicon.webp

⚙️ Requisitos

PHP 8.0+
Extensión php_sqlsrv instalada y habilitada
Acceso a SQL Server con las bases FAM450 e ICR450
Stored procedures EstadoErrorICR y Y_ProcesosAuto creados en el servidor


🔄 Flujo de datos
index.php
  ├── fetch('procedimientos.php')
  │     ├── CALL EstadoErrorICR  →  errores por tipo de comprobante
  │     └── CALL Y_ProcesosAuto  →  estado de robots por código
  └── fetch('procedimientos2.php')
        ├── SELECT COUNT(*) FROM ICR450.dbo.ENTRADA  →  cola de mensajes
        └── SELECT COUNT(*) FROM FAM450.dbo.TRANSAC  →  facturas sin número

📌 Notas

Los robots se clasifican internamente por tipo (Balcony, VTEX, SincroStock) mediante sus códigos. Cualquier estado distinto de "Ok" se reporta como demorado en el panel correspondiente.
