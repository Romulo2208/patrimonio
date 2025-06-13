<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR SITUA&Ccedil;&Atilde;O PROTOCOLO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Remessa', array('class' => 'form-horizontal'));
            echo $this->Form->input('id', array('type' => 'hidden' , 'value' =>$remessas['Remessa']['id']));

            // pr($remessas['Remessa']['id']);exit;
      ?>

      <div class="input-prepend">
          <label>Situa&ccedil;&atilde;o</label>
          <span class="add-on">
            <i class="icon-exchange"></i>
          </span>
          <?php echo $this->Form->input('situacao_protocolo', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Pendente','2'=>'Atendido', '3'=>'Atendido Parcialmente'), 'required' => true, 'class' => 'input-medium')); ?>
      </div>

      <div class="control-group">
        <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
      </div>



    </div>
  </div>
</div>
