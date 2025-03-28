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
    url: "https://inea.nayarit.gob.mx/apis/educandos/educandos.php",
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
          label: "Educandos 📈",
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
    if (encuesta.Id !== "Id") {
      tableBody += `<tr>
      <td style="display: none;">${encuesta.Id}</td>
      <td style="display: none;">${encuesta.Apellido_Paterno} ${encuesta.Apellido_Materno} ${encuesta.Nombre}</td>
      <td style="display: none;">${encuesta.eMail}</td>
      <td>${encuesta.Clave_Nivel}</td>
      <td>${encuesta.Nivel}</td>
      <td>${encuesta.Localidad}</td>
      <td>${encuesta.AntecedentesEscolares}</td>
      <td>${encuesta.fec_registro}</td>
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
      let fecha = item.fec_registro.split(" ")[0]; // Tomar solo la fecha (sin la hora)
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
                      text: "Altas Educandos",
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

    exportTableToExcel(table,"educandosRegistroNay.xlsx");
    // XLSX.writeFile(wb, "figurasOp.xlsx");
  } else {
    loginMessage.textContent = "Contraseña incorrecta. Inténtalo de nuevo.";
  }
}

function exportTableToExcel(tableId, fileName = "educandosRegistroNay.xlsx") {
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
          <tr>
      <td style="display: none;">${item.Id}</td>
      <td style="display: none;">${item.Apellido_Paterno} ${item.Apellido_Materno} ${item.Nombre}</td>
      <td style="display: none;">${item.eMail}</td>
      <td>${item.Clave_Nivel}</td>
      <td>${item.Nivel}</td>
      <td>${item.Localidad}</td>
      <td>${item.AntecedentesEscolares}</td>
      <td>${item.fec_registro}</td>
  </tr>
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
      let nivel = item.Nivel; // Estado
      let localidad = item.Localidad; // Plantel

      if (!summary[nivel]) {
          summary[nivel] = {};
      }
      if (!summary[nivel][localidad]) {
          summary[nivel][localidad] = 0;
      }
      summary[nivel][localidad] += 1; // Contar registros
  });

  // 🔹 Generar HTML de la tabla dinámica con acordeón
  let tableHTML = `<table border="1" class="summary-table">
                      <thead>
                          <tr>
                              <th>Nivel</th>
                              <th>Localidad</th>
                              <th>Total Educandos</th>
                          </tr>
                      </thead>
                      <tbody>`;

  Object.keys(summary).forEach((nivel, nivelIndex) => {
      let estadoTotal = 0;
      let estadoRowSpan = Object.keys(summary[nivel]).length;

      tableHTML += `<tr class="estado-row" onclick="togglePlanteles('${nivelIndex}')">
                      <td colspan="2"><strong>▶ ${nivel}</strong></td>
                      <td><strong>${Object.values(summary[nivel]).reduce((a, b) => a + b, 0)}</strong></td>
                    </tr>`;

      // 🔹 Planteles ocultos por defecto
      Object.keys(summary[nivel]).forEach((localidad) => {
          let localidadTotal = summary[nivel][localidad];

          tableHTML += `<tr class="plantel-row plantel-${nivelIndex}" style="display: none;">
                          <td></td>
                          <td>${localidad}</td>
                          <td>${localidadTotal}</td>
                        </tr>`;
      });
  });

  tableHTML += `</tbody></table>`;

  // 🔹 Insertar la tabla en el HTML
  document.getElementById("summaryContainer").innerHTML = tableHTML;
}

// 🔹 Función para expandir/colapsar los planteles al hacer clic en el estado
function togglePlanteles(nivelIndex) {
  let localidades = document.querySelectorAll(`.plantel-${nivelIndex}`);
  localidades.forEach(row => {
      row.style.display = (row.style.display === "none") ? "table-row" : "none";
  });
}