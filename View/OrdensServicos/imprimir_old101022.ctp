<?php
// pr($assinatura[0]['b']['nome']);exit;
// pr($totalservico[0][0]['Total']);exit;
$data = $ordem['OrdemServico']['data'];
$dataConclusao = $ordem['OrdemServico']['data_conclusao'];
$dataAssinatura = $ordem['OrdemServico']['data_assinatura'];
$html = "";
// $html .= "<div class='container'>";
// $html .= "<div class='tabbable'>";

$html .= "<div class='container'>";
$html .= "<div class='op1'>";

$html .= "<div class='inicial'>";
if($ordem['OrdemServico']['setores_id']== 8){
  $html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logoamerical.jpeg' height='20' width='90';/ ><b>                     <font size='1'><u>{$ordem['Setor']['razao_social']}</u></font></b></p>";
}elseif ($ordem['OrdemServico']['setores_id']== 7) {
  $html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logocalta.jpeg' height='20' width='90';/ ><b>                     <font size='1'><u>{$ordem['Setor']['razao_social']}</u></font></b></p>";
}elseif ($ordem['OrdemServico']['setores_id']== 6) {
  $html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logoemfol.jpeg' height='20' width='90';/ ><b>                     <font size='1'><u>{$ordem['Setor']['razao_social']}</u></font></b></p>";
}else {
  $html .= "<p align='left'> <img src='http://localhost/patrimonio/img/logobritacanova.png' height='20' width='90';/ ><b>                     <font size='1'><u>{$ordem['Setor']['razao_social']}</u></font></b></p>";
}
$html .= "<p align='center'><font size='1'>{$ordem['Setor']['endereco']} <br>{$ordem['Setor']['cep']} - {$ordem['Setor']['cidade']} {$ordem['Setor']['uf']} - {$ordem['Setor']['descricao']}</font></p>";
// $html .= "<p align='center'>CEP:73900-000 - Posse GO</p>";
$html .= "<p align='left'> <font size='1'><b>Fone Escritorio:</b>{$ordem['Setor']['telefone']}</font></p>";
$html .= "<p align='right'><font size='1'><b>Fone Industria:</b>{$ordem['Setor']['telefone']}</font></p>";
$html .= "</div>";

$html .= "<div class='fornecedor'>";
$html .= "<p align='left'><font size='3'><b>Fornecedor:</b></font><font size='2'>{$ordem['OrdemServico']['fornecedor']}</font></p>";
$html .= "<br>";
$html .= "<p align='left'><font size='3'><b>Observa&ccedil;&otilde;es:</b></font><font size='2'>{$ordem['OrdemServico']['observacao']}</font></p>";
$html .= "</div>";

$html .= "<div class='tabelaD'>";
$html .= "<table border='1' width='100%'>";
$html .= "<thead>";
$html .= "<tr>";
$html .= "<td><font size='3'><b>Descrimina&ccedil;&atilde;o dos Servi&ccedil;os</b></font></td>";
$html .= "<td><font size='4'><b>Pre&ccedil;os</b></font></td>";
$html .= "</tr>";
foreach ($servico as $key => $value) {
$precoservico = number_format($value['ServicoItem']['precos'],2,",",".");
  $html .= "<tr>";
  $html .= "<td><font size='2'>{$value['ServicoItem']['descricao']}</font></td>";
  $html .= "<td><font size='3'>r&#36; {$precoservico}</font></td>";
  $html .= "</tr>";
}
//$preco = number_format($totalservico[0][0]['Total'],2,",",".");
$descontoServico = number_format($ordem['OrdemServico']['desconto_servico'],2,",",".");
$total = $totalservico[0][0]['Total'] - $ordem['OrdemServico']['desconto_servico'];
$totalGeral = number_format($total,2,",",".");
$html .= "<tr>";
$html .= "<td style='text-align: right;'><font size='2'><b>DESCONTO:</font></b></td>";
$html .= "<td><font size='3'><b>r&#36; {$descontoServico}</b></font></td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td style='text-align: right;'><font size='2'><b>TOTAL:</font></b></td>";
$html .= "<td><font size='3'><b>r&#36; {$totalGeral}</b></font></td>";
$html .= "</tr>";
$html .= "</thead>";
$html .= "</table>";
// $html .= "<p align='right'><b>TOTAL:{$totalservico[0][0]['Total']}</b></p>";
$html .= "</div>";

$html .= "</div>";

$html .= "<div class='op2'>";
$html .= "<div class='inicialE'>";

$html .= "<div class='ladoE2'>";
$html .= "<div class='ladoE3'>";
$html .= "<p align='center'><b>ORDEM DE SERVI&Ccedil;O</b></p>";
$html .= "<p align='center'><font size='3'>{$ordem['OrdemServico']['id']}</font></p>";
// $html .= "<br>";
$html .= "<p align='center'><b>DATA DE ENTRADA</b></p>";
$html .= "<p align='center'><font size='3'>{$data}</font></p>";
$html .= "</div>";
$html .= "</div>";

$html .= "<div class='ladoE'>";
$html .= "<p align='center'><font size='3'><b>Servi&ccedil;o Execultado</b></font><br>{$situacao}</p>";
if ($ordem['OrdemServico']['situacao'] == 1) {
$html .= "<p align='center'><font size='2'>Oficina</font><br></p>";
}else {
  $html .= "<p align='center'><font size='2'>Industria</font><br></p>";
}
// $html .= "<br>";
$html .= "<p align='left'><font size='3'><b>Equipamento:</b></font><font size='2'>{$ordem['OrdemServico']['equipamento']} </font></p>";
$html .= "</div>";
$html .= "</div>";

$html .= "<div class='especi'>";
$html .= "<p align='left'><font size='2'><b>Especifica&ccedil;&atilde;o dos Servi&ccedil;os:</b></font><font size='1'>{$ordem['OrdemServico']['especificacao_servico']}</font></p>";
$html .= "<br>";

$html .= "</div>";

$html .= "<table border='1' width='100%'>";
$html .= "<thead>";
$html .= "<tr>";
$html .= "<td><font size='3'><b>Quant.</b></font></td>";
$html .= "<td style='text-align: center;'><font size='3'><b>Pe&ccedil;as e Acessorios</b></font></td>";
$html .= "<td><font size='3'><b>Pre&ccedil;os</b></font></td>";
$html .= "</tr>";
foreach ($pecas as $key => $value) {
$precopecas = number_format($value['ServicoPeca']['precos'],2,",","."); //aqui
  // pr($value);exit;
$html .= "<tr>";
$html .= "<td style='text-align: center;'><font size='2'>{$value['ServicoPeca']['quantidade']}</font></td>";
$html .= "<td style='text-align: center;'><font size='2'>{$value['ServicoPeca']['pecas_acessorios']}</font></td>";
//$html .= "<td style='text-align: center;'><font size='2'>r&#36; {$value['ServicoPeca']['precos']}</font></td>";
$html .= "<td style='text-align: center;'><font size='2'>r&#36; {$precopecas}</font></td>";
$html .= "</tr>";
}
$precopecatotal = number_format($ordem['OrdemServico']['desconto_peca'],2,",",".");
$totalP = $totalpeca[0][0]['Total'] - $ordem['OrdemServico']['desconto_peca'];
$totalGeralP = number_format($totalP,2,",",".");
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align: right;'><font size='2'><b>DESCONTO:</font></b></td>";
$html .= "<td><font size='3'><b>r&#36; {$precopecatotal}</b></font></td>";
$html .= "</tr>";
$html .= "<tr>";
$html .= "<td></td>";
$html .= "<td style='text-align: right;'><font size='2'><b>TOTAL:</font></b></td>";
//$html .= "<td><font size='3'><b>r&#36; {$totalpeca[0][0]['Total']}</b></font></td>";
$html .= "<td><font size='3'><b>r&#36; {$totalGeralP}</b></font></td>";
$html .= "</tr>";
$html .= "</thead>";
$html .= "</table>";

// $html .= "<p align='right'><b>TOTAL:</b></p>";
$html .= "<br>";
$html .= "<p align='left'><font size='3'><b>Conclus&atilde;o / Prazo para Pagamento:</b><font size='1'> <br>{$ordem['OrdemServico']['conclusao']}</font></p>";
$html .= "<br><br><br>";
// $html .= "<p align='left'><font size='2'><b>Prazo para Pagamento:</b></font></p>";
$html .= "<br><br><br>";
$html .= "<p align='right'><b>DATA:</b><font size='4'>{$dataConclusao}</font></p>";

$html .= "<br>";
$html .= "<div class='filial'>";
$html .= "<div class='assinatura'>";
if($assinatura[0]['b']['nome'] <>''){
  $html .= "<p align='center'><font size='1'>{$assinatura[0]['b']['']}<br><br></font><br><b>_________________________</b></p>";
  //$html .= "<p align='center'><font size='1'><b>       GERENTE FILIAL</b></font></p>";
 $html .= "<p align='center'><font size='0'>{$assinatura[0]['b']['nome']}<br></font></p>";
}else{
  $html .= "<p align='center'> <font size='1' style='color:red'><b>FALTA AUTORIZACAO</b></font><br><b>_________________________</b></p>";
  $html .= "<p align='center'><font size='1'><b>       GERENTE FILIAL</b></font></p>";
// $html .= "<p align='center' style='color:red'><font size='1'><b>FALTA AUTORIZACAO</b></font></p>";
}
// $html .= "<p align='left'><b>_________________________</b></p>";
// $html .= "<p align='center'><font size='1'><b>       GERENTE FILIAL</b></font></p>";
$html .= "</div>";

$html .= "<div class='executado'>";
if($assinatura[0]['b']['nome'] <>''){
  $html .= "<p align='center'><font size='1'></font></p>";
  $html .= "<p align='center'><font size='1'></font></p>";
  //$html .= "<p align='center'><font size='1'></font></p>";
}else{
  $html .= "<p align='center'><font size='2'></font></p>";
  // $html .= "<p align='center'><font size='1'></font></p>";
}
$html .= "<p align='right'><b>_________________________</b></p>";
$html .= "<p align='center'><font size='2'>       EXECULTADO POR</font></p>";
$html .= "</div>";
$html .= "</div>";

$html .= "</div>";
$html .= "</div>";
// $html .= "</div>";
// $html .= "</div>";
 ?>
<?php

$mpdf = new mPDF();
$mpdf->SetTitle('REQUISICAO DE MATERIAL DE CONSUMO');
$mpdf->SetDisplayMode('fullpage');
$css = file_get_contents("css/estilo.css");
$mpdf->WriteHTML($css,1);
$mpdf->Image('logobritacal.png', 0, 0, 210, 297, 'png', '', true, false);
$mpdf->WriteHTML("<style> th, td { border: 1px solid #000; padding: 2px; font-size: 14px; padding: 10px; text-transform: uppercase;} td { border-bottom: 0; border-right: 0;} </style>");
$mpdf->WriteHTML($html);
ob_clean();
$mpdf->Output();
exit;

?>
