<?php
function view($ruta, $data = []) {
  extract($data); // <- permite $titulo, $facturas, etc.
  $BASE_PATH = dirname(__DIR__); // .../logistica_global
  ob_start();
  include $BASE_PATH . "/views/$ruta"; // genera $contenido
  $contenido = ob_get_clean();
  include $BASE_PATH . "/views/layout.php"; // usa $contenido y $titulo
}

function redirect($path) {
  header("Location: $path");
  exit;
}
