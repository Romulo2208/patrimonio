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
echo $this->Form->create('Material', array('class' => 'form-horizontal'));
?>
<input type="text" id="min" placeholder="Data inicial" name="material[inicio][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
<input type="text" id="max" placeholder="Data Final" name="material[final][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>

  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;

  <button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>

<br>

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>NOME</th>
      <th>LOCALIZA&Ccedil;&Atilde;O</th>
      <th>Data da Saida</th>
      <th>QUANTIDADE</th>
      <th>CATEGORIA</th>
      <!-- <th>CONFER&Ecirc;NCIA</th> -->
    </tr>
  </thead>
  <tbody>

    <?php if(isset($this->request->data['material']['inicio'][0])) { ?>
    <?php foreach ($saidas as $saida): ?>
      <tr>
        <td><?php echo h($saida['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($saida['Localizacao']['descricao']); ?>&nbsp;</td>
        <td><?php echo h($saida['Saida']['data_saida']); ?>&nbsp;</td>
        <td><?php echo h($saida['Saida']['quantidade_saida']); ?>&nbsp;</td>
        <td><?php echo h($saida['MaterialClassificacao']['descricao']); ?>&nbsp;</td>
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
