$(document).ready(function() {
    if (localStorage.getItem('token')) {
        $('.login-container').hide();
        $('#dashboard').show();
        loadData();
    }
    
    $('#loginForm').submit(function(event) {
        event.preventDefault();
        let email = $('#email').val();
        let password = $('#password').val();

        $.ajax({
            url: 'login.php', // Endpoint de autenticación
            type: 'POST',
            data: { email: email, password: password },
            success: function(response) {
                let data = JSON.parse(response);
                if (data.success) {
                    localStorage.setItem('token', data.token);
                    $('.login-container').hide();
                    $('#dashboard').show();
                    loadData();
                } else {
                    $('#errorMsg').text('Credenciales incorrectas');
                }
            }
        });
    });
});

function loadData() {
    $.ajax({
        url: 'https://inea.nayarit.gob.mx/apis/encuestas/encuestas.php', // Endpoint para obtener encuestas
        type: 'GET',
        success: function(response) {
            let data = JSON.parse(response);
            let tableBody = '';
            let labels = [];
            let avances = [];
            let dificultades = { 'Alta': 0, 'Media': 0, 'Baja': 0 };

            data.forEach(encuesta => {
                tableBody += `<tr>
                    <td>${encuesta.id}</td>
                    <td>${encuesta.nombre_alfabetizador}</td>
                    <td>${encuesta.nombre_educando}</td>
                    <td>${encuesta.calificacion_sesion}</td>
                    <td>${encuesta.avance_educando}</td>
                    <td>${encuesta.dificultad_educando}</td>
                    <td>${encuesta.fecha_registro}</td>
                </tr>`;

                labels.push(encuesta.nombre_educando);
                avances.push(encuesta.avance_educando === 'Buena' ? 80 : (encuesta.avance_educando === 'Regular' ? 50 : 30));
                dificultades[encuesta.dificultad_educando]++;
            });
            
            $('#encuestasTable tbody').html(tableBody);
            generateCharts(labels, avances, dificultades);
        }
    });
}

function generateCharts(labels, avances, dificultades) {
    new Chart(document.getElementById('chartAvance').getContext('2d'), {
        type: 'bar',
        data: {
            labels: labels,
            datasets: [{
                label: 'Avance Educando',
                data: avances,
                backgroundColor: 'blue'
            }]
        }
    });

    new Chart(document.getElementById('chartDificultad').getContext('2d'), {
        type: 'pie',
        data: {
            labels: Object.keys(dificultades),
            datasets: [{
                data: Object.values(dificultades),
                backgroundColor: ['red', 'yellow', 'green']
            }]
        }
    });
}