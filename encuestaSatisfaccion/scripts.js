// Función para mostrar el campo de tipo de dificultad si se selecciona 'Sí' en la dificultad de la sesión
function mostrarTipoDificultad() {
    const dificultadSesion = document.getElementById('dificultad_sesion').value;
    const tipoDificultad = document.getElementById('tipo_dificultad_container');
    
    // Si el valor es "Si", mostramos el campo de tipo de dificultad
    if (dificultadSesion === 'Si') {
        tipoDificultad.style.display = 'block';
    } else {
        tipoDificultad.style.display = 'none';
    }
}

// Asegurarse de que el evento de cambio esté conectado cuando el documento esté listo
document.addEventListener("DOMContentLoaded", function() {
    // Agregar el evento de cambio al select "dificultad_sesion"
    document.getElementById('dificultad_sesion').addEventListener('change', mostrarTipoDificultad);

    // Llamar a la función de mostrar/ocultar tipo de dificultad según el valor inicial
    mostrarTipoDificultad();
});
