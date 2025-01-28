// Función para obtener los Detalle sde la API según el tipo de reporte
function getData(reporte) {
  return $.ajax({
    url: `https://inea.nayarit.gob.mx/api/getPlanteles?reporte=${reporte}`, // Cambia esto por la URL de tu API
    method: "GET",
    dataType: "json",
  });
}
// Función para actualizar los gráficos y la tabla
function updateChartsAndTable(response) {
  const barData = response.barData;
  const lineData = response.lineData;

  // Actualiza el gráfico de barras
  const barChart = Chart.getChart("barChart");
  barChart.data.labels = barData.labels;
  barChart.data.datasets[0].data = barData.values;
  barChart.update();

  // Actualiza el gráfico de líneas
  const lineChart = Chart.getChart("lineChart");
  lineChart.data.labels = lineData.labels;
  lineChart.data.datasets[0].data = lineData.values;
  lineChart.update();

  // Actualiza la tabla
  updateTable(barData.labels, barData.values);
}

// Función para manejar los errores y mostrar gráficos y tabla con valores en 0
function showEmptyChartsAndTable() {
  const emptyLabels = ["Enero", "Febrero", "Marzo", "Abril", "Mayo"];
  const emptyValues = [0, 0, 0, 0, 0];

  // Actualizar gráfico de barras con valores en 0
  const barChart = Chart.getChart("barChart");
  barChart.data.labels = emptyLabels;
  barChart.data.datasets[0].data = emptyValues;
  barChart.update();

  // Actualizar gráfico de líneas con valores en 0
  const lineChart = Chart.getChart("lineChart");
  lineChart.data.labels = emptyLabels;
  lineChart.data.datasets[0].data = emptyValues;
  lineChart.update();

  // Actualizar la tabla con valores en 0
  updateTable(emptyLabels, emptyValues);
}

// Función para actualizar la tabla con los datos
function updateTable(labels, values) {
  const tableBody = $("#dataTable tbody");
  tableBody.empty(); // Vaciar la tabla antes de rellenar

  labels.forEach((label, index) => {
    const row = `<tr>
                        <td>${label}</td>
                        <td>${values[index]}</td>
                     </tr>`;
    tableBody.append(row);
  });
}

// Inicializa los gráficos vacíos con valores en 0 por defecto
const barCtx = document.getElementById("barChart").getContext("2d");
const barChart = new Chart(barCtx, {
  type: "bar",
  data: {
    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo"], // Etiquetas predeterminadas
    datasets: [
      {
        label: "Valores",
        data: [0, 0, 0, 0, 0], // Valores predeterminados en 0
        backgroundColor: "rgba(75, 192, 192, 0.2)",
        borderColor: "rgba(75, 192, 192, 1)",
        borderWidth: 1,
      },
    ],
  },
  options: {
    responsive: true,
    scales: {
      y: {
        beginAtZero: true,
      },
    },
  },
});

const lineCtx = document.getElementById("lineChart").getContext("2d");
const lineChart = new Chart(lineCtx, {
  type: "line",
  data: {
    labels: ["Enero", "Febrero", "Marzo", "Abril", "Mayo"], // Etiquetas predeterminadas
    datasets: [
      {
        label: "Tendencia de datos",
        data: [0, 0, 0, 0, 0], // Valores predeterminados en 0
        fill: false,
        borderColor: "#36A2EB", // Color de la línea
        tension: 0.1, // Suaviza la curva de la línea
      },
    ],
  },
  options: {
    responsive: true,
    plugins: {
      legend: {
        display: true, // Muestra la leyenda
        position: "top",
      },
    },
  },
});

// Manejadores de eventos para los enlaces del menú
$("#reporte5").on("click", function () {
  // Actualiza el <h1> con el texto del enlace clicado
  const newTitle = $(this).text(); // Toma el texto del enlace
  $("h1").text(`Dashboard de ${newTitle}`); // Actualiza el <h1> dinámicamente
  $("#tableHeader").text(`Datos de ${newTitle}`); // Actualiza el encabezado de la tabla dinámicamente

  // Llama a la API para obtener los datos y actualizar los gráficos
  getData("finanzas")
    .done(updateChartsAndTable) // Si la solicitud es exitosa
    .fail(showEmptyChartsAndTable); // Si la solicitud falla, se muestran gráficos y tabla con valores en 0
});

$("#reporte4").on("click", function () {
  // Actualiza el <h1> con el texto del enlace clicado
  const newTitle = $(this).text(); // Toma el texto del enlace
  $("h1").text(`Dashboard de ${newTitle}`); // Actualiza el <h1> dinámicamente
  $("#tableHeader").text(`Detalle  ${newTitle}`); // Actualiza el encabezado de la tabla dinámicamente

  // Llama a la API para obtener los datos y actualizar los gráficos
  getData("finanzas")
    .done(updateChartsAndTable) // Si la solicitud es exitosa
    .fail(showEmptyChartsAndTable); // Si la solicitud falla, se muestran gráficos y tabla con valores en 0
});
$("#reporte3").on("click", function () {
  // Actualiza el <h1> con el texto del enlace clicado
  const newTitle = $(this).text(); // Toma el texto del enlace
  $("h1").text(`Dashboard de ${newTitle}`); // Actualiza el <h1> dinámicamente
  $("#tableHeader").text(`Detalle  ${newTitle}`); // Actualiza el encabezado de la tabla dinámicamente

  // Llama a la API para obtener los datos y actualizar los gráficos
  getData("finanzas")
    .done(updateChartsAndTable) // Si la solicitud es exitosa
    .fail(showEmptyChartsAndTable); // Si la solicitud falla, se muestran gráficos y tabla con valores en 0
});
$("#reporte2").on("click", function () {
  // Actualiza el <h1> con el texto del enlace clicado
  const newTitle = $(this).text(); // Toma el texto del enlace
  $("h1").text(`Dashboard de ${newTitle}`); // Actualiza el <h1> dinámicamente
  $("#tableHeader").text(`Detalle  ${newTitle}`); // Actualiza el encabezado de la tabla dinámicamente

  // Llama a la API para obtener los datos y actualizar los gráficos
  getData("finanzas")
    .done(updateChartsAndTable) // Si la solicitud es exitosa
    .fail(showEmptyChartsAndTable); // Si la solicitud falla, se muestran gráficos y tabla con valores en 0
});
$("#reporte1").on("click", function () {
  // Actualiza el <h1> con el texto del enlace clicado
  const newTitle = $(this).text(); // Toma el texto del enlace
  $("h1").text(`Dashboard de ${newTitle}`); // Actualiza el <h1> dinámicamente
  $("#tableHeader").text(`Detalle  ${newTitle}`); // Actualiza el encabezado de la tabla dinámicamente

  // Llama a la API para obtener los datos y actualizar los gráficos
  getData("finanzas")
    .done(updateChartsAndTable) // Si la solicitud es exitosa
    .fail(showEmptyChartsAndTable); // Si la solicitud falla, se muestran gráficos y tabla con valores en 0
});

// Muestra los gráficos y tabla con valores en 0 al cargar la página si no hay datos
showEmptyChartsAndTable();
