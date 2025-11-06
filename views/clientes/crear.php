<!DOCTYPE html>
<html lang="es">
<head>
  <meta charset="UTF-8">
  <title>Registrar Cliente</title>
  <link rel="stylesheet" href="../../assets/css/style.css">
  <script>
    function toggleTipoCliente() {
      const tipo = document.getElementById("tipo_identificacion").value;
      document.getElementById("formFisico").style.display = tipo === "FISICO" ? "block" : "none";
      document.getElementById("formJuridico").style.display = tipo === "JURIDICO" ? "block" : "none";
    }

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

      // Cargar provincias
      for (const prov in provincias) {
        const opt = document.createElement("option");
        opt.value = prov;
        opt.textContent = prov;
        provinciaSelect.appendChild(opt);
      }

      // Cambiar provincia → cargar cantones
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

      // Cambiar cantón → cargar distritos
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
</head>

<body>
  <div class="container">
    <h1>➕ Registrar Cliente</h1>

    <form method="POST" action="/logistica_global/controllers/clienteController.php?accion=crear">
      <label>Tipo de Cliente:</label>
      <select name="tipo_identificacion" id="tipo_identificacion" onchange="toggleTipoCliente()" required>
        <option value="">-- Seleccionar --</option>
        <option value="FISICO">Físico</option>
        <option value="JURIDICO">Jurídico</option>
      </select>

      <label>Correo:</label>
      <input type="email" name="correo" required>

      <label>Teléfono:</label>
      <input type="text" name="telefono">

      <label>Dirección:</label>
      <input type="text" name="direccion">

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

      <!-- Cliente Físico -->
      <div id="formFisico" style="display:none; grid-column: 1 / -1;">
        <h3>👤 Datos Cliente Físico</h3>
        <label>Nombre:</label>
        <input type="text" name="nombre">
        <label>Primer Apellido:</label>
        <input type="text" name="primer_apellido">
        <label>Segundo Apellido:</label>
        <input type="text" name="segundo_apellido">
        <label>Cédula:</label>
        <input type="text" name="cedula_fisica">
      </div>

      <!-- Cliente Jurídico -->
      <div id="formJuridico" style="display:none; grid-column: 1 / -1;">
        <h3>🏢 Datos Cliente Jurídico</h3>
        <label>Nombre Empresa:</label>
        <input type="text" name="nombre_empresa">
        <label>Cédula Jurídica:</label>
        <input type="text" name="cedula_juridica">

        <h4>👔 Representante Legal</h4>
        <label>Nombre:</label>
        <input type="text" name="rep_nombre">
        <label>Primer Apellido:</label>
        <input type="text" name="rep_ape1">
        <label>Segundo Apellido:</label>
        <input type="text" name="rep_ape2">
        <label>Teléfono:</label>
        <input type="text" name="rep_telefono">
        <label>Correo:</label>
        <input type="email" name="rep_correo">
        <label>Cédula:</label>
        <input type="text" name="rep_cedula">
      </div>

      <button type="submit">💾 Guardar</button>
      <a href="/logistica_global/controllers/clienteController.php" class="btn">⬅️ Volver</a>
    </form>
  </div>
</body>
</html>
