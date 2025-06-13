<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO EQUIPAMENTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Equipamento', array('class'=>'form-horizontal')); ?>


      <p>
        <div class="input-prepend">
          <label class="control-label" for="EquipamentoDescricao">Nome</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <?php echo $this->Form->input('descricao', array('label'=>false, 'div' => false,'class' => 'input-xlarge', 'required' => true)); ?>
          </div>
        </div>
      </p>

      <div class="row-fluid wizard-actions">
        <button class="btn btn-inverse" type="submit">
          <i class="icon-ok bigger-110"></i> Gravar
        </button>
      </div>


      <?php echo $this->Form->end(); ?>
    </div>
  </div>
</div>
<script type="text/javascript">

</script>
