<div class="container">
  <h1>✏️ Editar Cliente</h1>

  <form method="POST" action="/logistica_global/controllers/clienteController.php?accion=editar&id=<?= htmlspecialchars($cliente['id_cliente']) ?>">

    <!-- Tipo de cliente (solo lectura) -->
    <label>Tipo de Identificación:</label>
    <input type="text" name="tipo_identificacion" value="<?= htmlspecialchars($cliente['tipo_identificacion']) ?>" readonly>

    <!-- Campos comunes -->
    <label>Correo:</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo']) ?>" required>

    <label>Teléfono:</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono']) ?>">

    <label>Dirección:</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion']) ?>">

    <!-- ======================= -->
    <!-- Provincia / Cantón / Distrito -->
    <!-- ======================= -->
    <label>Provincia:</label>
    <select id="provincia" name="provincia" required>
      <option value="">-- Seleccione Provincia --</option>
    </select>

    <label>Cantón:</label>
    <select id="canton" name="canton" required disabled>
      <option value="">-- Seleccione Cantón --</option>
    </select>

    <label>Distrito:</label>
    <select id="distrito" name="distrito" required disabled>
      <option value="">-- Seleccione Distrito --</option>
    </select>

    <label>Estado:</label>
    <select name="estado">
      <option value="Activo" <?= $cliente['estado'] === 'Activo' ? 'selected' : '' ?>>Activo</option>
      <option value="Inactivo" <?= $cliente['estado'] === 'Inactivo' ? 'selected' : '' ?>>Inactivo</option>
    </select>

    <!-- ======================================= -->
    <!-- SECCIÓN CLIENTE FÍSICO -->
    <!-- ======================================= -->
    <?php if ($cliente['tipo_identificacion'] === 'FISICO'): ?>
      <h3>Datos del Cliente Físico</h3>

      <label>Nombre:</label>
      <input type="text" name="nombre" value="<?= htmlspecialchars($cliente['nombre']) ?>" required>

      <label>Primer Apellido:</label>
      <input type="text" name="primer_apellido" value="<?= htmlspecialchars($cliente['primer_apellido']) ?>" required>

      <label>Segundo Apellido:</label>
      <input type="text" name="segundo_apellido" value="<?= htmlspecialchars($cliente['segundo_apellido']) ?>">

      <label>Cédula:</label>
      <input type="text" name="cedula_fisica" value="<?= htmlspecialchars($cliente['cedula_fisica']) ?>" required>
    <?php endif; ?>

    <!-- ======================================= -->
    <!-- SECCIÓN CLIENTE JURÍDICO -->
    <!-- ======================================= -->
    <?php if ($cliente['tipo_identificacion'] === 'JURIDICO'): ?>
      <h3>Datos del Cliente Jurídico</h3>

      <label>Nombre Empresa:</label>
      <input type="text" name="nombre_empresa" value="<?= htmlspecialchars($cliente['nombre_empresa']) ?>" required>

      <label>Cédula Jurídica:</label>
      <input type="text" name="cedula_juridica" value="<?= htmlspecialchars($cliente['cedula_juridica']) ?>" required>
    <?php endif; ?>

    <!-- Botón de guardar -->
    <button type="submit">💾 Guardar Cambios</button>
  </form>
</div>

<!-- ====================================================== -->
<!-- Script para provincias, cantones y distritos (igual a crear.php) -->
<!-- ====================================================== -->
<script>
document.addEventListener("DOMContentLoaded", function() {
  const provincias = {
    "San José": {
      "Central": ["Carmen", "Merced", "Hospital", "Catedral", "Zapote", "San Francisco de Dos Ríos"],
      "Escazú": ["Escazú Centro", "San Rafael", "San Antonio"],
      "Desamparados": ["Desamparados Centro", "San Miguel", "San Rafael Arriba"]
    },
    "Alajuela": {
      "Central": ["Alajuela", "San José", "Carrizal", "San Antonio"],
      "San Ramón": ["San Ramón Centro", "Santiago", "Piedades Norte"],
      "Grecia": ["Grecia Centro", "San Roque", "San Isidro"]
    },
    "Cartago": {
      "Central": ["Oriental", "Occidental", "San Nicolás", "Aguacaliente"],
      "Paraíso": ["Paraíso Centro", "Santiago", "Cachí"],
      "La Unión": ["Tres Ríos", "San Diego", "San Juan"]
    },
    "Heredia": {
      "Central": ["Heredia", "Mercedes", "San Francisco"],
      "Barva": ["Barva Centro", "San Pedro", "San Pablo"],
      "Santo Domingo": ["Santo Domingo", "Paracito", "Pará"]
    },
    "Guanacaste": {
      "Liberia": ["Liberia", "Cañas Dulces", "Mayorga", "Nacascolo", "Curubandé"],
      "Nicoya": ["Nicoya", "Mansión", "San Antonio", "Sámara", "Nosara"],
      "Santa Cruz": ["Santa Cruz", "Veintisiete de Abril", "Tamarindo", "Bolsón"],
      "Bagaces": ["Bagaces", "Fortuna", "Mogote", "Río Naranjo"],
      "Carrillo": ["Filadelfia", "Palmira", "Sardinal", "Belén"],
      "Cañas": ["Cañas", "Palmira", "San Miguel", "Bebedero"],
      "Tilarán": ["Tilarán", "Tronadora", "Quebrada Grande", "Tierras Morenas"],
      "La Cruz": ["La Cruz", "Santa Cecilia", "La Garita", "Santa Elena"],
      "Hojancha": ["Hojancha", "Monte Romo", "Puerto Carrillo", "Huacas"]
    },
    "Puntarenas": {
      "Central": ["Puntarenas", "Barranca", "El Roble"],
      "Esparza": ["Espíritu Santo", "San Juan Grande", "Macacona"],
      "Buenos Aires": ["Buenos Aires", "Volcán", "Brunka"]
    },
    "Limón": {
      "Central": ["Limón", "Valle La Estrella", "Río Blanco"],
      "Pococí": ["Guápiles", "Jiménez", "Cariari"],
      "Siquirres": ["Siquirres", "Pacuarito", "Florida"]
    }
  };

  const provinciaSelect = document.getElementById("provincia");
  const cantonSelect = document.getElementById("canton");
  const distritoSelect = document.getElementById("distrito");

  const provSaved = "<?= htmlspecialchars($cliente['provincia']) ?>";
  const cantonSaved = "<?= htmlspecialchars($cliente['canton']) ?>";
  const distritoSaved = "<?= htmlspecialchars($cliente['distrito']) ?>";

  // 🟦 Cargar provincias
  for (const prov in provincias) {
    const opt = document.createElement("option");
    opt.value = prov;
    opt.textContent = prov;
    if (prov === provSaved) opt.selected = true;
    provinciaSelect.appendChild(opt);
  }

  // 🟩 Cargar cantones si hay provincia guardada
  if (provSaved && provincias[provSaved]) {
    const cantones = provincias[provSaved];
    cantonSelect.disabled = false;
    for (const canton in cantones) {
      const opt = document.createElement("option");
      opt.value = canton;
      opt.textContent = canton;
      if (canton === cantonSaved) opt.selected = true;
      cantonSelect.appendChild(opt);
    }
  }

  // 🟨 Cargar distritos si hay cantón guardado
  if (provSaved && cantonSaved && provincias[provSaved]?.[cantonSaved]) {
    const distritos = provincias[provSaved][cantonSaved];
    distritoSelect.disabled = false;
    distritos.forEach(dist => {
      const opt = document.createElement("option");
      opt.value = dist;
      opt.textContent = dist;
      if (dist === distritoSaved) opt.selected = true;
      distritoSelect.appendChild(opt);
    });
  }

  // 🟦 Evento cambio provincia
  provinciaSelect.addEventListener("change", () => {
    cantonSelect.innerHTML = '<option value="">-- Seleccione Cantón --</option>';
    distritoSelect.innerHTML = '<option value="">-- Seleccione Distrito --</option>';
    distritoSelect.disabled = true;

    const cantones = provincias[provinciaSelect.value];
    if (cantones) {
      cantonSelect.disabled = false;
      for (const canton in cantones) {
        const opt = document.createElement("option");
        opt.value = canton;
        opt.textContent = canton;
        cantonSelect.appendChild(opt);
      }
    } else {
      cantonSelect.disabled = true;
    }
  });

  // 🟨 Evento cambio cantón
  cantonSelect.addEventListener("change", () => {
    distritoSelect.innerHTML = '<option value="">-- Seleccione Distrito --</option>';
    const cantones = provincias[provinciaSelect.value];
    const distritos = cantones ? cantones[cantonSelect.value] : null;

    if (distritos) {
      distritoSelect.disabled = false;
      distritos.forEach(dist => {
        const opt = document.createElement("option");
        opt.value = dist;
        opt.textContent = dist;
        distritoSelect.appendChild(opt);
      });
    } else {
      distritoSelect.disabled = true;
    }
  });
});
</script>
