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

<!-- <div class="page-header position-relative">
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Materiais </small>
  </h1>
</div> -->

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>NOME</th>
      <th>CLASSIFICA&Ccedil;&Atilde;O</th>
      <th>QUANTIDADE</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($compras as $compra): ?>
      <tr>
        <td><?php echo h($compra['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($compra['Classificacao']['descricao']); ?>&nbsp;</td>
        <td><?php echo h($compra['ItemCompra']['quantidade_pedido']); ?>&nbsp;</td>
      </tr>
    <?php endforeach; ?>
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
