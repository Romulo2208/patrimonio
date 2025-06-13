<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR TRANSFERENCIA DE MATERIAL</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body" style="overflow-y: auto; max-height: 400px; min-width: 650px;">
    <div class="widget-main">
      <?php
      echo $this->Form->create('Remessa', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
      echo $this->Form->input('id', array('type' => 'hidden'));
      ?>

      <div class="row-fluid">
          <div class="input-prepend">
            <label>Unidade Requisitante</label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <select name="data[Remessa][setores_id]" class="input-xlarge select" onchange="getDescricao(this);">
              <option value='0'></option>
              <?php
              foreach ($setores as $key => $value) {
                $selected = $key == $this->request->data['Remessa']['setores_id'] ? 'selected' : null;
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
              <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Pendente','2'=>'Atendido', '3'=>'Em Andamento', '4'=>'Bloqueado', '5'=>'Separado'), 'required' => true, 'class' => 'input-medium')); ?>
          </div>
      </div>

      <span class="materiais" >
        <?php foreach ($itens as $id => $item) { ?>
          <div class="row-fluid" style="margin-top: 10px;">
            <div class="input-prepend">
              <label>Material n&ordm; <small class="count"><?php echo $id+1; ?></small></label>
              <span class="add-on">
                <i class="icon-list"></i>
              </span>
              <span class="input-icon">
                <select name="material[id][]" class="input-xxlarge select" onchange="getDescricao(this);">
                  <option value='0'></option>
                  <?php
                  foreach ($materiais as $key => $value) {
                    $selected = $key == $item['ItemRemessa']['materiais_id'] ? 'selected' : null;
                    echo "<option value='{$key}' {$selected}>{$value}</option>";
                  }
                  ?>
                  <?php
                  // foreach ($materiais as $key => $value) {
                  //     $selected = $key == $item['ItemTransferencia']['materiais_id'] ? 'selected' : null;
                  //   echo "<option value='{$key}' {$selected}>{$value['Material']['barcode']} - {$value['Material']['nome']}</option>";
                  // }
                  ?>
                </select>
                <input type="hidden" name="material[descricao][]" class="input-medium descricao" />
              </span>
            </div>

            <!-- <div class="input-prepend">
              <label>Aplica&ccedil;&atilde;o</label>
              <span class="add-on">
                <i class=" icon-exchange"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="text" name="material[aplicacao][]" class="input-small"  value="<?php //echo $item['ItemCompra']['aplicacao']; ?>" />
              </span>
            </div> -->

            <div class="input-prepend">
              <label>Quantidade</label>
              <span class="add-on">
                <i class="icon-plus"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="number" name="material[remessa][]" class="input-mini" min="0" style="width: 40px;" value="<?php echo $item['ItemRemessa']['quantidade']; ?>" />
              </span>
            </div>

                <input type="hidden" name="material[equipamentos_id][]" class="input-small" min="0" style="width: 40px;" value="<?php echo $item['ItemRemessa']['equipamentos_id']; ?>" />

            <!-- <div class="input-prepend">
              <label>Qtd. Fornecida</label>
              <span class="add-on">
                <i class="icon-plus"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="number" name="material[fornecido][]" class="input-mini" min="0" style="width: 40px;" value="<?php //echo $item['ItemCompra']['quantidade_fornecido']; ?>" <?php echo in_array($this->Session->read('Perfil.id'), array('3')) ? 'readonly' : ''; ?>  />
              </span>
            </div> -->

            <div class="input-prepend">
              <label> &nbsp; </label>
              <span class="input-icon input-icon-right">
                <button type="button" onclick="removeMaterial(this);" class="red"><i class="icon-trash"></i></button>
                <input type="hidden" name="material[item][]" value="<?php echo $item['ItemRemessa']['id']; ?>" />
                <input type="hidden" name="material[pedidos_id][]" value="<?php echo $item['ItemRemessa']['pedidos_id']; ?>" />
                <input type="hidden" name="material[orcamentos_id][]" value="<?php echo $item['ItemRemessa']['orcamentos_id']; ?>" />
                <input type="hidden" name="material[setor_id][]" value="<?php echo $item['ItemRemessa']['setor_id']; ?>" />
              </span>
            </div>
          </div>
        <?php } ?>

        <div class="row-fluid" style="margin-top: 10px;">
          <div class="input-prepend">
            <label>Material n&ordm; <small class="count"><?php echo sizeof($itens)+1; ?></small></label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="material[id][]" class="input-xlarge select" onchange="getDescricao(this);">
                <option value='0'></option>
                <?php
                foreach ($materiais as $key => $value) {
                  echo "<option value='{$key}'>{$key} - {$value}</option>";
                }
                ?>
              </select>
              <input type="hidden" name="material[descricao][]" class="input-medium descricao" />
            </span>
          </div>

          <!-- <div class="input-prepend">
            <label>Aplica&ccedil;&atilde;o</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="material[aplicacao][]" class="input-small"  />
            </span>
          </div> -->

          <div class="input-prepend">
            <label>Quantidade</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="number" name="material[remessa][]" class="input-mini quantidade" min="0" style="width: 40px;" />
            </span>
          </div>

          <!-- <div class="input-prepend">
            <label>Qtd. Fornecida</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="number" name="material[fornecido][]" class="input-mini quantidade" min="0" style="width: 40px;" <?php //echo in_array($this->Session->read('Perfil.id'), array('3')) ? 'readonly' : ''; ?>/>
            </span>
          </div> -->

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
              <input type="hidden" name="material[pedidos_id][]" value="0"/>
            </span>
          </div>
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
$(function () {

});

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}

function addMaterial(e) {
  var field = $(e).parent().parent().parent();
  var clone = $($(field)).clone().appendTo($(field).parent());

  $(clone).find('select, input').val('');
  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeMaterial(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));

}

function removeMaterial(e) {
  $(e).parent().parent().parent().remove();
}
</script>
