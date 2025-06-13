<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>


<?php
echo $this->Form->create('Material', array('class' => 'form-horizontal'));
?>
<div class="control-group">
  <input type="text" id="min" placeholder="Data inicial" name="material[inicio][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>

<div class="control-group">
  <input type="text" id="max" placeholder="Data Final" name="material[final][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>

<div class="input-prepend">
  <label>Filial</label>
  <select name="material[setor][]" class="input-xlarge select" onchange="getDescricao(this);" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?> readonly <?php } ?>>
    <option value='0'></option>
    <?php
    foreach ($setores as $key => $value) {
      $selected = $key == $this->Session->read('Auth.User.setor') ? 'selected' : null;
      echo "<option value='{$key}' {$selected}>{$value}</option>";
    }
    ?>
  </select>
</div>
<br><br>

&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;

<button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>

<br><br><br><br>

<?php if(isset($this->request->data['material']['inicio'][0])) { ?>
  <table class="table table-bordered table-striped" border="1">
    <tr>
      <td colspan="4" style="text-align: center;" bgcolor="#FFD966"><b>CONTROLE DE ENTRADA E SAIDA DE OLEOS</b></td>
    </tr>
    <tr><td></td><td></td><td></td><td></td></tr>
    <tr bgcolor="#A9D08E">
      <td  style="text-align: center;"><b>VEICULO/BEM</b></td>
      <td  style="text-align: center;"><b>DIA</b></td>
      <td  style="text-align: center;"><b>OLEO</b></td>
      <td  style="text-align: center;"><b>QUANTIDADE SAIDA</b></td>
    </tr>
    <?php foreach ($saidas as $saida) { ?>
      <tr>
        <td style="text-align: center;" bgcolor="#D9D9D9"><b><?php echo h($saida['Equipamento']['descricao']); ?></b></td>
        <td style="text-align: center;" bgcolor="#9DC3E6"><b><?php echo h($saida['Saida']['data_saida']); ?></b>&nbsp;</td>
        <td style="text-align: center;" bgcolor="#FFC000"><b><?php echo h($saida['Material']['nome']); ?></b>&nbsp;</td>
        <td style="text-align: center;" bgcolor="#ADB9CA"><b><?php echo h($saida['Saida']['quantidade_saida']); ?></b>&nbsp;</td>
      </tr>
    <?php } ?>

    <tr><td></td><td></td><td></td><td></td></tr>

    <tr>
      <td colspan="4" style="text-align: center;" bgcolor="#FFD966"><b>RESUMO GERAL</b></td>
    </tr>
    <tr>
      <td style="text-align: center;" bgcolor="#00B0F0"><b>MES/ANO</b></td>
      <td style="text-align: center;" bgcolor="#ADB9CA"><b>SAIDA DE DIESEL S-10</b></td>
      <td colspan="2" style="text-align: center;" bgcolor="#00B0F0"><b>FILIAL</b></td>
    </tr>
    <tr>
      <td style="text-align: center;" bgcolor="#00B0F0"><b></b></td>
      <td style="text-align: center;"><b><?php echo h ($somas[0][0]['Soma']); ?></b></td>
      <td colspan="2" style="text-align: center;" bgcolor="#00B0F0"><b> <?php echo h($saidas[0]['Setor']['descricao']); ?></b></td>
    </tr>
    <tr bgcolor="#A9D08E">
      <th colspan="2" style="text-align: center;"><b>VALOR TOTAL SAIDO DE LUBRIFICANTES EM GERAL</b></th>
      <th colspan="2" style="text-align: center;"><b>SALDO ANTERIOR DIESEL S-10</b></th>
    </tr>

    <?php foreach ($totalgeral as $totalgera) {
      $saldoEntrada = $totalgera['t']['quantidade'];
    }
    ?>

    <tr>
      <th colspan="2" style="text-align: center;" bgcolor="#FFFF00"><b><?php echo h ($total[0][0]['Total']); ?></b></th>
      <th colspan="2" style="text-align: center;" bgcolor="#FFFF00"><b><?php echo h (isset($saldoEntrada)); ?></b></th>
    </tr>

    <tr>
      <th colspan="4" style="text-align: center;" bgcolor="#A9D08E"><b>ENTRADA DO DIESEL S-10</b></th>
    </tr>
    <tr>
      <th colspan="2" style="text-align: center;" bgcolor="#A9D08E"><b>DIA</b></th>
      <th colspan="2" style="text-align: center;" bgcolor="#A9D08E"><b>QUANTIDADE</b></th>
    </tr>
    <?php foreach ($entradas as $entrada) { ?>
      <tr bgcolor="#FFFFFF">
        <td colspan="2" style="text-align: center;"><b><?php echo h($entrada['Entrada']['data_entrada']); ?></b>&nbsp;</td>
        <td colspan="2" style="text-align: center;"><b><?php echo h($entrada['Entrada']['quantidade_entrada']); ?></b>&nbsp;</td>
      </tr>
    <?php } ?>

    <tr bgcolor="#A9D08E">
      <td colspan="2" style="text-align: right;"> <b>VALOR TOTAL ENTRADA DE DIESEL:</b></td>
      <th colspan="2" style="text-align: center;"><b><?php echo h ($totalentrada[0][0]['TotalEntrada']); ?></b></th>
    </tr>
    <tr>
      <th colspan="2" style="text-align: center;" bgcolor="#A9D08E"><b>SALDO EM ESTOQUE DE DIESEL S-10:</b></th>
      <th colspan="2" style="text-align: LEFT;" bgcolor="#FFFF00"><b><?php echo $saldodiesel['MaterialFilial']['quantidade']; ?></b></th>
    </tr>

    <tr><td></td><td></td><td></td><td></td></tr>

    <tr bgcolor="#ADB9CA">
      <th style="text-align: center;"><b>NOMES DOS OLEOS</b></th>
      <th style="text-align: center;"><b>ENTRADA</b></th>
      <th style="text-align: center;"><b>SAIDA</b></th>
      <th style="text-align: center;"><b>SALDO LUBRIFICANTES EM ESTOQUE</b></th>
    </tr>
    <?php foreach ($oleoentradas as $oleoentrada) {  ?>
      <?php $saldo = ($oleoentrada['f']['quantidade'] - $oleoentrada[0]['Total_Entrada']) + $oleoentrada[0]['Total_Saida'] ?>
      <tr>
        <td style="text-align: center;" bgcolor="#9DC3E6"><b><?php echo h($oleoentrada['t']['nome']); ?></b>&nbsp;</td>
        <td style="text-align: center;" bgcolor="#FFFFFF"><b><?php echo h($saldoTotal = $saldo + $oleoentrada[0]['Total_Entrada']); ?></b>&nbsp;</td>
        <td style="text-align: center;" bgcolor="#FFD966"><b><?php  echo h($oleoentrada[0]['Total_Saida'])?></b>&nbsp;</td>
        <td style="text-align: center;" bgcolor="#FFFF00"><b><?php echo h($saldoTotal - $oleoentrada[0]['Total_Saida']) ?></b>&nbsp;</td>
      </tr>
    <?php } ?>



  </table>
  <?php

  // header("Content-Type:   application/vnd.ms-excel; charset=utf-8");
  // header("Content-type:   application/x-msexcel; charset=utf-8");
  // header("Content-Disposition: attachment; filename=abc.xls");
  // header("Cache-Control: private",false);
  // header("Expires: 0");
  // header("Cache-Control: must-revalidate, post-check=0, pre-check=0");
  // echo $dadosXls;
  // exit;


  $file = 'relatorio-oleos.xls';
  header ("Content-type: application/x-msexcel");
  header ("Content-Disposition: attachment; filename=\"{$file}\"" );
  header ("Content-Description: PHP Generated Data" );
  exit;
  ?>
<?php } ?>

<script type="text/javascript">
$(document).ready(function () {

  $('#min').datepicker({
    dateFormat: 'yy-mm-dd',
    language: 'pt-BR',
    minDate: 0,
    maxDate: 'today'
  });

  $('#max').datepicker({
    dateFormat: 'yy-mm-dd',
    language: 'pt-BR',
    minDate: 0,
    maxDate: 'today'
  });

});


</script>
