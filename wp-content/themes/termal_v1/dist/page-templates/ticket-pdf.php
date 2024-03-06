<?php /* Template Name: Credencial PDF */ ?>
<?php $id = (isset($_GET['id'])) ? (string)trim($_GET['id']) : ''; ?>
<?php
	require_once(get_template_directory().'/vendor/autoload.php');
    //Create JSON Request
    $array = array(
        'msg' => 'getCliente',
        'fields' => array(
            'cliente_id' => $id
        )
    );
    $json_array = json_encode($array);

    //Request Call
    $response = callAPI($json_array);
    $response_row = json_decode($response, true);

    //Check Request Call Status
    if ((int)$response_row['status'] == 1)
    {
        //Read Jobs
        $cliente = $response_row['data'];
    }

    // CONFIGURACIÓN PREVIA
    require(get_template_directory().'/vendor/setasign/fpdf/fpdf.php');
    define('EURO',chr(36)); // Constante con el símbolo Euro.
    $pdf = new FPDF('P','mm',array(80,95)); // Tamaño tickt 80mm x 150 mm (largo aprox)
    $pdf = new \Sarabitcom\Fpdf\FpdfCode39('P', 'mm', array(80,95));          
    $pdf->AddPage();

    // CABECERA
    //$pdf->Image('http://localhost:8888/redline-main/wp-content/uploads/2024/02/screenshot.jpg', 10, 10, -300);
    $pdf->SetFont('Helvetica','',12);
    $pdf->Cell(60,4,utf8_decode('Redline Project'),0,1,'C');
    $pdf->SetFont('Helvetica','',8);
    //$pdf->Cell(60,4,'C.I.F.: 01234567A',0,1,'C');
    //$pdf->Cell(60,4,'C/ Arturo Soria, 1',0,1,'C');
    $pdf->Cell(60,4,utf8_decode('Calle Lázaro Cardenas 410'),0,1,'C');
    $pdf->Cell(60,4,'Horario: 7am - 10pm',0,1,'C');
    $pdf->Cell(60,4,''.$company['email'].'',0,1,'C');
    $pdf->Code39(10, 57, '000000'.$cliente[0]['ID'].'', 1, 10);

    
    // DATOS FACTURA        
    $pdf->Ln(4);
    $pdf->Cell(60,4,'ID: '.$cliente[0]['ID'].'',0,1,'');
    $pdf->Cell(60,4,'Membresia: '.$cliente[0]['membresia'].'',0,1,'');
    $pdf->Cell(60,4,'Nombre: '.$cliente[0]['nombre'].'',0,1,'');
    $pdf->Cell(60,4,'Vencimiento: '.$cliente[0]['vencimiento'].'',0,1,'');

    //$pdf->Cell(60,4,'Metodo de pago: Tarjeta',0,1,'');


    
    // PIE DE PAGINA
    $pdf->Ln(25);
    $pdf->Cell(60,0,'Gracias por su preferencia',0,1,'C');
    $pdf->Ln(3);
    $pdf->Cell(60,0,utf8_decode('Redline © 2024'),0,1,'C');
    //print_r($pdf);
    $pdf->Output('ticket.pdf','i');
?>