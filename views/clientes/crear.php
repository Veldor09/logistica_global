<?php require __DIR__ . '/../layout.php'; ?>

<div class="container">
  <h1>Crear Cliente</h1>

  <form method="POST" action="/logistica_global/controllers/clienteController.php?accion=crear" id="form-cliente">
    <h3>Datos del Cliente</h3>

    <label>Tipo de Identificación</label>
    <select name="tipo_identificacion" id="tipo_identificacion" required>
      <option value="Fisica">Física</option>
      <option value="Juridica">Jurídica</option>
    </select>

    <label>Correo</label>
    <input type="email" name="correo" placeholder="cliente@correo.com">

    <label>Teléfono</label>
    <input type="text" name="telefono" placeholder="8888-8888">

    <label>Dirección</label>
    <input type="text" name="direccion" placeholder="Dirección exacta">

    <label>Provincia</label>
    <input type="text" name="provincia">

    <label>Cantón</label>
    <input type="text" name="canton">

    <label>Distrito</label>
    <input type="text" name="distrito">

    <h3 id="titulo-fisica">Datos de Persona Física</h3>

    <label>Nombre</label>
    <input type="text" name="nombre" id="f_nombre" required>

    <label>Primer Apellido</label>
    <input type="text" name="primer_apellido" id="f_ape1" required>

    <label>Segundo Apellido</label>
    <input type="text" name="segundo_apellido" id="f_ape2">

    <label>Cédula</label>
    <input type="text" name="cedula" id="f_ced" required>

    <h3 id="titulo-juridica" style="display:none;">Datos de Persona Jurídica</h3>

    <label class="j-only" style="display:none;">Nombre de la Empresa</label>
    <input class="j-only" style="display:none;" type="text" name="nombre_empresa" id="j_empresa">

    <label class="j-only" style="display:none;">Cédula Jurídica</label>
    <input class="j-only" style="display:none;" type="text" name="cedula_juridica" id="j_cedjur">

    <h4 class="j-only" style="display:none;">Representante Legal (opcional)</h4>

    <label class="j-only" style="display:none;">Nombre</label>
    <input class="j-only" style="display:none;" type="text" name="rep_nombre">

    <label class="j-only" style="display:none;">Primer Apellido</label>
    <input class="j-only" style="display:none;" type="text" name="rep_ape1">

    <label class="j-only" style="display:none;">Segundo Apellido</label>
    <input class="j-only" style="display:none;" type="text" name="rep_ape2">

    <label class="j-only" style="display:none;">Teléfono</label>
    <input class="j-only" style="display:none;" type="text" name="rep_telefono">

    <label class="j-only" style="display:none;">Correo</label>
    <input class="j-only" style="display:none;" type="email" name="rep_correo">

    <label class="j-only" style="display:none;">Cédula</label>
    <input class="j-only" style="display:none;" type="text" name="rep_cedula">

    <button type="submit">Guardar</button>
  </form>
</div>

<script>
const tipo = document.getElementById('tipo_identificacion');
const jOnly = document.querySelectorAll('.j-only');
const fisicaFields = ['f_nombre','f_ape1','f_ape2','f_ced'].map(id=>document.getElementById(id));
const tituloFis = document.getElementById('titulo-fisica');
const tituloJur = document.getElementById('titulo-juridica');

function toggleTipo() {
  const isFisica = tipo.value === 'Fisica';
  jOnly.forEach(el => el.style.display = isFisica ? 'none' : '');
  fisicaFields.forEach((el,i) => el.required = isFisica ? (i!==2) : false);
  document.getElementById('j_empresa').required = !isFisica;
  document.getElementById('j_cedjur').required = !isFisica;
  tituloFis.style.display = isFisica ? '' : 'none';
  tituloJur.style.display = isFisica ? 'none' : '';
}
tipo.addEventListener('change', toggleTipo);
toggleTipo();
</script>
