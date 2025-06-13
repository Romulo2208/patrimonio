<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO OR&Ccedil;AMENTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php
      echo $this->Form->create('Orcamento', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
      echo $this->Form->input('situacao', array('type' => 'hidden', 'value' => '1'));
      ?>



      <span class="materiais" >
        <div class="row-fluid" style="margin-top: 10px;">
          <div class="input-prepend">
            <label>Fornecedor </label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="material[fornecedores_id][]" class="input-large select" onchange="getDescricao(this);">
                <option value='0'></option>
                <?php
                foreach ($fornecedores as $key => $value) {
                  echo "<option value='{$key}'>{$value}</option>";
                }
                ?>
              </select>
              <!-- <input type="hidden" name="material[descricao][]" class="input-large descricao" /> -->
            </span>
          </div>

          <div class="input-prepend">
            <label>Prazo de Entrega</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="material[prazo_entrega][]" class="input-medium" min="0" />
            </span>
          </div>

          <div class="input-prepend">
            <label>Cond Pagamento</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="material[cod_pagamento][]" class="input-medium" min="0" />
            </span>
          </div>

          <div class="input-prepend">
            <label>Transportadora</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="material[transportadora][]" class="input-medium" min="0" />
            </span>
          </div>

          <table class="table table-bordered table-striped" width="80% height=20%">
            <thead>
              <tr>
                <td width="20">QT</td>
                <td width="50">MAQ</td>
                <td width="50">FIL</td>
                <td width="130">Descri&ccedil;&atilde;o</td>
                <td width="20">Unitario</td>
                <td width="40">total</td>
              </tr>

            </thead>
            <tbody>

              <?php foreach ($compra as $compra): ?>
                <tr>
                  <td><input type="text" name="data[Teste][quantidade_pedido][]" class="input-mini" min="0" value="<?php echo $compra['ItemCompra']['quantidade_pedido']; ?>" readonly/></td>
                  <td><input type="text" name="data[Teste][aplicacao][]" class="input-medium" min="0" value="<?php echo $compra['ItemCompra']['aplicacao']; ?>" readonly/></td>
                  <td><input type="text" name="data[Teste][setor][]" class="input-small" min="0" value="<?php echo $compra['Setor']['descricao']; ?>" readonly/></td>
                  <td><input type="text" name="data[Teste][nome][]" class="input-large" min="0" value="<?php echo $compra['Material']['nome']; ?>" readonly/></td>
                  <td><input type="text" name="data[Teste][unitario][]" class="input-mini" min="0" /></td>
                  <td><input type="text" name="data[Teste][total][]" class="input-mini" min="0" /></td>
                </tr>
                  <input type="hidden" name="data[Teste][id][]" value="<?php echo $compra['ItemCompra']['materiais_id']; ?>"/>
                  <input type="hidden" name="data[Teste][compras_id][]" value="<?php echo $compra['Compra']['id']; ?>"/>
                  <input type="hidden" name="data[Teste][setor_id][]" value="<?php echo $compra['Setor']['id']; ?>"/>
                  <input type="hidden" name="data[Teste][aplica][]" value="<?php echo $compra['ItemCompra']['aplicacao']; ?>"/>
              <?php endforeach; ?>

            </tbody>
          </table>

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
            </span>
          </div>

        </div>
      </span>


      <!-- <div class="row-fluid" style="margin-top: 10px;">
        <div class="input-prepend">
          <label>Observa&ccedil;&atilde;o</label>
          <?php //echo $this->Form->input('observacao', array('label' => false, 'div' => false, 'type' => 'textarea', 'required' => false, 'class' => 'input-xxlarge')); ?>
        </div>
      </div> -->

      <div class="control-group">
        <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>

<script type="text/javascript">
$(function () {

});

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}

function addMaterial(e) {
  var field = $(e).parent().parent().parent();
  var clone = $($(field)).clone().appendTo($(field).parent());

  $(clone).find('select, input').val();
  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeMaterial(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));
}

function removeMaterial(e) {
  $(e).parent().parent().parent().remove();
}

  $(".chzn-select").chosen();

</script>
