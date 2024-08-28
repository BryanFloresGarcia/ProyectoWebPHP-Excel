
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
    let contador = 0;
    let nombre = "";
    const select = document.getElementById('tabla');
    var div01 = document.getElementById('content1');
    var eleccionTabla = select.options[select.selectedIndex].text;
    /* const opcionesArray = []; */
    var elementoVacio = document.getElementById('ordenarVacio');
    // Recorrer las opciones usando for...of
    for (const option of select.options) {
        /* opcionesArray.push(option.value); */
        if (option.value !== "Seleccione_una_tabla") {
            nombre = option.value + "-" + contador;
            var filtro = document.getElementById(nombre);
            var fecha = filtro.options[filtro.selectedIndex].text;
            nomProyecto = eleccionTabla + "-" + fecha;
            var labelProyecto = document.getElementById("L" + nomProyecto);
            /* document.getElementById(nombre).addEventListener('change', function (event) {
                activarSelectReporte();
                }); */
            if (filtro) {
                /* alert(nombre); */
                if (nombre == eleccionTabla + "-" + contador) {
                    filtro.hidden = false;
                    elementoVacio.hidden = true;
                    var proyecto = document.getElementById(nomProyecto);
                    if (eleccionTabla !== "DEPOSITOS" && eleccionTabla !== "REGISTROS_RCE") {
                        alteraInputsReporte(select, fecha, proyecto.options[proyecto.selectedIndex].text);
                    }
                    
                        if (proyecto) {
                            for (const opcFecha of filtro.options) {
                                var proyectoFecha = document.getElementById(option.value + "-" + opcFecha.value);
                                proyectoFecha.hidden = true;
                            }
                            if (div01.style.display == 'none' && eleccionTabla !== "REGISTROS_RCE" && eleccionTabla !== "DEPOSITOS") {
                                labelProyecto.hidden = false;
                                labelProyecto.innerHTML = "Proyecto: ";
                                proyecto.hidden = false;
                            } else {
                                labelProyecto.hidden = true;
                            }
                        }
                    
                } else {
                    filtro.hidden = true;/* console.log(nombre);console.log(eleccionTabla); */
                }
            }
            if (eleccionTabla !== "Seleccione una tabla") {
                if (filtro) {
                    for (const opcFecha of filtro.options) {
                        /* console.log(eleccionTabla); */
                    /* console.log(option.value + "-" + opcFecha.value); */
                    var proyectoFecha = document.getElementById(option.value + "-" + opcFecha.value);
                    var labelProyecto = document.getElementById("L" + option.value + "-" + opcFecha.value);
                    if (nomProyecto == option.value + "-" + opcFecha.value) {
                        /* console.log(option.value + "-" + opcFecha.value); */
                    } else {
                        proyectoFecha.hidden = true;
                        labelProyecto.hidden = true;
                    }
                    }
                }
            }
            
            contador++;
        }
    }


    if (eleccionTabla == "Seleccione una tabla") {
        elementoVacio.hidden = false;
    }

    function alteraInputsReporte(RTabla, Rorden, RProyecto) {
        var inputTabla = document.getElementById('RTabla');
        inputTabla.value = RTabla.options[RTabla.selectedIndex].text + "";
        var inputRorden = document.getElementById('Rorden');
        inputRorden.value = Rorden;
        var inputRProyecto = document.getElementById('RProyecto');
        inputRProyecto.value = RProyecto;
    }
}

function validarNumero(input) {
    // Permitir solo números
    input.value = input.value.replace(/[^0-9]/g, '');
}

function activaDesactivaPestana(enlace1,enlace2,div1,div2,display) {
    var botonera = document.getElementById('botonera');
    var btnMostrar = document.getElementById('btnMostrar');
    var btnRojo = document.getElementById('btnRojo');
    var btnGris = document.getElementById('btnGris');
    event.preventDefault();
    // Ocultar el div
    div1.style.display = display;
    enlace1.style.background = '#007730';
    div2.style.display = 'none';
    enlace2.style.background = 'none';
    if (display == "flex") {
        botonera.innerHTML = "Seleccione las opciones";
        btnMostrar.hidden = true;
        btnRojo.hidden = true;
        btnGris.hidden = true;
    }else{
        botonera.innerHTML = "Visualización";
        btnMostrar.hidden = false;
        btnRojo.hidden = false;
        btnGris.hidden = false;
    }
    
    activarSelectReporte();
}

function activaDesactivaDiv(enlace1,enlace2) {
    var div1 = document.getElementById('content1');
    var div2 = document.getElementById('content2');
    if (div1.style.display !== 'none') {
        enlace1.style.background = '#007730';
    }
    if (div2.style.display !== 'none') {
        enlace2.style.background = '#007730';
    }
}

function validarEntrada(event) {
    const input = event.target;
    const value = parseInt(input.value, 10);

    if (value < 0 || value > 31 || isNaN(value)) {
        alert('Número fuera del rango permitido. Ingrese un valor entre 0 y 31.');
        input.value = '';
    }
}

function validarFormulario(event) {
    const select = document.getElementById('tabla');
    const monto = document.getElementById('RMonto');
    if (select.value === 'Seleccione_una_tabla' || select.value === 'DEPOSITOS' || select.value === 'REGISTROS_RCE' || select.value === 'PROPUESTA') {
        alert('Por favor, seleccione una Tabla válida.');
        event.preventDefault();// Evita el envío del formulario
    }else if (monto.value <= 0) {
        alert('Por favor, ingresa un monto válido.');
        event.preventDefault();// Evita el envío del formulario
    }
}