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
      echo $this->Form->create('OrdemServico', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('setores_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.setor')));
      echo $this->Form->input('situacao', array('type' => 'hidden', 'value' => 1));
      // echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
      ?>
      <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) {
        echo $this->Form->input('aprovacao', array('type' => 'hidden', 'value' => 1));
        echo $this->Form->input('assinatura_gerente', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
        echo $this->Form->input('data_assinatura', array('type' => 'hidden', 'value' => date('Y-m-d')));
      }?>

    <div class="row-fluid">
        <div class="input-prepend">
          <label>Servi&ccedil;o Executado</label>
          <span class="add-on">
            <i class="icon-list"></i>
          </span>
          <?php echo $this->Form->input('servico', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Oficina','2'=>'Industria'), 'required' => true, 'class' => 'input-large')); ?>
        </div>
        <div class="input-prepend">
            <label>Equipamento</label>
            <span class="add-on">
              <i class="icon-exchange"></i>
            </span>
              <input type="text" name="OrdemServico[equipamento]" class="input-xlarge" min="0" />
        </div>
        <div class="input-prepend">
            <label for="OrdemServicoData">Data</label>
            <span class="add-on">
              <i class="icon-calendar"></i>
            </span>
              <?php echo $this->Form->input('data', array('label' => false, 'div' => false,  'type'=> 'text',  'class' => 'input-small', 'data-date-format' => 'dd/mm/yyyy', 'required' => true)); ?>
        </div>
    </div>

      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;
      &nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;&nbsp;


<div class="control-group" style="margin-top: 10px;">
  <div class="input-prepend">
    <label>Especifica&ccedil;&atilde;o do Servi&ccedil;o</label>
    <span class="add-on">
      <i class="icon-cog"></i>
    </span>
    <span class="input-icon input-icon-right">
      <?php echo $this->Form->input('especificacao_servico', array('label' => false, 'div' => false, 'type' => 'textarea', 'required' => false, 'style' => 'width: 485px; height: 100px;')); ?>
    </span>
  </div>
</div>


<div class="control-group">
  <label>fornecedor</label>
  <span class="add-on">
    <i class="icon-truck"></i>
  </span>
  <span class="input-icon input-icon-right">
    <input type="text" name="OrdemServico[fornecedor]" class="input-xxlarge" min="0" />
  </span>
</div>

<div class="control-group" style="margin-top: 10px;">
  <div class="input-prepend">
    <label>Observa&ccedil;&atilde;o</label>
    <span class="add-on">
      <i class="icon-edit"></i>
    </span>
    <span class="input-icon input-icon-right">
      <?php echo $this->Form->input('observacao', array('label' => false, 'div' => false, 'type' => 'textarea', 'required' => false, 'style' => 'width: 485px; height: 100px;')); ?>
    </span>
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
  $('#OrdemServicoData').datepicker({
      dateFormat: 'yy-mm-dd',
      language: 'pt-BR',
      minDate: 0,
      maxDate: 'today'
  });

});

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}


$(".chzn-select").chosen({width: "500px"});

</script>
