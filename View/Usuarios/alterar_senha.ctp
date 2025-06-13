<div class="page-header position-relative">
    <h1>
        Usu&aacute;rio        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Alterar Senha'); ?>        </small>

        
    </h1>
</div>

<?php 
    echo $this->Form->create('Usuario', array('class' => 'form-horizontal')); 
    echo $this->Form->input('id', array('value' => $usuario['Usuario']['id']));
?>


    <div class="control-group">
        <label class="control-label" for="form-field-1">Senha Atual</label>
        <div class="controls">
            <?php echo $this->Form->input('senha_atual', array('label' => false, 'type' => 'password')); ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="form-field-1">Nova Senha</label>
        <div class="controls">
            <?php echo $this->Form->input('nova_senha', array('label' => false, 'type' => 'password')); ?>
        </div>
    </div>

    <div class="control-group">
        <label class="control-label" for="form-field-1">Confirmar Senha</label>
        <div class="controls">
            <?php echo $this->Form->input('confirmar_senha', array('label' => false, 'type' => 'password')); ?>
        </div>
    </div>

    <div class="form-actions">
        <button class="btn btn-danger" type="submit">
            <i class="icon-ok bigger-110"></i>
            Gravar
        </button>
        <?php echo $this->Html->link(__('&nbsp;&nbsp; Cancelar'), array('controller' => 'mains', 'action' => 'index'), array('class' => 'btn btn-inverse icon-book bigger-110', 'escape' => false)); ?>
    </div>
<?php echo $this->Form->end(); ?>
