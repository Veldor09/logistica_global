<?php
// ============================================================
// 📄 Generador de Factura en PDF
// Ruta: views/facturas/pdf.php
// ============================================================

require_once dirname(__DIR__, 2) . '/libs/fpdf.php';

/**
 * Genera una factura en PDF con sus detalles
 * @param array $factura
 * @param array $detalles
 */
function generarFacturaPDF($factura, $detalles)
{
    // 🧹 Evitar “ruido” antes del PDF
    if (function_exists('ini_set')) @ini_set('zlib.output_compression', '0');
    while (ob_get_level() > 0) { ob_end_clean(); }

    $pdf = new FPDF('P', 'mm', 'A4');
    $pdf->SetAutoPageBreak(true, 15);
    $pdf->AddPage();
    $pdf->SetFont('Arial', 'B', 16);

    // ============ Encabezado ============
    $pdf->Cell(190, 10, utf8_decode('Logística Global S.A.'), 0, 1, 'C');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(190, 8, utf8_decode('Factura #') . ($factura['id_factura'] ?? ''), 0, 1, 'C');
    $pdf->Ln(8);

    // ============ Datos del cliente ============
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 8, utf8_decode('Cliente:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(120, 8, utf8_decode($factura['cliente'] ?? 'N/D'), 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 8, utf8_decode('Fecha Emisión:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $fecha = is_object($factura['fecha_emision'])
        ? $factura['fecha_emision']->format('Y-m-d')
        : (string)($factura['fecha_emision'] ?? date('Y-m-d'));
    $pdf->Cell(50, 8, $fecha, 0, 1);

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(40, 8, utf8_decode('Método de Pago:'), 0, 0);
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(50, 8, utf8_decode($factura['metodo_pago'] ?? 'N/A'), 0, 1);
    $pdf->Ln(6);

    // ============ Tabla de detalles ============
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(80, 8, utf8_decode('Concepto'), 1, 0, 'C');
    $pdf->Cell(30, 8, utf8_decode('Cantidad'), 1, 0, 'C');
    $pdf->Cell(40, 8, utf8_decode('Precio'), 1, 0, 'C');
    $pdf->Cell(40, 8, utf8_decode('Total'), 1, 1, 'C');

    $pdf->SetFont('Arial', '', 12);
    if (empty($detalles)) {
        $pdf->Cell(190, 8, utf8_decode('No hay detalles registrados.'), 1, 1, 'C');
    } else {
        foreach ($detalles as $d) {
            $concepto = utf8_decode((string)($d['concepto'] ?? ''));
            $cantidad = (int)($d['cantidad'] ?? 0);
            $precio   = (float)($d['precio_unitario'] ?? 0);
            $linea    = $cantidad * $precio;

            $pdf->Cell(80, 8, $concepto, 1);
            $pdf->Cell(30, 8, $cantidad, 1, 0, 'C');
            $pdf->Cell(40, 8, number_format($precio, 2), 1, 0, 'R');
            $pdf->Cell(40, 8, number_format($linea, 2), 1, 1, 'R');
        }
    }

    // ============ Totales ============
    $subtotal = (float)($factura['subtotal'] ?? 0);
    $impuesto = (float)($factura['impuesto'] ?? 0);
    $total    = $subtotal + $impuesto;

    $pdf->Ln(6);
    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 8, utf8_decode('Subtotal:'), 0, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 8, number_format($subtotal, 2), 0, 1, 'R');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 8, utf8_decode('Impuesto:'), 0, 0, 'R');
    $pdf->SetFont('Arial', '', 12);
    $pdf->Cell(40, 8, number_format($impuesto, 2), 0, 1, 'R');

    $pdf->SetFont('Arial', 'B', 12);
    $pdf->Cell(150, 8, utf8_decode('Total:'), 0, 0, 'R');
    $pdf->SetFont('Arial', 'B', 14);
    $pdf->Cell(40, 8, number_format($total, 2), 0, 1, 'R');

    // ============ Pie ============
    $pdf->Ln(8);
    $pdf->SetFont('Arial', 'I', 10);
    $pdf->Cell(190, 6, utf8_decode('Gracias por confiar en Logística Global S.A.'), 0, 1, 'C');
    $pdf->Cell(190, 6, utf8_decode('Documento generado electrónicamente - No requiere firma.'), 0, 1, 'C');

    // ============ Salida ============
    $pdf->Output('I', 'Factura_' . ($factura['id_factura'] ?? 'sin_id') . '.pdf');
    exit;
}
