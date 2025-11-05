<?php
// Espera $cliente = Cliente::obtenerPorId(...) con shape:
// ['id_cliente','tipo_identificacion','correo','telefono','direccion','provincia','canton','distrito','estado',
//  'fisico_nombre','fisico_ape1','fisico_ape2','fisico_cedula',
//  'jur_nombre_empresa','jur_cedula_juridica','rep_nombre','rep_ape1','rep_ape2','rep_telefono','rep_correo','rep_cedula']
require __DIR__ . '/../layout.php';
?>
<div class="container">
  <h1>Editar Cliente #<?= (int)$cliente['id_cliente'] ?></h1>

  <form method="POST" action="/logistica_global/controllers/clienteController.php?accion=editar&id=<?= (int)$cliente['id_cliente'] ?>" id="form-cliente">
    <h3>Datos del Cliente</h3>

    <label>Tipo de Identificación</label>
    <select name="tipo_identificacion" id="tipo_identificacion" required>
      <option value="Fisica"  <?= $cliente['tipo_identificacion']==='Fisica'?'selected':'' ?>>Física</option>
      <option value="Juridica"<?= $cliente['tipo_identificacion']==='Juridica'?'selected':'' ?>>Jurídica</option>
    </select>

    <label>Correo</label>
    <input type="email" name="correo" value="<?= htmlspecialchars($cliente['correo'] ?? '') ?>">

    <label>Teléfono</label>
    <input type="text" name="telefono" value="<?= htmlspecialchars($cliente['telefono'] ?? '') ?>">

    <label>Dirección</label>
    <input type="text" name="direccion" value="<?= htmlspecialchars($cliente['direccion'] ?? '') ?>">

    <label>Provincia</label>
    <input type="text" name="provincia" value="<?= htmlspecialchars($cliente['provincia'] ?? '') ?>">

    <label>Cantón</label>
    <input type="text" name="canton" value="<?= htmlspecialchars($cliente['canton'] ?? '') ?>">

    <label>Distrito</label>
    <input type="text" name="distrito" value="<?= htmlspecialchars($cliente['distrito'] ?? '') ?>">

    <label>Estado</label>
    <select name="estado">
      <option value="Activo"   <?= ($cliente['estado'] ?? '')==='Activo'?'selected':'' ?>>Activo</option>
      <option value="Inactivo" <?= ($cliente['estado'] ?? '')==='Inactivo'?'selected':'' ?>>Inactivo</option>
    </select>

    <h3 id="titulo-fisica">Datos de Persona Física</h3>

    <label>Nombre</label>
    <input type="text" name="nombre" id="f_nombre" value="<?= htmlspecialchars($cliente['fisico_nombre'] ?? '') ?>">

    <label>Primer Apellido</label>
    <input type="text" name="primer_apellido" id="f_ape1" value="<?= htmlspecialchars($cliente['fisico_ape1'] ?? '') ?>">

    <label>Segundo Apellido</label>
    <input type="text" name="segundo_apellido" id="f_ape2" value="<?= htmlspecialchars($cliente['fisico_ape2'] ?? '') ?>">

    <label>Cédula</label>
    <input type="text" name="cedula" id="f_ced" value="<?= htmlspecialchars($cliente['fisico_cedula'] ?? '') ?>">

    <h3 id="titulo-juridica" style="display:none;">Datos de Persona Jurídica</h3>

    <label class="j-only" style="display:none;">Nombre de la Empresa</label>
    <input class="j-only" style="display:none;" type="text" name="nombre_empresa" id="j_empresa" value="<?= htmlspecialchars($cliente['jur_nombre_empresa'] ?? '') ?>">

    <label class="j-only" style="display:none;">Cédula Jurídica</label>
    <input class="j-only" style="display:none;" type="text" name="cedula_juridica" id="j_cedjur" value="<?= htmlspecialchars($cliente['jur_cedula_juridica'] ?? '') ?>">

    <h4 class="j-only" style="display:none;">Representante Legal (opcional)</h4>

    <label class="j-only" style="display:none;">Nombre</label>
    <input class="j-only" style="display:none;" type="text" name="rep_nombre" value="<?= htmlspecialchars($cliente['rep_nombre'] ?? '') ?>">

    <label class="j-only" style="display:none;">Primer Apellido</label>
    <input class="j-only" style="display:none;" type="text" name="rep_ape1" value="<?= htmlspecialchars($cliente['rep_ape1'] ?? '') ?>">

    <label class="j-only" style="display:none;">Segundo Apellido</label>
    <input class="j-only" style="display:none;" type="text" name="rep_ape2" value="<?= htmlspecialchars($cliente['rep_ape2'] ?? '') ?>">

    <label class="j-only" style="display:none;">Teléfono</label>
    <input class="j-only" style="display:none;" type="text" name="rep_telefono" value="<?= htmlspecialchars($cliente['rep_telefono'] ?? '') ?>">

    <label class="j-only" style="display:none;">Correo</label>
    <input class="j-only" style="display:none;" type="email" name="rep_correo" value="<?= htmlspecialchars($cliente['rep_correo'] ?? '') ?>">

    <label class="j-only" style="display:none;">Cédula</label>
    <input class="j-only" style="display:none;" type="text" name="rep_cedula" value="<?= htmlspecialchars($cliente['rep_cedula'] ?? '') ?>">

    <button type="submit">Guardar Cambios</button>
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
  // requeridos si es Física
  fisicaFields.forEach((el,i) => el.required = isFisica ? (i!==2) : false);
  document.getElementById('j_empresa').required = !isFisica;
  document.getElementById('j_cedjur').required = !isFisica;
  tituloFis.style.display = isFisica ? '' : 'none';
  tituloJur.style.display = isFisica ? 'none' : '';
}
tipo.addEventListener('change', toggleTipo);
toggleTipo();
</script>
