
<?php
require_once "config.php";
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard de Errores</title>
    <link rel="stylesheet" href="styles.css">
    <link href="/imagenes/favicon.webp" rel="icon" type="image/svg" sizes="16x16">
   
</head>
<body>
<div class="horaLogo">
    <div class="logoInd">
        <a href="index.php"> <img class="logo" src="Logo Punto Deportivo Compacto.png" alt=""> </a>

    </div>
    <div class="clock" id="clock">00:00:00</div>

    <script>
        function updateClock() {
            const now = new Date();
            let hours = now.getHours().toString().padStart(2, '0');
            let minutes = now.getMinutes().toString().padStart(2, '0');
            let seconds = now.getSeconds().toString().padStart(2, '0');
            
            document.getElementById('clock').textContent = `${hours}:${minutes}:${seconds}`;
        }

        setInterval(updateClock, 1000); // actualizar cada segundo
        updateClock(); 
    </script>
    
</div>
    <div class="dashboard">
        <div class="card">
            <div class="error-title">Robots Balcony</div>
            <ul class="error-list">
                <li class="critical">() Robots demorados</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Robots VTEX</div>
            <ul class="error-list">
                <li class="critical">() Robots demorados</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Sincro Stock WEB</div>
            <ul class="error-list">
                <li class="critical">Error en actualización de stock</li>
            </ul>
        </div>
    </div>
    <div class="dashICR">
        <div class="card">
            <div class="error-title">Cola de Errores ICR</div>
            <ul class="error-list">
                <li class="warning">Mensajes en cola</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Estado Error ICR</div>
            <ul class="error-listICR2">
                <li class="warning">Demora en procesamiento</li>
            </ul>
        </div>
    </div>
    <div class="dashFacOnline">
        <div class="card">
            <div class="error-title">Facturas MELI</div>
            <ul class="error-list">
                <li class="info">X Sin facturar</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Facturas VTEX</div>
            <ul class="error-list">
                <li class="info">X Sin facturar</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Facturas Producteca</div>
            <ul class="error-list">
                <li class="info">X Sin facturar</li>
            </ul>
        </div>
    </div>
    <div class="dashFacElec">
        <div class="card">
            <div class="error-title">Estado Factura Electronica</div>
            <ul class="error-list">
                <li class="elec">Error</li>
            </ul>
        </div>
        <div class="card">
            <div class="error-title">Notas de Credito Pendientes</div>
            <ul class="error-list">
                <li class="cred">() Pendientes</li>
            </ul>
        </div>
    </div>    
    <script>
function getFieldName(index) {
    const fieldNames = {
        0: 'Facturas',
        1: 'RemitosIngreso',
        2: 'RemitosEgreso',
        3: 'CompInterno',
        4: 'OrdenPago',
        5: 'DepBancario',
        6: 'Recibo'
    };
    return fieldNames[index] || `Campo ${index}`;
}

async function fetchData() {
    try {
        const response = await fetch('procedimientos.php');
        const data = await response.json();
        
        console.log("Data received:", data);
        
        if (data.error) {
            console.error('Error en la consulta:', data.error);
            return;
        }

        const errorList = document.querySelector('.error-listICR2');
        errorList.innerHTML = '';
        let hasError = false;
        
    
        for (let i = 0; i <= 6; i++) {
            if (data[i] && Array.isArray(data[i])) {
                const numValue = Number(data[i][0]); 
                if (numValue !== 0) {
                    hasError = true;
                    const li = document.createElement('li');
                    li.className = 'warning';
                    li.textContent = `${getFieldName(i)}: ${numValue} error(es)`;
                    errorList.appendChild(li);
                }
            }
        }

        if (!hasError) {
            const li = document.createElement('li');
            li.textContent = "No hay errores registrados.";
            errorList.appendChild(li);
        }

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

async function fetchDataRBTS() { 
    try {
        const response = await fetch('procedimientos.php');
        const data = await response.json();

        if (data.error) {
            console.error('Error en la consulta:', data.error);
            return;
        }

        const cards = document.querySelectorAll('.dashboard .card');
        
        const robotTypes = {
            'Balcony': ['SSPB'],
            'VTEX': ['AEPA', 'APCM', 'IPVX', 'APVV', 'SV01', 'SV17', 'SV19'],
            'SincroStock': ['ITWB']
        };

        cards.forEach((card) => {
            const errorList = card.querySelector('.error-list');
            errorList.innerHTML = '';

            const title = card.querySelector('.error-title').textContent;
            let delayedCount = 0;
            let delayedCodes = [];

            if (data.Y_ProcesosAuto) {
                let robotCodes;
                if (title.includes('Balcony')) {
                    robotCodes = robotTypes.Balcony;
                } else if (title.includes('VTEX')) {
                    robotCodes = robotTypes.VTEX;
                } else if (title.includes('Sincro Stock')) {
                    robotCodes = robotTypes.SincroStock;
                }

                data.Y_ProcesosAuto.forEach(robot => {
                    if (robotCodes && robotCodes.includes(robot.Codigo) && robot.Estado !== 'Ok') {
                        delayedCount++;
                        delayedCodes.push(robot.Codigo);
                    }
                });
            }

            const li = document.createElement('li');
            if (delayedCount > 0) {
                li.className = 'critical';
                li.textContent = `${delayedCount} Robot${delayedCount > 1 ? 's' : ''} demorado${delayedCount > 1 ? 's' : ''} (${delayedCodes.join(', ')})`;
            } else {
                li.textContent = "No hay errores registrados.";
            }
            errorList.appendChild(li);
        });

    } catch (error) {
        console.error('Error fetching data:', error);
    }
}

async function fetchColaICR() {
    try {
        const response = await fetch('procedimientos2.php');
        const data = await response.json();
        
        console.log("Cola ICR data:", data);
        
        if (data.error) {
            console.error('Error en la consulta:', data.error);
            return;
        }

        const colaICRCard = document.querySelector('.dashICR .card:first-child');
        if (!colaICRCard) return;
        
        const errorList = colaICRCard.querySelector('.error-list');
        errorList.innerHTML = '';
        
        const li = document.createElement('li');
        
        if (data.MensajesEnCola > 0) {
            li.className = 'warning';
            li.textContent = `${data.MensajesEnCola} Mensajes en cola`;
        } else {
            li.textContent = "No hay mensajes en cola";
        }
        
        errorList.appendChild(li);

    } catch (error) {
        console.error('Error fetching cola ICR data:', error);
    }
}

async function fetchFacturaElectronica() {
    try {
        const response = await fetch('procedimientos2.php');
        const data = await response.json();
        
        console.log("Factura Electronica data:", data);
        
        if (data.error) {
            console.error('Error en la consulta:', data.error);
            return;
        }

        const facturaElecCard = document.querySelector('.dashFacElec .card:first-child');
        if (!facturaElecCard) return;
        
        const errorList = facturaElecCard.querySelector('.error-list');
        errorList.innerHTML = '';
        
        const li = document.createElement('li');
        
        if (data.ErroresFactura > 0) {
            li.className = 'elec';
            li.textContent = `Error: ${data.ErroresFactura} facturas con problemas`;
        } else {
            li.textContent = "No hay errores registrados.";
        }
        
        errorList.appendChild(li);

    } catch (error) {
        console.error('Error fetching factura electronica data:', error);
    }
}

fetchData();
fetchDataRBTS();
fetchColaICR();
fetchFacturaElectronica();


</script>
</script>

    
</body>
</html>




