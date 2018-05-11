<?php

require_once('./tcpdf/tcpdf.php');

$mes = ($_REQUEST['mes']);
// $year = ($_POST['year']);
$id_usuario = ($_REQUEST['id_usuario']);

// echo($mes);
// echo($id_usuario);

$url = 'http://bank-admin.esy.es/api/v1/id/estadodecuenta/mes';
$data = array('id_usuario' => $id_usuario, 'mes' => $mes);

// Method: POST, PUT, GET etc
// Data: array("param" => "value") ==> index.php?param=value

function CallAPI($method, $url, $data = false)
{
    $curl = curl_init();

    switch ($method)
    {
        case "POST":
            curl_setopt($curl, CURLOPT_POST, 1);

            if ($data)
                curl_setopt($curl, CURLOPT_POSTFIELDS, $data);
            break;
        case "PUT":
            curl_setopt($curl, CURLOPT_PUT, 1);
            break;
        default:
            if ($data)
                $url = sprintf("%s?%s", $url, http_build_query($data));
    }

    // Optional Authentication:
    curl_setopt($curl, CURLOPT_HTTPAUTH, CURLAUTH_BASIC);
    curl_setopt($curl, CURLOPT_USERPWD, "username:password");

    curl_setopt($curl, CURLOPT_URL, $url);
    curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);

    $result = curl_exec($curl);
    header('Content-type: application/json');
    curl_close($curl);

    return $result;
}

$result = CallAPI('POST',$url,$data);
// echo($result);
$json = json_decode($result,true);
// print_r($json);

// var_dump($result);

class PDF extends TCPDF {
    public function Header (){
        $image_file = './tcpdf/logo.png';
        $this->Image($image_file, 185, 12, 9, '', 'PNG', '', 'T', false, 300, '', false, false, 0, false, false, false);
        $this->SetFont('dejavusans', '', 14);
        // $title = utf8_encode('Estado de Cuenta');
        // $subtitle = utf8_encode('sub title');
        $this->SetHeaderMargin(40);
        // $this-> Cell (0, 0, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, 1, 'R', 0, '', 0, false, '', 1);
        $this->Line(15,24,195,24);      
    }

    public function Footer() {
        $this->SetFont('dejavusans', '', 8);
        $this->SetY(10);
        // $this->SetX(10);
        $this-> Cell (0, 5, 'Página '.$this->getAliasNumPage().' de '.$this->getAliasNbPages(), 0, 1, 'R', 0, '', 0, false, '', 3);
    }   

    public static function makeHTML ($json){
        $html = <<<EOF
        <style type="text/css">
            .data {text-align:left;font-family:Arial, sans-serif;font-size:10px;}
            .titleheader {text-align:right;font-family:Arial, sans-serif;font-size:18px;}
            .tg  {border-collapse:collapse;border-spacing:0;border-color:#ccc;}
            .tg td{font-family:Arial, sans-serif;font-size:14px;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#fff;}
            .tg th{font-family:Arial, sans-serif;font-size:14px;font-weight:normal;padding:10px 5px;border-style:solid;border-width:0px;overflow:hidden;word-break:normal;border-color:#ccc;color:#333;background-color:#f0f0f0;}
            .tg .tg-dc35{background-color:#f9f9f9;border-color:inherit;vertical-align:top}
            .tg .tg-5fiw{background-color:#f9f9f9;border-color:inherit;text-align:right;vertical-align:top}
            .tg .tg-lqy6{text-align:right;vertical-align:top}
            .tg .tg-p8bj{font-weight:bold;border-color:inherit;vertical-align:top}
            .tg .tg-6ic8{font-weight:bold;border-color:inherit;text-align:right;vertical-align:top}
            .tg .tg-yw4l{vertical-align:top}
            .tg .tg-wk98{text-align:center}
            .tg .tg-b7b8{background-color:#f9f9f9;vertical-align:top}
            .tg .tg-p5oz{background-color:#f9f9f9;text-align:right;vertical-align:top}
        </style>
        <table class="tg">
            <tr>
                <th class="tg-wk98">Movimientos del Periodo</th>
            </tr>
        </table>
        <br />
        <br />
EOF;
        $html .= '
        <table class="tg">
            <tr>
                <th class="tg-p8bj">Fecha</th>
                <th class="tg-p8bj">Descripci&oacute;n</th>
                <th class="tg-6ic8">Cantidad (Pesos)</th>
            </tr>';
// EOF;
        for ($i=0; $i<count($json["detalles"]["movimientos"]); $i++){
            if ($i==0) {
                $cl1 = " class=\"tg-dc35\"";
                $cl2 = " class=\"tg-5fiw\"";
            } elseif ($i % 2 == 0) {
                $cl1 = " class=\"tg-b7b8\"";
                $cl2 = " class=\"tg-p5oz\"";
            } else {
                $cl1 = " class=\"tg-yw41\"";
                $cl2 = " class=\"tg-lqy6\"";
            }
            $html .= '<tr>
                        <td'.$cl1.'>'.$json["detalles"]["movimientos"][$i]["fecha"].'</td>
                        <td'.$cl1.'>'.$json["detalles"]["movimientos"][$i]["detalles"].'</td>
                        <td'.$cl2.'>'.'$'.$json["detalles"]["movimientos"][$i]["monto"].'</td>
                      </tr>';
        }
        $html .= '</table>';
        return $html;
    }
}

function printReport ($json){
    set_time_limit(0);

    $pdf = new PDF("P", PDF_UNIT, "A4",true, 'UTF-8', false);
    // $pdf = new PDF(PDF_PAGE_ORIENTATION, PDF_UNIT, PDF_PAGE_FORMAT, true, 'UTF-8', false);
    // $pdf->SetCreator(PDF_CREATOR);
    // $pdf->SetAuthor('Nicola Asuni');
    $pdf->SetTitle('Estado de Cuenta');
    // $pdf->SetSubject('TCPDF Tutorial');
    // $pdf->SetKeywords('TCPDF, PDF, example, test, guide');
    // $pdf->setPrintHeader(false);
    // $pdf->setFooterFont(Array(PDF_FONT_NAME_DATA, '', PDF_FONT_SIZE_DATA));
    $pdf->SetMargins (15, 15, 15);
    $pdf->SetFooterMargin(PDF_MARGIN_FOOTER);
    // $pdf->SetAutoPageBreak(TRUE, 50);
// $pdf->SetMargins(PDF_MARGIN_LEFT, PDF_MARGIN_TOP, PDF_MARGIN_RIGHT);
    // $pdf->SetHeaderMargin(100);
    // $pdf->setFooterData(array(0,64,0), array(0,64,128));
    // $pdf->SetFooterMargin(15);
    $pdf->SetFont('dejavusans', '', 8);
    // $pdf->SetAutoPageBreak(TRUE,50);
    $pdf->AddPage();

    // get current vertical position
    $y = $pdf->getY();
    $edo =<<<EOF
    <style type="text/css">
        .titleheader {text-align:right;font-family:Arial, sans-serif;font-size:20px;}
    </style>
    <p class="titleheader"><strong>ESTADO DE CUENTA</strong>&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;</p>
EOF;
    $pdf->writeHTML($edo, false, false, false, false, '');

    $tagvs = array('p' => array(0 => array('h' => 0, 'n' => 0), 1 => array('h' => 0, 'n'=> 0)));
    $pdf->setHtmlVSpace($tagvs);
    $data .= '
    <style type="text/css">
        p {color:#383838}
        .data {text-align:left;font-family:Arial, sans-serif;font-size:12px;}
    </style>';
    foreach ($json["detalles"]["datos_usuario"] as $key => $value) {
        // print_r($value);
        foreach ($value as $key1 => $value1) {
            // echo($value1);
            // $data .= '<br />';
            $data .= '<p class="data">'.$value1.'</p>';
        }
    }
    // $data .= '</p>';
    // $pdf->Ln(10);
    // set color for background
    $pdf->SetFillColor(240,240,240);

    // set color for text
    // $pdf->SetTextColor(0, 63, 127);

    // write the first column
    $pdf->writeHTMLCell(80, '', '', $y, $data, 1, 0, 1, true, 'R', true);

    // set color for background
    $pdf->SetFillColor(240,240,240);

    $data = <<<EOF
    <style type="text/css">
        p {color:#383838}
        .data {text-align:right;font-family:Arial, sans-serif;font-size:12px;}
    </style>
    <p class="data">Saldo a la Fecha de Corte:</p>
    <p class="data">Fecha de Corte:</p>
EOF;
    // $data .= '$'.end($json["detalles"]["saldo"])["total"].'</p>
    // <p class="data">Fecha de Corte: '.$json["detalles"]["fecha_corte"].'</p>';
    // get current vertical position
    // $y = $pdf->getY();
    $pdf->writeHTMLCell(60, '', 105, $y+11, $data, 0, 0, 1, true, 'R', true);
    $data = <<<EOF
    <style type="text/css">
        p {color:#F0F0F0}
        .data {text-align:right;font-family:Arial, sans-serif;font-size:12px;}
    </style>
EOF;
    $data .= '<p class="data">$'.end($json["detalles"]["saldo"])["total"].'</p>';
    $data .= '<p class="data">'.$json["detalles"]["fecha_corte"].'</p>';
    $pdf->SetFillColor(145,145,145);
    $pdf->writeHTMLCell(30, '', 165, $y+11, $data, 0, 0, 1, true, 'R', true);
    // set color for text
    // $pdf->SetTextColor(127, 31, 0);

    $pdf->Ln(15);

    //create html
    $html = $pdf->makeHTML($json);
    // echo $html;

    $pdf->writeHTML($html, false, false, false, false, '');

    if (!file_exists("your path"))
        mkdir("your path");

    // $pdf->Output("your path", 'F');  //save pdf
    ob_end_clean();
    $title = 'Estado de Cuenta-'.$json["detalles"]["fecha_corte"].'.pdf';
    $pdf->Output($title, 'I'); // show pdf

    return true;
}
 printReport($json);
?>