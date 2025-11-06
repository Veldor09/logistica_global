<div class="container">
  <h1>🚗 Registrar Vehículo</h1>

  <a href="/logistica_global/controllers/vehiculoController.php" class="btn">⬅️ Volver</a>

  <form method="POST" action="/logistica_global/controllers/vehiculoController.php?accion=crear">
    <div class="form-grid-2">
      <div>
        <label>Tipo de Camión:</label>
        <select name="id_tipo_camion" id="id_tipo_camion" required>
          <option value="">-- Seleccione --</option>
          <?php foreach ($tipos as $t): ?>
            <option value="<?= $t['id_tipo_camion'] ?>">
              <?= htmlspecialchars($t['nombre_tipo']) ?>
            </option>
          <?php endforeach; ?>
        </select>
      </div>

      <div>
        <label>Placa:</label>
        <input type="text" name="placa" id="placa" maxlength="10" required placeholder="Ej. ABC123 o 000123">
      </div>

      <div>
        <label>Marca:</label>
        <input type="text" name="marca" id="marca" placeholder="Seleccione tipo primero" list="listaMarcas">
        <datalist id="listaMarcas"></datalist>
      </div>

      <div>
        <label>Modelo:</label>
        <input type="text" name="modelo" id="modelo" placeholder="Seleccione marca" list="listaModelos">
        <datalist id="listaModelos"></datalist>
      </div>

      <div>
        <label>Año:</label>
        <input type="number" name="anio" id="anio" placeholder="Seleccione modelo" list="listaAnios" min="1990" max="2050">
        <datalist id="listaAnios"></datalist>
      </div>

      <div>
        <label>Capacidad (kg):</label>
        <input type="number" name="capacidad_kg" id="capacidad_kg" step="0.01" placeholder="Auto según tipo" required>
      </div>

      <div>
        <label>Fecha de adquisición:</label>
        <input type="date" name="fecha_adquisicion">
      </div>

      <div>
        <label>Estado:</label>
        <select name="estado">
          <option value="Activo">Activo</option>
          <option value="Inactivo">Inactivo</option>
          <option value="Mantenimiento">Mantenimiento</option>
        </select>
      </div>
    </div>

    <button type="submit" class="btn success">💾 Guardar</button>
  </form>
</div>

<!-- ========================================================= -->
<!-- 📜 Script dinámico tipo → marca → modelo → año             -->
<!-- ========================================================= -->
<script>
document.addEventListener("DOMContentLoaded", () => {
  const dataVehiculos = {
    "Camión Liviano": {
      capacidad: 3500,
      marcas: {
        "Toyota": ["Dyna", "Hilux Chasis", "Hiace Cargo"],
        "Nissan": ["Cabstar", "Atlas"],
        "Isuzu": ["Elf", "NHR", "NKR"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    },
    "Camión Mediano": {
      capacidad: 7000,
      marcas: {
        "Hino": ["300", "500"],
        "Isuzu": ["NQR", "NPR"],
        "Mercedes-Benz": ["Atego"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    },
    "Camión Pesado": {
      capacidad: 12000,
      marcas: {
        "Scania": ["R-Series", "G-Series"],
        "Volvo": ["FH", "FMX"],
        "MAN": ["TGS", "TGX"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    },
    "Camión Plataforma": {
      capacidad: 15000,
      marcas: {
        "Freightliner": ["Cascadia", "M2 106"],
        "International": ["LT", "HX"],
        "Kenworth": ["T680", "T800"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    },
    "Camión Cisterna": {
      capacidad: 10000,
      marcas: {
        "Hino": ["500 Tanker", "700 Tanker"],
        "Volvo": ["FMX Tanker"],
        "Isuzu": ["FVZ Tanker"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    },
    "Camión Refrigerado": {
      capacidad: 9000,
      marcas: {
        "Isuzu": ["NQR Cool", "NPR Cool"],
        "Mercedes-Benz": ["Atego Cool"],
        "Hyundai": ["HD78 Cool"]
      },
      años: [2024, 2023, 2022, 2021, 2020]
    }
  };

  const tipoSelect = document.getElementById("id_tipo_camion");
  const marcaInput = document.getElementById("marca");
  const modeloInput = document.getElementById("modelo");
  const anioInput = document.getElementById("anio");
  const capacidadInput = document.getElementById("capacidad_kg");

  tipoSelect.addEventListener("change", () => {
    const tipoNombre = tipoSelect.options[tipoSelect.selectedIndex].text;
    const tipoData = dataVehiculos[tipoNombre];
    if (!tipoData) return;

    // Capacidad
    capacidadInput.value = tipoData.capacidad;

    // Marcas
    const listaMarcas = document.getElementById("listaMarcas");
    listaMarcas.innerHTML = "";
    Object.keys(tipoData.marcas).forEach(marca => {
      const option = document.createElement("option");
      option.value = marca;
      listaMarcas.appendChild(option);
    });

    marcaInput.value = "";
    modeloInput.value = "";
    anioInput.value = "";
  });

  marcaInput.addEventListener("input", () => {
    const tipoNombre = tipoSelect.options[tipoSelect.selectedIndex].text;
    const tipoData = dataVehiculos[tipoNombre];
    if (!tipoData) return;

    const marca = marcaInput.value;
    const modelos = tipoData.marcas[marca] || [];

    const listaModelos = document.getElementById("listaModelos");
    listaModelos.innerHTML = "";
    modelos.forEach(modelo => {
      const option = document.createElement("option");
      option.value = modelo;
      listaModelos.appendChild(option);
    });

    modeloInput.value = "";
    anioInput.value = "";
  });

  modeloInput.addEventListener("input", () => {
    const tipoNombre = tipoSelect.options[tipoSelect.selectedIndex].text;
    const tipoData = dataVehiculos[tipoNombre];
    if (!tipoData) return;

    const listaAnios = document.getElementById("listaAnios");
    listaAnios.innerHTML = "";
    tipoData.años.forEach(anio => {
      const option = document.createElement("option");
      option.value = anio;
      listaAnios.appendChild(option);
    });
  });
});
</script>

<style>
.container {
  background: #fff;
  padding: 2rem;
  border-radius: 15px;
  box-shadow: 0 0 10px rgba(0,0,0,0.1);
  margin-top: 2rem;
}

h1 {
  text-align: center;
  margin-bottom: 1.5rem;
}

.form-grid-2 {
  display: grid;
  grid-template-columns: repeat(auto-fit, minmax(300px, 1fr));
  gap: 1rem 2rem;
}

label {
  font-weight: bold;
}

input, select {
  width: 100%;
  padding: 0.5rem;
  border: 1px solid #ccc;
  border-radius: 6px;
}

.btn {
  display: inline-block;
  padding: 0.5rem 1rem;
  border-radius: 6px;
  text-decoration: none;
  color: white;
  background: #007bff;
  margin-top: 1rem;
}

.btn.success {
  background: #28a745;
}

.btn:hover {
  opacity: 0.85;
}
</style>
