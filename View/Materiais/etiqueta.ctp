<?php
$html .= "<div class='container'>";
$html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logobritacal.png' height='20' width='90';/ ></p>";
$html .= "<p style='color:white'> Branco</p>";

$html .= "<p align='center'> <font size='5'><b>{$materiais['Material']['nome']}</font></p>";

$html .= "</div>";


 ?>

<?php

$mpdf = new mPDF('utf-8', array(90,30));
$mpdf->SetTitle('REQUISICAO DE MATERIAL DE CONSUMO');
$mpdf->SetDisplayMode('fullpage');
$mpdf->autoPageBreak = false;
// $mpdf->setHtmlFooter('<div style="text-align:right;">{PAGENO}</div>');
$css = file_get_contents("css/estilo1.css");
$mpdf->WriteHTML($css,1);
$mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
$mpdf->WriteHTML($html);
ob_clean();
$mpdf->Output();
exit;

?>

