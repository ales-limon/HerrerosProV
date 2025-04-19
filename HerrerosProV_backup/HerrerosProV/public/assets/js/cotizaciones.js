function generarPDFCotizacion() {
    // Obtener el ID del proyecto de la URL
    const urlParams = new URLSearchParams(window.location.search);
    const idProyecto = urlParams.get('id');

    // Recolectar todos los datos necesarios
    const datos = {
        id_proyecto: idProyecto,
        id_cliente: document.getElementById('nombreCliente').dataset.idCliente,
        numero_cotizacion: document.getElementById('numeroCotizacion').value,
        nombre_cliente: document.getElementById('nombreCliente').value,
        nombre_proyecto: document.getElementById('nombreProyecto').value,
        valido_hasta: document.getElementById('validoHasta').value,
        notas: document.getElementById('notasCotizacion').value,
        condiciones: document.getElementById('condicionesCotizacion').value,
        subtotal: document.getElementById('subtotalCotizacion').textContent.replace('$', '').trim(),
        porcentaje_iva: document.getElementById('porcentajeIVA').textContent,
        iva: document.getElementById('ivaCotizacion').textContent.replace('$', '').trim(),
        total: document.getElementById('totalCotizacion').textContent.replace('$', '').trim(),
        piezas: []
    };

    // Recolectar datos de las piezas
    const filas = document.querySelectorAll('#tablaPiezasCotizacion tr');
    filas.forEach(fila => {
        const checkbox = fila.querySelector('input[type="checkbox"]');
        if (checkbox && checkbox.checked) {
            const pieza = {
                id_pieza: fila.dataset.idPieza,
                codigo: fila.dataset.codigo,
                tipo_pieza: fila.dataset.tipo,
                descripcion: fila.dataset.descripcion,
                cantidad: fila.querySelector('input[name="cantidad"]').value,
                precio_unitario: fila.querySelector('td:nth-child(6)').textContent.replace('$', '').trim(),
                subtotal: fila.querySelector('td:nth-child(7)').textContent.replace('$', '').trim(),
                incluir: true
            };
            datos.piezas.push(pieza);
        }
    });

    // Enviar los datos al servidor
    fetch('index.php?controller=taller&action=generarPDFCotizacion', {
        method: 'POST',
        headers: {
            'Content-Type': 'application/x-www-form-urlencoded',
        },
        body: 'datos=' + encodeURIComponent(JSON.stringify(datos))
    })
    .then(response => response.blob())
    .then(blob => {
        const url = window.URL.createObjectURL(blob);
        const a = document.createElement('a');
        a.href = url;
        a.download = 'Cotizacion_' + datos.numero_cotizacion + '.pdf';
        document.body.appendChild(a);
        a.click();
        window.URL.revokeObjectURL(url);
    })
    .catch(error => {
        console.error('Error:', error);
        Swal.fire({
            icon: 'error',
            title: 'Error',
            text: 'Error al generar la cotización'
        });
    });
} 