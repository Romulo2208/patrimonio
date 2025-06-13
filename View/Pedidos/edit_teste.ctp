<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR PEDIDO - <?php echo $id; ?></h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body" style="overflow-y: auto; max-height: 400px;">
    <div class="widget-main">
      <?php
      echo $this->Form->create('Pedido', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      date_default_timezone_set('America/Sao_Paulo');
      if($this->Session->read('Perfil.id') == 2){
        echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s', time())));
      }else {
        echo $this->Form->input('data_hora_registro', array('type' => 'hidden'));
      }
      //echo $this->Form->input('data_hora_registro', array('type' => 'hidden'));
      echo $this->Form->input('id', array('type' => 'hidden'));
      ?>


      <div class="row-fluid">
          <div class="input-prepend">
            <label>Unidade Requisitante</label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <select name="data[Pedido][setores_id]" class="input-xlarge select" onchange="getDescricao(this);">
              <option value='0'></option>
              <?php
              foreach ($setores as $key => $value) {
                $selected = $key == $this->request->data['Pedido']['setores_id'] ? 'selected' : null;
                echo "<option value='{$key}' {$selected}>{$value}</option>";
              }
              ?>
            </select>
          </div>

          <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?>
          <div class="input-prepend">
              <label>Situa&ccedil;&atilde;o</label>
              <span class="add-on">
                <i class="icon-exchange"></i>
              </span>
              <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Pendente','2'=>'Atendido', '3'=>'Antendido Parcialmente', '4'=>'Bloqueado'), 'required' => true, 'class' => 'input-medium')); ?>
          </div>
          <?php } ?>


          <?php if(in_array($this->Session->read('Perfil.id'), array('2'))) { ?>
              <div class="input-prepend">
                  <label>Parecer do Gerente</label>
                  <span class="add-on">
                    <i class="icon-exchange"></i>
                  </span>
                  <?php echo $this->Form->input('aprovacao', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Aprovado','2'=>'Bloqueado'), 'required' => true, 'class' => 'input-medium')); ?>
              </div>
              <?php } ?>

      </div>

      <span class="materiais" >
        <?php foreach ($itens as $id => $item) { ?>
          <div class="row-fluid" style="margin-top: 10px;">
            <div class="input-prepend">
              <label>Material n&ordm; <small class="count"><?php echo $id+1; ?></small></label>
              <span class="add-on">
                <i class="icon-list"></i>
              </span>
              <!-- <span class="input-icon"> -->
                <select name="material[id][]" class="chzn-select" style="width: 0px" onchange="getDescricao(this);">
                  <option value='0'></option>
                  <?php
                  foreach ($materiais2 as $key => $value) {
                    $selected = $value['Material']['id'] == $item['ItemPedido']['materiais_id'] ? 'selected' : null;
                    echo "<option value='{$value['Material']['id']}' {$selected}>{$value['Material']['nome']}</option>";
                  }
                  ?>
                </select>
                <input type="hidden" name="material[descricao][]" class="input-large descricao" />
              <!-- </span> -->
            </div>

            <?php if(!in_array($this->Session->read('Perfil.id'), array('2','3'))) { ?>
              <div class="input-prepend">
                <label>Estoque</label>
                  <select name="" class="input-small select" disabled="disabled" onchange="getDescricao(this);">
                    <?php
                    foreach ($materiais2 as $key => $value) {
                      $selected = $value['Material']['id'] == $item['ItemPedido']['materiais_id'] ? 'selected' : null;
                      echo "<option value='{$value['Material']['id']}' {$selected}>{$value['Material']['quantidade_central']}</option>";
                    }
                    ?>
                  </select>
              </div>
            <?php } ?>

            <div class="input-prepend">
              <label>Aplica&ccedil;&atilde;o</label>
              <span class="add-on">
                <i class="icon-list"></i>
              </span>
              <span class="input-icon">
                <select name="material[equipamentos_id][]" class="chzn-select1" style="width: 0px" data-placeholder = "Selecione o equipamento">
                  <option value='0'></option>
                  <?php
                  foreach ($equipamentos as $key => $value) {
                    $selected = $value['Equipamento']['id'] == $item['ItemPedido']['equipamentos_id'] ? 'selected' : null;
                    echo "<option value='{$value['Equipamento']['id']}' {$selected}>{$value['Equipamento']['descricao']}</option>";
                  }
                  ?>
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
                <input type="hidden" name="material[aplicacao][]" class="input-medium" min="0" value="<?php echo $item['ItemPedido']['aplicacao']; ?>" />
              <!-- </span>
            </div> -->

            <!-- <div class="input-prepend">
              <label>QTD. Estoque</label>
              <span class="add-on">
                <i class=" icon-exchange"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="text" class="input-mini" min="0" readonly />
              </span>
            </div> -->

            <div class="input-prepend">
              <label>Qtd. Pedida</label>
              <span class="add-on">
                <i class="icon-plus"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="number" name="material[pedido][]" class="input-mini" min="0" value="<?php echo $item['ItemPedido']['quantidade_pedido']; ?>" />
              </span>
            </div>

            <div class="input-prepend">
              <label>Qtd. Fornecida</label>
              <span class="add-on">
                <i class="icon-plus"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="number" name="material[fornecido][]" class="input-mini" min="0" value="<?php echo $item['ItemPedido']['quantidade_fornecido']; ?>" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?> readonly <?php } ?> />
              </span>
            </div>

            <input type="hidden" name="material[transferido][]" class="input-mini" min="0" value="<?php echo $item['ItemPedido']['material_transferido']; ?>" />

            <div class="input-prepend">
              <label> &nbsp; </label>
              <span class="input-icon input-icon-right">
                <button type="button" onclick="removeMaterial(this);" class="red"><i class="icon-trash"></i></button>
                <input type="hidden" name="material[item][]" value="<?php echo $item['ItemPedido']['id']; ?>" />
              </span>
            </div>

            &nbsp;&nbsp;&nbsp;&nbsp;


          </div>
        <?php } ?>

        <div class="row-fluid" style="margin-top: 10px;">
          <div class="input-prepend">
            <label>Material n&ordm; <small class="count"><?php echo sizeof($itens)+1; ?></small></label>
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
              <input type="number" name="material[pedido][]" class="input-mini quantidade" min="0" />
            </span>
          </div>

          <div class="input-prepend">
            <label>Qtd. Fornecida</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="number" name="material[fornecido][]" class="input-mini quantidade" min="0" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?> readonly <?php } ?>/>
            </span>
          </div>

          <input type="hidden" name="material[transferido][]" class="input-mini" min="0"/>

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
            </span>
          </div>
        </div>
      </span>

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;



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

  $(clone).find('.chzn-select').chosen({width: "500px"});
  $(clone).find('.chzn-select1').chosen({width: "290px"});
  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeMaterial(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));

}

function removeMaterial(e) {
  $(e).parent().parent().parent().remove();
}

  //$(".chzn-select").chosen();
  $(function () {

    globalField = "<div class=\"row-fluid\" style=\"margin-top: 10px;\">" + $("span.materiais .row-fluid:last").html() + "</div>";

    $(".chzn-select").chosen({width: "500px"});
    $(".chzn-select1").chosen({width: "290px"});

  });

</script>
