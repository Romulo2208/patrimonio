<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO CARREGAMENTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php
      echo $this->Form->create('Carregamento', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('setores_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.setor')));
      echo $this->Form->input('situacao', array('type' => 'hidden', 'value' => 0));
      echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
      ?>

        <div class="control-group">
          <label>Placa</label>
          <span class="add-on">
            <i class="icon-truck"></i>
          </span>
          <span class="input-icon input-icon-right">
            <input type="text" name="Carregamento[placa]" class="input-small" min="0" />
          </span>
        </div>

          <div class="control-group">
            <label>Produto</label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="Carregamento[produtos_id]" class="chzn-select" style="width: 0px" data-placeholder = "Selecione o produto" onchange="getDescricao(this);">
                <option value='0'></option>
                <?php
                foreach ($produtos as $key => $value) {
                  echo "<option value='{$key}' {$selected}>{$value}</option>";
                }
                ?>
              </select>
              <!-- <input type="hidden" name="Produto[descricao][]" class="input-large descricao" /> -->
            </span>
          </div>

          <div class="control-group">
            <label>Quantidade</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="Carregamento[quantidade]" class="input-mini" min="0" oninput="maskReal(this)"/>
            </span>
          </div>

           <div class="row-fluid" style="margin-top: 10px;">
            <div class="input-prepend">
              <label>Observa&ccedil;&atilde;o</label>
              <?php echo $this->Form->input('observacao', array('label' => false, 'div' => false, 'type' => 'textarea', 'required' => false, 'class' => 'input-xxlarge')); ?>
            </div>
          </div>

        <!-- </div> -->


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


  $(".chzn-select").chosen({width: "500px"});

function maskReal(input) {
    // Remove todos os caracteres exceto números e ponto decimal
    input.value = input.value.replace(/[^\d.]/g, '');

    // Verifica se há mais de um ponto decimal
    if ((input.value.match(/\./g) || []).length > 1) {
        input.value = input.value.slice(0, -1); // Remove o último caractere
    }
}
</script>
