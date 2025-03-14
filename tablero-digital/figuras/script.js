let allData = [];
let currentChart = null;
const rowsPerPage = 5;
let currentPage = 1;

$gmx(document).ready(function () {
  $("#dashboard").show();
  loadData();

  $("#floatingButton").click(function () {
    let table = document.getElementById("encuestasTable");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Encuestas" });
    XLSX.writeFile(wb, "encuestas.xlsx");
  });
  // 🔹 Filtrar datos en tiempo real
  $("#searchInput").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    let filteredData = allData.filter((item) =>
      item.nombre_alfabetizador.toLowerCase().includes(value)
    );
    renderTable(filteredData, 1, rowsPerPage);
    setupPagination(filteredData);
    updateCharts(filteredData);
  });

  $("#applyFilters").click(function () {
    applyFilters();
  });
});

function applyFilters() {
    let searchValue = $('#searchInput').val().toLowerCase();
    let startDate = $('#startDate').val();
    let endDate = $('#endDate').val();

    let filteredData = allData.filter(item => {
        let matchesSearch = item.nombre_alfabetizador.toLowerCase().includes(searchValue);
        let matchesDate = true;
        if (startDate && endDate) {
            matchesDate = item.fecha_registro >= startDate && item.fecha_registro <= endDate;
        }
        return matchesSearch && matchesDate;
    });
    
    renderTable(filteredData, 1,rowsPerPage);
    setupPagination(filteredData);
    updateCharts(filteredData);
}

function loadData() {
  $.ajax({
    url: "https://inea.nayarit.gob.mx/apis/encuestas/encuestas.php",
    type: "GET",
    success: function (response) {
      console.log("Respuesta de la API:", response); // <-- Depuración

      try {
        allData = JSON.parse(response); // Convertir a JSON
        console.log("Datos parseados:", allData); // <-- Verificar si se parseó correctamente
        renderTable(allData, currentPage, rowsPerPage);
        setupPagination(allData);
        updateCharts(allData);
      } catch (error) {
        console.error("Error al convertir JSON:", error);
      }
    },
    error: function (xhr, status, error) {
      console.error("Error en la petición AJAX:", status, error);
    },
  });
}

function generateCharts(labels, avances, dificultades) {
  new Chart(document.getElementById("chartAvance").getContext("2d"), {
    type: "bar",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Avance Educando",
          data: avances,
          backgroundColor: "blue",
        },
      ],
    },
  });

  new Chart(document.getElementById("chartDificultad").getContext("2d"), {
    type: "pie",
    data: {
      labels: Object.keys(dificultades),
      datasets: [
        {
          data: Object.values(dificultades),
          backgroundColor: ["red", "yellow", "green"],
        },
      ],
    },
  });
}

function generateLineChart(labels, avances) {
  new Chart(document.getElementById("chartGeneral").getContext("2d"), {
    type: "line",
    data: {
      labels: labels,
      datasets: [
        {
          label: "Avance Educando 📈",
          data: avances,
          borderColor: "blue",
          fill: false,
          tension: 0.1,
        },
      ],
    },
  });
}

function renderTable(data, page, rowsPerPage) {
  let start = (page - 1) * rowsPerPage;
  let end = start + rowsPerPage;
  let tableData = data.slice(start, end);
  let tableBody = "";
  tableData.forEach((encuesta) => {
    tableBody += `<tr>
                    <td style="display: none;">${encuesta.id}</td>
                    <td style="display: none;">${encuesta.nombre_alfabetizador}</td>
                    <td style="display: none;">${encuesta.nombre_educando}</td>
                    <td>${encuesta.calificacion_sesion}</td>
                    <td>${encuesta.avance_educando}</td>
                    <td>${encuesta.dificultad_educando}</td>
                    <td>${encuesta.fecha_registro}</td>
                </tr>`;
  });
  $("#encuestasTable tbody").html(tableBody);
}

// 🔹 Configurar paginación dinámica
function setupPagination(data) {
  let pageCount = Math.ceil(data.length / rowsPerPage);
  $("#pagination").html("");

  for (let i = 1; i <= pageCount; i++) {
    $("#pagination").append(
      `<button class="page-btn" data-page="${i}">${i}</button>`
    );
  }

  $(".page-btn").click(function () {
    currentPage = $(this).data("page");
    renderTable(data, currentPage,rowsPerPage);
  });
}
// 🔹 Función para actualizar gráficos después de cargar datos o filtrar
function updateCharts(data) {
    let labels = data.map(item => item.fecha_registro.split(" ")[0]);
    let avances = data.map(item => item.avance_educando === 'Buena' ? 80 : (item.avance_educando === 'Regular' ? 50 : 30));
    
    if (currentChart) {
        currentChart.destroy();
    }
    
    let ctx = document.getElementById('chartGeneral').getContext('2d');
    currentChart = new Chart(ctx, {
        type: 'line',
        data: {
            labels: labels,
            datasets: [{
                label: 'Calificación',
                data: avances,
                borderColor: 'blue',
                fill: false,
                tension: 0.1
            }]
        }
    });
}
// function updateCharts(data) {
//   let labels = [];
//   let avances = [];
//   let dificultades = { Alta: 0, Media: 0, Baja: 0 };

//   data.forEach((encuesta) => {
//     labels.push(encuesta.fecha_registro);
//     avances.push(
//       encuesta.avance_educando === "Buena"
//         ? 80
//         : encuesta.avance_educando === "Regular"
//         ? 50
//         : 30
//     );
//     dificultades[encuesta.dificultad_educando]++;
//   });

// //   generateCharts(labels, avances, dificultades);
// //   generateLineChart(labels, avances);
// }
