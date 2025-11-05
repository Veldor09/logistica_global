<?php
// Helper interno: devuelve Y-m-d desde DateTime o string
function fmtFecha($valor) {
    if ($valor instanceof DateTime) {
        return $valor->format('Y-m-d');
    }
    if (!empty($valor)) {
        return date('Y-m-d', strtotime($valor));
    }
    return '';
}
?>
<div class="p-6 bg-white rounded-2xl shadow-md border">
  <h2 class="text-3xl font-bold text-center mb-6 text-[#003366]">
    ✏️ Editar Orden de Transporte
  </h2>

  <form 
    method="POST" 
    action="/logistica_global/controllers/ordenController.php?accion=editar&id=<?= htmlspecialchars($orden['id_orden']) ?>" 
    class="grid grid-cols-1 md:grid-cols-2 gap-6"
  >
    <!-- Solicitud Asociada (solo lectura) -->
    <div class="md:col-span-2">
      <label class="block font-semibold mb-1 text-gray-700">Solicitud Asociada:</label>
      <input 
        type="text" 
        class="w-full border rounded-lg px-3 py-2 bg-gray-100 text-gray-600 cursor-not-allowed"
        value="#<?= htmlspecialchars($orden['id_solicitud']) ?> - <?= htmlspecialchars($orden['tipo_servicio']) ?>"
        disabled
      >
    </div>

    <!-- Dirección de Origen -->
    <div>
      <label for="direccion_origen" class="block font-semibold mb-1 text-gray-700">Dirección de Origen:</label>
      <input 
        type="text" 
        name="direccion_origen" 
        id="direccion_origen"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= htmlspecialchars($orden['direccion_origen'] ?? '') ?>" 
        required
      >
    </div>

    <!-- Dirección de Destino -->
    <div>
      <label for="direccion_destino" class="block font-semibold mb-1 text-gray-700">Dirección de Destino:</label>
      <input 
        type="text" 
        name="direccion_destino" 
        id="direccion_destino"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= htmlspecialchars($orden['direccion_destino'] ?? '') ?>" 
        required
      >
    </div>

    <!-- Peso Estimado -->
    <div>
      <label for="peso_estimado_kg" class="block font-semibold mb-1 text-gray-700">Peso Estimado (kg):</label>
      <input 
        type="number" 
        step="0.01"
        name="peso_estimado_kg" 
        id="peso_estimado_kg"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= htmlspecialchars($orden['peso_estimado_kg'] ?? 0) ?>" 
      >
    </div>

    <!-- Fecha de Carga -->
    <div>
      <label for="fecha_carga" class="block font-semibold mb-1 text-gray-700">Fecha de Carga:</label>
      <input 
        type="date" 
        name="fecha_carga" 
        id="fecha_carga"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= fmtFecha($orden['fecha_carga']) ?>"
      >
    </div>

    <!-- Fecha Entrega Estimada -->
    <div>
      <label for="fecha_entrega_estimada" class="block font-semibold mb-1 text-gray-700">Fecha Entrega Estimada:</label>
      <input 
        type="date" 
        name="fecha_entrega_estimada" 
        id="fecha_entrega_estimada"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= fmtFecha($orden['fecha_entrega_estimada']) ?>"
      >
    </div>

    <!-- Fecha Entrega Real -->
    <div>
      <label for="fecha_entrega_real" class="block font-semibold mb-1 text-gray-700">Fecha Entrega Real:</label>
      <input 
        type="date" 
        name="fecha_entrega_real" 
        id="fecha_entrega_real"
        class="w-full border rounded-lg px-3 py-2"
        value="<?= fmtFecha($orden['fecha_entrega_real']) ?>"
      >
    </div>

    <!-- Estado -->
    <div>
      <label for="estado" class="block font-semibold mb-1 text-gray-700">Estado:</label>
      <select 
        name="estado" 
        id="estado" 
        class="w-full border rounded-lg px-3 py-2"
      >
        <?php 
          $estados = ['Programada', 'En Progreso', 'Completada', 'Cancelada'];
          foreach ($estados as $estado):
            $selected = ($orden['estado'] === $estado) ? 'selected' : '';
            echo "<option value='$estado' $selected>$estado</option>";
          endforeach;
        ?>
      </select>
    </div>

    <!-- Observaciones -->
    <div class="md:col-span-2">
      <label for="observaciones" class="block font-semibold mb-1 text-gray-700">Observaciones:</label>
      <textarea 
        name="observaciones" 
        id="observaciones"
        rows="3"
        class="w-full border rounded-lg px-3 py-2"
      ><?= htmlspecialchars($orden['observaciones'] ?? '') ?></textarea>
    </div>

    <!-- Botones -->
    <div class="md:col-span-2 flex justify-between pt-4">
      <a 
        href="/logistica_global/controllers/ordenController.php" 
        class="bg-gray-500 text-white px-4 py-2 rounded-lg hover:bg-gray-600 transition"
      >
        ← Volver
      </a>
      <button 
        type="submit" 
        class="bg-blue-600 text-white px-5 py-2 rounded-lg hover:bg-blue-700 transition"
      >
        💾 Guardar Cambios
      </button>
    </div>
  </form>
</div>