<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>

<link rel="stylesheet" href="https://cdn.datatables.net/1.10.22/css/jquery.dataTables.min.css">
<link rel="stylesheet" href="https://cdn.datatables.net/buttons/1.6.4/css/buttons.dataTables.min.css">

<script src="https://code.jquery.com/jquery-3.5.1.js"></script>
<script src="https://cdn.datatables.net/1.10.22/js/jquery.dataTables.min.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/dataTables.buttons.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jszip/3.1.3/jszip.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/pdfmake.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/pdfmake/0.1.53/vfs_fonts.js"></script>
<script src="https://cdn.datatables.net/buttons/1.6.4/js/buttons.html5.min.js"></script>

<?php
echo $this->Form->create('Carregamento', array('class' => 'form-horizontal'));
?>



<table id="dyntable" class="display" style="width:100%">
  <thead>
    <tr bgcolor="#A9D08E">
      <th  style="text-align: center;"><b>PLACA</b></th>
      <th  style="text-align: center;"><b>PRODUTO</b></th>
      <th  style="text-align: center;"><b>QUANTIDADE</b></th>
      <th  style="text-align: center;"><b>DATA</b></th>
      <th  style="text-align: center;"><b>FILIAL</b></th>
      <!-- <th>CONFER&Ecirc;NCIA</th> -->
    </tr>
  </thead>
  <tbody>

    <?php //if(isset($this->request->data['material']['inicio'][0])) { ?>
      <?php foreach ($carregamentos as $carregamento): ?>
        <tr>
          <td style="text-align: center;" bgcolor="#D9D9D9"><b><?php echo h($carregamento['Carregamento']['placa']); ?></b></td>
          <td style="text-align: center;" bgcolor="#9DC3E6"><b><?php echo h($carregamento['Produto']['descricao']); ?></b>&nbsp;</td>
          <td style="text-align: center;" bgcolor="#FFC000"><b><?php echo h($carregamento['Carregamento']['quantidade']); ?></b>&nbsp;</td>
          <td style="text-align: center;" bgcolor="#ADB9CA"><b><?php echo date('d/m/Y', strtotime($carregamento['Carregamento']['data_hora_registro'])); ?></b>&nbsp;</td>
          <td style="text-align: center;" bgcolor="#ADB9CA"><b><?php echo h($carregamento['Setor']['descricao']); ?></b>&nbsp;</td>
          <!-- <td>&nbsp;</td> -->
        </tr>
      <?php endforeach; ?>
    <?php //} ?>
  </tbody>
</table>
<script>
$(document).ready(function() {
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

</script>
