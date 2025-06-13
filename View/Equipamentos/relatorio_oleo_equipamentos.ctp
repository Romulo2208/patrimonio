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
      <td  style="text-align: center;"><b>VEICULO/BEM</b></td>
      <td  style="text-align: center;"><b>DIA</b></td>
      <td  style="text-align: center;"><b>OLEO</b></td>
      <td  style="text-align: center;"><b>QUANTIDADE SAIDA</b></td>
      <!-- <th>CONFER&Ecirc;NCIA</th> -->
    </tr>
  </thead>
  <tbody>

    <?php if(isset($this->request->data['material']['inicio'][0])) { ?>
      <?php foreach ($saidas as $saida): ?>
        <tr>
          <td style="text-align: center;" bgcolor="#D9D9D9"><b><?php echo h($saida['Equipamento']['descricao']); ?></b></td>
          <td style="text-align: center;" bgcolor="#9DC3E6"><b><?php echo h($saida['Saida']['data_saida']); ?></b>&nbsp;</td>
          <td style="text-align: center;" bgcolor="#FFC000"><b><?php echo h($saida['Material']['nome']); ?></b>&nbsp;</td>
          <td style="text-align: center;" bgcolor="#ADB9CA"><b><?php echo h($saida['Saida']['quantidade_saida']); ?></b>&nbsp;</td>
          <!-- <td>&nbsp;</td> -->
        </tr>
      <?php endforeach; ?>
    <?php } ?>
  </tbody>
</table>

<script type="text/javascript">
$(document).ready(function () {

  $(".chzn-select").chosen();

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
