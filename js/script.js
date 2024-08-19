
function activarSubmit($idArchivo) {
    if ($idArchivo == 'fileToUpload') {
        var elemento = document.getElementById('submit');
        elemento.hidden = false;
    } else if ($idArchivo == 'excelToUpload') {
        var elemento = document.getElementById('submit2');
        elemento.hidden = false;
    } else {
        var elemento = document.getElementById('subir');
        elemento.hidden = false;
    }
}

function desactivarSubmit($idArchivo) {
    if ($idArchivo == 'fileToUpload') {
        var elemento = document.getElementById('submit');
        elemento.hidden = true;
    } else if ($idArchivo == 'excelToUpload') {
        var elemento = document.getElementById('submit2');
        elemento.hidden = true;
    } else {
        var elemento = document.getElementById('subir');
        elemento.hidden = true;
    }
}

function mostrarrutaArchivo($idArchivo) {
    const archivoInput = document.getElementById($idArchivo);
    const rutaArchivo = archivoInput.files[0] ? archivoInput.files[0].name : 'Ningún archivo seleccionado';
    return rutaArchivo;
}

function comprobarArchivo($idArchivo) {
    // Define la URL del archivo en la carpeta "datos"

    const url = 'datos/' + mostrarrutaArchivo($idArchivo); // Reemplaza con la URL del archivo
    if (url != 'datos/Ningún archivo seleccionado') {
        fetch(url, {
            method: 'HEAD'
        }) // Usa el método HEAD para solo verificar la existencia
            .then(response => {
                if (response.ok) {
                    alert('El archivo ya existe.');
                    desactivarSubmit($idArchivo);
                    return false;
                } else {
                    activarSubmit($idArchivo);
                    return true;
                }
            })
            .catch(error => {
                console.error('Error:', error);
                alert('Error al comprobar el archivo.');
            });
    } else {
        alert('No se ha seleccionado el archivo.');
    }
}
function activarSelectReporte() {
    var seleccion = document.getElementById('tabla');
    /* var btnMostrar = document.getElementById('btnMostrar'); */
    var eleccion = seleccion.options[seleccion.selectedIndex].text;


    if (eleccion == 'Reporte1') {
        var elemento = document.getElementById('Reporte1');
        elemento.hidden = false;
        var elemento = document.getElementById('Reporte2');
        elemento.hidden = true;
        elemento.name = "Reporte2";
        /* btnMostrar.disabled = false; */
        var elemento = document.getElementById('ordenarVacio');
        elemento.hidden = true;
    } else if (eleccion == 'Reporte2') {
        var elemento = document.getElementById('Reporte2');
        elemento.hidden = false;
        var elemento = document.getElementById('Reporte1');
        elemento.hidden = true;
        elemento.name = "Reporte1";
        var elemento = document.getElementById('ordenarVacio');
        elemento.hidden = true;
    } else {
        var elemento = document.getElementById('Reporte1');
        elemento.hidden = true;
        var elemento = document.getElementById('Reporte2');
        elemento.hidden = true;
        var elemento = document.getElementById('ordenarVacio');
        elemento.hidden = false;
    }

}