<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

<!-- <script src="https://code.jquery.com/jquery-3.5.1.js"></script> -->
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>

<div class="page-header position-relative">
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Saidas </small>
  </h1>
</div>

<?php
echo $this->Form->create('Material');
?>


<div class="control-group">
<input type="text" id="min" placeholder="Data inicial" name="orcamento[inicio]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>

<br><br>

<div class="control-group">
<input type="text" id="max" placeholder="Data Final" name="orcamento[final]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>

<br><br>



  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;
  <div class="control-group">
    <button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>
  </div>

<br><br>

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>NOME</th>
      <th>FORNECEDOR</th>
      <th>DATA DO ORCAMENTO</th>
      <th>QUANTIDADE</th>
      <th>PRECO UNITARIO</th>
      <th>PRECO TOTAL DO PRODUTO</th>
      <!-- <th>CONFER&Ecirc;NCIA</th> -->
    </tr>
  </thead>
  <tbody>

    <?php if(isset($this->request->data['orcamento']['inicio'])) { ?>
    <?php foreach ($orcamentos as $orcamento): ?>
      <tr>
        <td><?php echo h($orcamento['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($orcamento['Fornecedor']['nome_fantasia']); ?>&nbsp;</td>
        <td><?php echo h($orcamento['Orcamento']['data_hora_registro']); ?>&nbsp;</td>
        <td><?php echo h($orcamento['ItemOrcamento']['quantidade']); ?>&nbsp;</td>
        <td><?php echo h($orcamento['ItemOrcamento']['unitario']); ?>&nbsp;</td>
        <td><?php echo h($orcamento['ItemOrcamento']['total']); ?>&nbsp;</td>
        <!-- <td>&nbsp;</td> -->
      </tr>
    <?php endforeach; ?>
    <?php } ?>
  </tbody>
</table>

<script type="text/javascript">
$(document).ready(function () {

  $('#dyntable').DataTable( {
      dom: 'Bfrtip',
      buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdfHtml5'
      ]
  } );



  $('#min').mask('99/99/9999');
  $('#max').mask('99/99/9999');

} );
</script>
