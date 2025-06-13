<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>

<div class="page-header position-relative">
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Oleos </small>
  </h1>
</div>

<?php
echo $this->Form->create('Material', array('class' => 'form-horizontal'));
?>
<input type="text" id="min" placeholder="Data inicial" name="material[inicio][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
<input type="text" id="max" placeholder="Data Final" name="material[final][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>

  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;

  <button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>

<br><br><br>

<table class="table table-bordered table-striped">
    <tr>
      <th colspan="4" style="text-align: center;">CONTROLE DE ENTRADA E SAIDA DE OLEOS</th>
    </tr>
    <tr>
      <th>VEICULO/BEM</th>
      <th>DIA</th>
      <th>OLEO</th>
      <th>QUANTIDADE SAIDA</th>
    </tr>
    <tr>
      <th colspan="4" style="text-align: center;">RESUMO GERAL</th>
    </tr>
    <tr>
      <th>JULHO/2020</th>
      <th>SAIDA DE DIESEL S-10</th>
      <th colspan="2" style="text-align: center;">FILIAL 02</th>
    </tr>
    <tr>
      <th colspan="2" style="text-align: center;">VALOR TOTAL SAIDO DE LUBRIFICANTES EM GERAL</th>
      <th colspan="2" style="text-align: center;">SALDO ANTERIOR DIESEL S-10</th>
    </tr>
    <tr>
      <th colspan="4" style="text-align: center;">ENTRADA DO DIESEL S-10</th>
    </tr>
    <tr>
      <th colspan="2" style="text-align: center;">DIA</th>
      <th colspan="2" style="text-align: center;">QUANTIDADE</th>
    </tr>
    <tr>
      <th>SALDO ANTERIOR DIESEL S-10</th>
      <th>VALOR TOTAL ENTRADA DE DIESEL</th>
      <th colspan="2" style="text-align: center;">SALDO EM ESTOQUE DE DIESEL S-10</th>
    </tr>
    <tr>
      <th>NOMES DOS OLEOS</th>
      <th>N</th>
      <th>ENTRADA</th>
      <th>SAIDA</th>
    </tr>
    <tr>
      <th colspan="4" style="text-align: center;">SALDO DE LUBRIFICANTES EM GERAL NO ESTOQUE</th>
    </tr>
    <tr>
      <th colspan="2" style="text-align: center;">LUBRIFICANTE</th>
      <th colspan="2" style="text-align: center;">SALDO</th>
    </tr>

  <tbody>

    <?php if(isset($this->request->data['material']['inicio'][0])) { ?>
      <tr>
        <td colspan="4"></td>
      </tr>
    <?php foreach ($saidas as $saida) { ?>
      <tr>
        <td></td>
        <td><?php echo h($saida['Saida']['data_saida']); ?>&nbsp;</td>
        <td><?php echo h($saida['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($saida['Saida']['quantidade_saida']); ?>&nbsp;</td>
      </tr>
    <?php } ?>
    <tr>
      <td colspan="4"></td>
    </tr>
    <?php } ?>
  </tbody>

</table>

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
