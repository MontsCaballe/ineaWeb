let allData = [];
let currentChart = null;
const rowsPerPage = 5;
let currentPage = 1;
// Contraseña de acceso
const PASSWORD = "inea12345"; // Cambia esto según necesites

$gmx(document).ready(function () {
  $("#dashboard").show();
  loadData();

  $("#floatingButton").click(function () {
    // const inputPassword = document.getElementById("password").value;
    // const loginMessage = document.getElementById("login-message");

    // if (inputPassword === PASSWORD) {
    //   document.getElementById("login-container").style.display = "none";
    //   // document.getElementById("table-container").style.display = "block";

    //   let table = document.getElementById("encuestasTable");
    //   let wb = XLSX.utils.table_to_book(table, { sheet: "Figuras Operativas" });
    //   XLSX.writeFile(wb, "figurasOp.xlsx");
    // } else {
    //   loginMessage.textContent = "Contraseña incorrecta. Inténtalo de nuevo.";
    // }
    // Mostrar el modal al cargar la página
    document.getElementById("login-modal").style.display = "block";
    document.getElementById("modal-overlay").style.display = "block";
    // document.getElementById("main-content").classList.add("blur-background");
  });
  // 🔹 Filtrar datos en tiempo real
  $("#searchInput").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    let filteredData = allData.filter((item) =>
      item.cDesMunicipio.toLowerCase().includes(value)
    );
    renderTable(filteredData, 1, rowsPerPage);
    setupPagination(filteredData);
    updateCharts(filteredData);
  });
  $("#searchInputCZ").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    let filteredData = allData.filter((item) =>
      item.cDesCZ.toLowerCase().includes(value)
    );
    renderTable(filteredData, 1, rowsPerPage);
    setupPagination(filteredData);
    updateCharts(filteredData);
  });
  $("#searchInputP").on("keyup", function () {
    let value = $(this).val().toLowerCase();
    let filteredData = allData.filter((item) =>
      item.cIdenDepen.toLowerCase().includes(value)
    );
    renderTable(filteredData, 1, rowsPerPage);
    setupPagination(filteredData);
    updateCharts(filteredData);
  });

  $("#applyFilters").click(function () {
    applyFilters();
  });
});

function applyFiltersCZ() {
  let searchValue = $("#searchInputCZ").val().toLowerCase();
  let startDate = $("#startDate").val();
  let endDate = $("#endDate").val();

  let filteredData = allData.filter((item) => {
    let matchesSearch = item.cDesMunicipio.toLowerCase().includes(searchValue);
    let matchesDate = true;
    if (startDate && endDate) {
      matchesDate =
        item.fecha_registro >= startDate && item.fecha_registro <= endDate;
    }
    return matchesSearch && matchesDate;
  });

  renderTable(filteredData, 1, rowsPerPage);
  setupPagination(filteredData);
  updateCharts(filteredData);
}


function applyFiltersP() {
  let searchValue = $("#searchInputP").val().toLowerCase();
  let startDate = $("#startDate").val();
  let endDate = $("#endDate").val();

  let filteredData = allData.filter((item) => {
    let matchesSearch = item.cIdenDepen.toLowerCase().includes(searchValue);
    let matchesDate = true;
    if (startDate && endDate) {
      matchesDate =
        item.fecha_registro >= startDate && item.fecha_registro <= endDate;
    }
    return matchesSearch && matchesDate;
  });

  renderTable(filteredData, 1, rowsPerPage);
  setupPagination(filteredData);
  updateCharts(filteredData);
}

function applyFilters() {
  let searchValue = $("#searchInput").val().toLowerCase();
  let startDate = $("#startDate").val();
  let endDate = $("#endDate").val();

  let filteredData = allData.filter((item) => {
    let matchesSearch = item.cDesMunicipio.toLowerCase().includes(searchValue);
    let matchesDate = true;
    if (startDate && endDate) {
      matchesDate =
        item.fecha_registro >= startDate && item.fecha_registro <= endDate;
    }
    return matchesSearch && matchesDate;
  });

  renderTable(filteredData, 1, rowsPerPage);
  setupPagination(filteredData);
  updateCharts(filteredData);
}

function loadData() {
  $.ajax({
    url: "https://inea.nayarit.gob.mx/apis/figuras/figuras.php",
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
          label: "Voluntarios 📈",
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
    if (encuesta.iCveIE !== "iCveIE") {
      tableBody += `<tr>
      <td style="display: none;">${encuesta.iCveIE}</td>
      <td style="display: none;">${encuesta.cPaterno} ${encuesta.cMaterno} ${encuesta.cNombre}</td>
      <td style="display: none;">${encuesta.cDesRolFO}</td>
      <td>${encuesta.cIdenSubPro}</td>
      <td>${encuesta.cIdenDepen}</td>
      <td>${encuesta.cDesMunicipio}</td>
      <td>${encuesta.cDesCZ}</td>
      <td>${encuesta.fRegistro}</td>
  </tr>`;
    }
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
    renderTable(data, currentPage, rowsPerPage);
  });
}

function updateCharts(data) {
  // Contador de registros por fecha
  let registrosPorFecha = {};

  // Recorrer los datos y contar cuántos registros hay por fecha
  data.forEach((item) => {
      let fecha = item.fRegistro.split(" ")[0]; // Tomar solo la fecha (sin la hora)
      if (registrosPorFecha[fecha]) {
          registrosPorFecha[fecha] += 1; // Sumar 1 si ya existe la fecha
      } else {
          registrosPorFecha[fecha] = 1; // Inicializar si no existe
      }
  });

  // Extraer las etiquetas (fechas) y valores (cantidad de registros)
  let labels = Object.keys(registrosPorFecha);
  let avances = Object.values(registrosPorFecha);

  // Destruir el gráfico anterior si existe
  if (currentChart) {
      currentChart.destroy();
  }

  // Crear el gráfico de líneas con los datos corregidos
  let ctx = document.getElementById("chartGeneral").getContext("2d");
  currentChart = new Chart(ctx, {
      type: "line",
      data: {
          labels: labels,
          datasets: [
              {
                  label: "Cantidad de registros",
                  data: avances,
                  borderColor: "blue",
                  backgroundColor: "rgba(0, 123, 255, 0.3)",
                  fill: true,
                  tension: 0.1,
              },
          ],
      },
      options: {
          responsive: true,
          scales: {
              y: {
                  beginAtZero: true,
                  title: {
                      display: true,
                      text: "Cantidad de fRegistro",
                  },
              },
              x: {
                  title: {
                      display: true,
                      text: "Fechas",
                  },
              },
          },
      },
  });
}

// // 🔹 Función para actualizar gráficos después de cargar datos o filtrar
// function updateCharts(data) {
//   let labels = data.map((item) => item.fRegistro.split(" ")[0]);
//   let avances = data.map((item) =>
//     item.avance_educando === "Buena"
//       ? 80
//       : item.avance_educando === "Regular"
//       ? 50
//       : 30
//   );

//   if (currentChart) {
//     currentChart.destroy();
//   }

//   let ctx = document.getElementById("chartGeneral").getContext("2d");
//   currentChart = new Chart(ctx, {
//     type: "line",
//     data: {
//       labels: labels,
//       datasets: [
//         {
//           label: "Calificación",
//           data: avances,
//           borderColor: "blue",
//           fill: false,
//           tension: 0.1,
//         },
//       ],
//     },
//   });
// }
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

function checkLogin() {
  const inputPassword = document.getElementById("password").value;
  const loginMessage = document.getElementById("login-message");

  if (inputPassword === PASSWORD) {
    // Ocultar el modal y restaurar el fondo
    document.getElementById("login-modal").style.display = "none";
    document.getElementById("modal-overlay").style.display = "none";
    let table = document.getElementById("encuestasTable");
    let wb = XLSX.utils.table_to_book(table, { sheet: "Figuras Operativas" });
    XLSX.writeFile(wb, "figurasOp.xlsx");
  } else {
    loginMessage.textContent = "Contraseña incorrecta. Inténtalo de nuevo.";
  }
}
