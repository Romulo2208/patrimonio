<?php

$arquivo = "planilha.xls";

$html ="";

$html .="<table>";
$html .="<thead>";
$html .="<tr>";
$html .="<th>NOME</th>";
$html .="<th>USUARIO</th>";
$html .="<th>Data da Entrada</th>";
$html .="<th>QUANTIDADE</th>";
$html .="<tr>";
$html .="</thead>";
$html .="<tbody>";

    foreach ($entradas as $entrada){

      $html .= "<tr>";

         $html .="<td>{$entrada['Material']['nome']}</td>";
         $html .="<td>{$entrada['Usuario']['nome']}</td>";
         $html .="<td>{$entrada['Entrada']['data_entrada']}</td>";
         $html .="<td>{$entrada['Entrada']['quantidade_entrada']}</td>";

      $html .="</tr>";
    }
  $html .=  "</tbody>";
  $html .=  "</table>";


header ("Expires: Mon, 26 Jul 1997 05:00:00 GMT");
header ("Last-Modified: " . gmdate("D,d M YH:i:s") . " GMT");
header ("Cache-Control: no-cache, must-revalidate");
header ("Pragma: no-cache");
header ("Content-type: application/x-msexcel");
header ("Content-Disposition: attachment; filename=\"{$arquivo}\"" );
header ("Content-Description: PHP Generated Data" );
// Envia o conteúdo do arquivo
echo $html;
exit;
?>













<?php echo $this->Html->script( array( 'entrada2' ));?>
