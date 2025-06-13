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
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Entrada </small>
  </h1>
</div>

<?php
echo $this->Form->create('Material', array('class' => 'form-horizontal'));
?>

<input type="text" id="min" placeholder="Data inicial" name="material[inicio][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
<input type="text" id="max" placeholder="Data Final" name="material[final][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>

  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;

  <button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>NOME</th>
      <th>CATEGORIA</th>
      <th>USUARIO</th>
      <th>FILIAL</th>
      <th>Data da Entrada</th>
      <th>QUANTIDADE</th>
      <!-- <th>CONFER&Ecirc;NCIA</th> -->
    </tr>
  </thead>
  <tbody>
    <?php if(isset($this->request->data['material']['inicio'][0])) { ?>
    <?php foreach ($entradas as $entrada): ?>
      <tr>
        <td><?php echo h($entrada['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($entrada['MaterialClassificacao']['descricao']); ?>&nbsp;</td>
        <td><?php echo h($entrada['Usuario']['nome']); ?>&nbsp;</td>
        <td><?php echo h($entrada['Setor']['descricao']); ?>&nbsp;</td>
        <td><?php echo h($entrada['Entrada']['data_entrada']); ?>&nbsp;</td>
        <td><?php echo h($entrada['Entrada']['quantidade_entrada']); ?>&nbsp;</td>
        <!-- <td>&nbsp;</td> -->
      </tr>
    <?php endforeach; ?>
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

  $('#dyntable').DataTable( {
      dom: 'Bfrtip',
      buttons: [
          'copyHtml5',
          'excelHtml5',
          'csvHtml5',
          'pdfHtml5'
      ]
  } );


} );


//$.fn.dataTable.ext.order.intl();
// jQuery.extend(jQuery.fn.dataTableExt.oSort, {
//     "date-uk-pre": function (a) {
//         var ukDatea = a.split('/');
//         return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
//     },
//     "date-uk-asc": function (a, b) {
//         return ((a < b) ? -1 : ((a > b) ? 1 : 0));
//     },
//     "date-uk-desc": function (a, b) {
//         return ((a < b) ? 1 : ((a > b) ? -1 : 0));
//     }
// });
//
// function dateFormat(date) {
//     var dateArr = date.split("/");
//     return dateArr[2] + "-" + dateArr[1] + "-" + dateArr[0];
// }
</script>
