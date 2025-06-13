<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>

<div class="page-header position-relative">
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Equipamentos </small>
  </h1>
</div>

<?php
echo $this->Form->create('material');
?>
<div class="control-group">
  <input type="text" id="min" placeholder="Data inicial" name="material[inicio][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>
<br><br>

<div class="control-group">
  <input type="text" id="max" placeholder="Data Final" name="material[final][]" data-date-format = "dd/mm/yyyy" style="width: 200px; float: left;"/>
</div>
<br><br>

<?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?>
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
<?php }else{ ?>

  <div class="input-prepend">
    <!-- <label>Filial</label> -->
    <select name="material[setor][]" class="input-xlarge hidden" onchange="getDescricao(this);" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?> readonly <?php } ?>>
      <option value='0'></option>
      <?php
      foreach ($setores as $key => $value) {
        $selected = $key == $this->Session->read('Auth.User.setor') ? 'selected' : null;
        echo "<option value='{$key}' {$selected}>{$value}</option>";
      }
      ?>
    </select>
  </div>
<?php } ?>

<div class="row-fluid">
  <label class="control-label" for="SaidaEquipamentosId">Equipamento</label>
  <div class="controls">
    <?php echo $this->Form->input('equipamentos_id', array('label' => false, 'div' => false, 'type'=>'select', 'empty'=>true,  'class' => 'chzn-select', 'data-placeholder' => 'Selecione o Equipamento', 'style'=>'width: 295px;','required' => true)); ?>
  </div>
</div>
<br><br>

&nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;  &nbsp;&nbsp;&nbsp;

<button type="submit" class="btn btn-info" style=" float: left;"><i class="icon-ok icon-white"></i> Pesquisar</button>

<br><br><br><br>

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr bgcolor="#A9D08E">
      <th>VEICULO/BEM</th>
      <th>DIA</th>
      <th>OLEO</th>
      <th>QUANTIDADE SAIDA</th>
    </tr>
  </thead>
  <tbody>

    <?php if(isset($this->request->data['material']['inicio'][0])) { ?>
      <?php foreach ($saidas as $saida): ?>
        <tr>
          <td bgcolor="#D9D9D9"><?php echo h($saida['Equipamento']['descricao']); ?></td>
          <td bgcolor="#9DC3E6"><?php echo h($saida['Saida']['data_saida']); ?>&nbsp;</td>
          <td bgcolor="#FFC000"><?php echo h($saida['Material']['nome']); ?>&nbsp;</td>
          <td bgcolor="#ADB9CA"><?php echo h($saida['Saida']['quantidade_saida']); ?>&nbsp;</td>
        </tr>
      <?php endforeach; ?>
    <?php } ?>
  </tbody>
  <tfoot>
            <tr>
                <th colspan="3" style="text-align:right">Total:</th>
                <th></th>
            </tr>
        </tfoot>
</table>

<script type="text/javascript">
$(document).ready(function() {

  $(".chzn-select").chosen();

  // $('#dyntable').dataTable( {
  //              "language": {
  //                "lengthMenu": 'Display <select>'+
  //                  '<option value="10">10</option>'+
  //                  '<option value="20">20</option>'+
  //                  '<option value="30">30</option>'+
  //                  '<option value="40">40</option>'+
  //                  '<option value="50">50</option>'+
  //                  '<option value="-1">All</option>'+
  //                  '</select> records100'
  //              }
  //            } );
  $(document).ready(function() {
      $('#dyntable').DataTable( {
        "language": {
          "lengthMenu": 'Display <select>'+
            '<option value="10">10</option>'+
            '<option value="25">25</option>'+
            '<option value="50">50</option>'+
            '<option value="100">100</option>'+
            '<option value="-1">All</option>'+
            '</select> records'
        },
          "footerCallback": function ( row, data, start, end, display ) {
              var api = this.api(), data;

              // Remove the formatting to get integer data for summation
              var Number = function ( i ) {
                  return typeof i === 'string' ?
                      i.replace(/[\$,]/g, '')*1 :
                      typeof i === 'number' ?
                          i : 0;
              };

              // Total over all pages
              total = api
                  .column( 3 )
                  .data()
                  .reduce( function (a, b) {
                      return parseFloat(a) + parseFloat(b);
                  }, 0 );

              // Total over this page
              pageTotal = api
                  .column( 3, { page: 'current'} )
                  .data()
                  .reduce( function (a, b) {
                      return parseFloat(a) + parseFloat(b);
                  }, 0 );

              // Update footer
              $( api.column( 3 ).footer() ).html(
                  +pageTotal +' ('+ total +' total)'
              );
          }


      } );
} );


  $('#min').mask('99/99/9999');
  $('#max').mask('99/99/9999');

} );
</script>
