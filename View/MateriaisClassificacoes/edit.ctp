<div class="page-header position-relative">
    <h1>
        <?php echo __('Categoria'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Editar'); ?>        </small>

        <?php echo $this->Html->link(__(' Listar Todos'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
    </h1>
</div>

<?php
    echo $this->Form->create('MaterialClassificacao', array('class' => 'form-horizontal'));
    echo $this->Form->input('id', array('required' => true));
?>



            <div class="control-group">
                <label class="control-label" for="MaterialClassificacaoDescricao">Nome</label>
                <div class="controls">
                <?php echo $this->Form->input('descricao', array('label'=>false, 'class' => 'input-xlarge', 'required' => true)); ?>
                </div>
            </div>


            <div class="row-fluid wizard-actions">
                <button class="btn btn-inverse" type="submit">
                    <i class="icon-ok bigger-110"></i> Gravar
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="icon-undo bigger-110"></i> Limpar
                </button>
            </div>


<?php echo $this->Form->end(); ?>
