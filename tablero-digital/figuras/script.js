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
        generateAccordionTable(allData);

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
                  label: "Altas por día",
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
                      text: "Altas Figuras",
                  },
              },
              x: {
                  title: {
                      display: true,
                      text: "Evolución por Fecha",
                  },
              },
          },
      },
  });
}


function checkLogin() {
  const inputPassword = document.getElementById("password").value;
  const loginMessage = document.getElementById("login-message");

  if (inputPassword === PASSWORD) {
    // Ocultar el modal y restaurar el fondo
    document.getElementById("login-modal").style.display = "none";
    document.getElementById("modal-overlay").style.display = "none";
    let table = document.getElementById("encuestasTable");
    // let wb = XLSX.utils.table_to_book(table, { sheet: "Figuras Operativas" });

    exportTableToExcel(table,"figurasOp.xlsx");
    // XLSX.writeFile(wb, "figurasOp.xlsx");
  } else {
    loginMessage.textContent = "Contraseña incorrecta. Inténtalo de nuevo.";
  }
}

function exportTableToExcel(tableId, fileName = "figurasOp.xlsx") {
  // Obtener la tabla original por ID
  let originalTable = document.getElementById("encuestasTable");

  if (!originalTable) {
      console.error("⚠ No se encontró la tabla con ID:", "encuestasTable");
      return;
  }

  // Obtener los datos filtrados (si existen filtros)
  let filteredData = allData; // Asumimos que allData contiene todos los datos

  if ($("#searchInput").length) {
      let searchValue = $("#searchInput").val().toUpperCase();
      let startDate = $("#startDate").val();
      let endDate = $("#endDate").val();

      filteredData = allData.filter((item) => {
          let matchesSearch = item.cDesMunicipio.toLowerCase().includes(searchValue);
          let matchesDate = true;

          if (startDate && endDate) {
              matchesDate = item.fRegistro >= startDate && item.fRegistro <= endDate;
          }
          return matchesSearch && matchesDate;
      });
  }

  // Crear una tabla temporal para la exportación
  let tempTable = document.createElement("table");
  let thead = document.createElement("thead");
  let tbody = document.createElement("tbody");

  // Clonar los encabezados de la tabla original
  let headerRow = originalTable.querySelector("thead tr").cloneNode(true);
  thead.appendChild(headerRow);
  tempTable.appendChild(thead);

  // Insertar los registros filtrados en la tabla temporal
  filteredData.forEach((item) => {
      let row = document.createElement("tr");

      row.innerHTML = `
          <td>${item.iCveIE}</td>
          <td>${item.cPaterno} ${item.cMaterno} ${item.cNombre}</td>
          <td>${item.cDesRolFO}</td>
          <td>${item.cIdenSubPro}</td>
          <td>${item.cIdenDepen}</td>
          <td>${item.cDesMunicipio}</td>
          <td>${item.cDesCZ}</td>
          <td>${item.fRegistro}</td>
      `;

      tbody.appendChild(row);
  });

  tempTable.appendChild(tbody);

  // Convertir la tabla a Excel y descargar
  let wb = XLSX.utils.table_to_book(tempTable, { sheet: "Datos Exportados" });
  XLSX.writeFile(wb, fileName);
}

function generateAccordionTable(data) {
  let summary = {};

  // 🔹 Agrupar por Estado y luego por Plantel
  data.forEach(item => {
    console.log(item);
      let estado = item.cDesCZ; // Estado
      let plantel = item.cIdenDepen; // Plantel

      if (!summary[estado]) {
          summary[estado] = {};
      }
      if (!summary[estado][plantel]) {
          summary[estado][plantel] = 0;
      }
      summary[estado][plantel] += 1; // Contar registros
  });

  // 🔹 Generar HTML de la tabla dinámica con acordeón
  let tableHTML = `<table border="1" class="summary-table">
                      <thead>
                          <tr>
                              <th>Coordinación de Zona</th>
                              <th>Plantel</th>
                              <th>Total Figuras</th>
                          </tr>
                      </thead>
                      <tbody>`;

  Object.keys(summary).forEach((estado, estadoIndex) => {
      let estadoTotal = 0;
      let estadoRowSpan = Object.keys(summary[estado]).length;

      tableHTML += `<tr class="estado-row" onclick="togglePlanteles('${estadoIndex}')">
                      <td colspan="2"><strong>▶ ${estado}</strong></td>
                      <td><strong>${Object.values(summary[estado]).reduce((a, b) => a + b, 0)}</strong></td>
                    </tr>`;

      // 🔹 Planteles ocultos por defecto
      Object.keys(summary[estado]).forEach((plantel) => {
          let plantelTotal = summary[estado][plantel];

          tableHTML += `<tr class="plantel-row plantel-${estadoIndex}" style="display: none;">
                          <td></td>
                          <td>${plantel}</td>
                          <td>${plantelTotal}</td>
                        </tr>`;
      });
  });

  tableHTML += `</tbody></table>`;

  // 🔹 Insertar la tabla en el HTML
  document.getElementById("summaryContainer").innerHTML = tableHTML;
}

// 🔹 Función para expandir/colapsar los planteles al hacer clic en el estado
function togglePlanteles(estadoIndex) {
  let planteles = document.querySelectorAll(`.plantel-${estadoIndex}`);
  planteles.forEach(row => {
      row.style.display = (row.style.display === "none") ? "table-row" : "none";
  });
}