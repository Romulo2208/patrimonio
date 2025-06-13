<div class="page-header position-relative">
  <h1>
    <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Compras'); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">



      <div class="tab-content">
        <div id="item" class="tab-pane in active">

          <?php echo $this->Form->create('Orcamento', array('class'=>'form-horizontal'));
          echo $this->Form->input('id', array('required' => true));
          echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
          echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
          ?>
          <!-- <small class="count">1</small> -->
          <span class="materiais" >
          <p>
            <div class="input-prepend">
              <label class="control-label" for="OrcamentoFornecedoresId">Fornecedor</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-briefcase"></i>
                </span>
                <?php echo $this->Form->input('fornecedores_id', array('type' => 'select', 'name'=>'Teste[fornecedores_id][]', 'label' => false, 'div' => false, 'empty' => true, 'class' => 'input-xlarge', 'required' => false, 'onBlur'=>'teste()', 'option' => $fornecedores)); ?>
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="OrcamentoPrazoEntrega">Prazo de Entrega</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-calendar"></i>
                </span>
                <input type="text" name="Teste[prazo_entrega][]" class="input-medium" min="0" />
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="OrcamentoCondPagamento">Cond Pagamento</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-credit-card"></i>
                </span>
                <input type="text" name="Teste[cod_pagamento][]" class="input-medium" min="0" />
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="OrcamentoTransportadora">Transportadora</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-fighter-jet"></i>
                </span>
                <input type="text" name="Teste[trasnportadora][]" class="input-large" min="0" />
              </div>
            </div>
          </p>

          <!-- <tr><td colspan="3">Telefone: &nbsp;&nbsp;<?php echo $this->Form->input('telefone', array('label'=>false, 'class' => 'input-medium', 'required' => false)); ?></td></tr>
          <tr><td colspan="4">Nome do Fornecedor: &nbsp;&nbsp; <?php echo $this->Form->input('fornecedores_id', array('type' => 'select', 'label' => false, 'div' => false, 'empty' => true, 'class' => 'input-large', 'required' => true, 'onBlur'=>'teste()', 'option' => $fornecedores)); ?></td></tr>
          <tr><td colspan="4">Contato:  &nbsp;&nbsp;<?php echo $this->Form->input('telefone', array('label'=>false, 'class' => 'input-medium', 'required' => false)); ?></td></tr>
          <tr><td colspan="4">Prazo de Entrega: &nbsp;&nbsp;<input type="text" name="data[Orcamento][prazo_entrega]" class="input-xlarge" min="0" /></td></tr>
          <tr><td colspan="4">Cond Pagamento: &nbsp;&nbsp; <input type="text" name="data[Orcamento][cod_pagamento]" class="input-xlarge" min="0" /></td></tr>
          <tr><td colspan="4">Transportadora: &nbsp;&nbsp;<input type="text" name="data[Orcamento][trasnportadora]" class="input-xlarge" min="0" /></td></tr>
          <tr><td colspan="4">Cidade/Estado: &nbsp;&nbsp;<input type="text" name="data[Orcamento][trasnportadora]" class="input-xlarge" min="0" /></td></tr> -->

          <table class="table table-bordered table-striped" width="80% height=20%">
            <thead>
              <tr>
                <td width="20">QT</td>
                <td width="50">MAQ</td>
                <td width="50">FIL</td>
                <td width="130">Descricao</td>
                <td width="20">Unitario</td>
                <td width="40">total</td>
              </tr>

            </thead>
            <tbody>

              <?php foreach ($compra as $compra): ?>
                <tr>
                  <td><?php echo $compra['ItemCompra']['quantidade_pedido']; ?></td>
                  <td><?php echo $compra['ItemCompra']['aplicacao']; ?></td>
                  <td><?php echo $compra['Setor']['descricao']; ?></td>
                  <td><?php echo $compra['Material']['nome']; ?></td>
                  <td><input type="text" name="data[Teste][unitario][]" class="input-mini" min="0" /></td>
                  <td><input type="text" name="data[Teste][total][]" class="input-mini" min="0" /></td>
                </tr>

              <?php endforeach; ?>

            </tbody>
          </table>


          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
            </span>
          </div>
</span>


          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="row-fluid wizard-actions">
    <button class="btn btn-inverse" type="submit">
        <i class="icon-ok bigger-110"></i> Gravar
    </button>
  </div>

  <!-- <div class="row-fluid wizard-actions">
    <button class="btn btn-inverse" type="submit">
        <i class="icon-ok bigger-110"></i> Gravar
    </button>
  </div> -->

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

  $(".chzn-select").chosen();

  // function teste(){
  //   var url = '<?php echo $this->Html->url(array('controller' => 'compras'), true); ?>/compras/';
  //   url += jQuery('#fornecedores_id').val();
  //   location.href = url;
  // }

  // jQuery(document).ready(function() {
  //     jQuery('#btnSearch').click(function() {
  //         var url = '<?php echo $this->Html->url(array('controller' => 'compras'), true); ?>/../pesquisar/';
  //         url += jQuery('#idSearch').val();
  //         location.href = url;
  //     });
  // });


</script>
