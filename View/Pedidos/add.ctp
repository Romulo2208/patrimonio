<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO PEDIDO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php
      echo $this->Form->create('Pedido', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('solicitante', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      date_default_timezone_set('America/Sao_Paulo');
      echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', time())));
      ?>
      <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) {
        echo $this->Form->input('aprovacao', array('type' => 'hidden', 'value' => 1));
      }
      ?>

      <?php

      // foreach ($materiais as $key => $value) {
      //
      // pr($value);exit;

    //}
      ?>

      <div class="row-fluid">
          <div class="input-prepend">
            <label>Unidade Requisitante</label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <select name="data[Pedido][setores_id]" class="input-xlarge select" onchange="getDescricao(this);" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?> readonly <?php } ?>>
              <option value='0'></option>
              <?php
              foreach ($setores as $key => $value) {
                $selected = $key == $this->Session->read('Auth.User.setor') ? 'selected' : null;
                echo "<option value='{$key}' {$selected}>{$value}</option>";
              }
              ?>
            </select>
          </div>
          <div class="input-prepend">
              <label>Situa&ccedil;&atilde;o</label>
              <span class="add-on">
                <i class="icon-exchange"></i>
              </span>
              <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Pendente'), 'required' => true, 'class' => 'input-medium')); ?>
          </div>
      </div>

      <span class="materiais" >
        <div class="row-fluid" style="margin-top: 10px;">
          <div class="input-prepend">
            <label>Material n&ordm; <small class="count">1</small></label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="material[id][]" class="chzn-select" style="width: 0px" data-placeholder = "Selecione o produto" onchange="getDescricao(this);">
                <option value='0'></option>

                <?php if(sizeof($materiais)){ ?>
                <?php foreach ($materiais as $value) { ?>
                  <option value="<?php echo $value['Material']['id']; ?>">
                    <?php echo $value['Material']['nome']; ?></option>
                    <?php }} ?>
              </select>
              <input type="hidden" name="material[descricao][]" class="input-large descricao" />
            </span>
          </div>

          <div class="input-prepend">
            <label>Aplica&ccedil;&atilde;o</label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="material[equipamentos_id][]" class="chzn-select1" style="width: 0px" data-placeholder = "Selecione o equipamento">
                <option value='0'></option>

                <?php if(sizeof($equipamentos)){ ?>
                <?php foreach ($equipamentos as $value) { ?>
                  <option value="<?php echo $value['Equipamento']['id']; ?>">
                    <?php echo $value['Equipamento']['descricao']; ?></option>
                    <?php }} ?>
              </select>
              <!-- <input type="hidden" name="material[descricao][]" class="input-large descricao" /> -->
            </span>
          </div>

          <!-- <div class="input-prepend">
            <label>Aplica&ccedil;&atilde;o</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right"> -->
              <input type="hidden" name="material[aplicacao][]" class="input-medium" min="0" />
            <!-- </span>
          </div> -->

          <div class="input-prepend">
            <label>Qtd. Pedida</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="number" name="material[pedido][]" class="input-mini" min="0" />
            </span>
          </div>

          <!-- <div class="input-prepend">
            <label>Qtd. Fornecida</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="number" name="material[fornecido][]" class="input-mini" min="0" readonly />
            </span>
          </div> -->

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
            </span>
          </div>

        &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;

        </div>
      </span>


      <div class="row-fluid" style="margin-top: 10px;">
        <div class="input-prepend">
          <label>Observa&ccedil;&atilde;o</label>
          <?php echo $this->Form->input('observacao', array('label' => false, 'div' => false, 'type' => 'textarea', 'required' => false, 'class' => 'input-xxlarge')); ?>
        </div>
      </div>

      <div class="control-group">
        <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>

<script type="text/javascript">
// $(function () {
//
//   $(".chzn-select").chosen({width: "500px"});
//
// });

var globalField;

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}

function addMaterial(e) {

  var field = $(e).parent().parent().parent();
  $("span.materiais").append(globalField);

  var clone = $("span.materiais .row-fluid:last");

  $(clone).find('select, input').val('');
  $(clone).find('.chzn-select').val('');
  $(clone).find('.chzn-select1').val('');

  $(clone).find('.chzn-select').chosen({width: "600px"});
  $(clone).find('.chzn-select1').chosen({width: "300px"});
  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeMaterial(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));

}

function removeMaterial(e) {
  $(e).parent().parent().parent().remove();
}

  //$(".chzn-select").chosen();
  $(function () {

    globalField = $("span.materiais").html();

    $(".chzn-select").chosen({width: "600px"});
    $(".chzn-select1").chosen({width: "300px"});

  });

</script>
