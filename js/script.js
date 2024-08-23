
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
        desactivarSubmit($idArchivo);
    }
}
function activarSelectReporte() {
    var seleccion = document.getElementById('tabla');
    /* var btnMostrar = document.getElementById('btnMostrar'); */
    var eleccion = seleccion.options[seleccion.selectedIndex].text;
    let contador = 0;
    for (let i = 1; i < 9; i++) {
        nombre = "Reporte";
        nombre = nombre.concat(i);
        var elemento = document.getElementById(nombre);
        var elementoVacio = document.getElementById('ordenarVacio');
        
        if(elemento) {
            if (eleccion == nombre) {
                var elemento = document.getElementById(nombre);
                elemento.hidden = false;
                elemento.name = nombre;
                var elemento = document.getElementById('ordenarVacio');
                elemento.hidden = true;
                contador++;
            } else {
                var elemento = document.getElementById(nombre);
                elemento.hidden = true;
            }
        }else {
            if (contador == 0) {
                elementoVacio.hidden = false;
                break;
            }else{
                elementoVacio.hidden = true;
                break;
            }
                
        }
        
    }

}