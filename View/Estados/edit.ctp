<div class="page-header position-relative">
    <h1>
        <?php echo __('Estado'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Edit'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Listar Associados'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
                    <?php echo $this->Form->postLink(__(' Deletar'), array('action' => 'delete', $this->Form->value('Estado.id')), array('class' => 'btn btn-small btn-default icon-trash bigger-110'), __('Deletar %s?', $this->Form->value('Estado.id'))); ?>
            </h1>
</div>
    
<?php echo $this->Form->create('Estado'); ?>
	<?php
		echo $this->Form->input('id', array('required' => true));
		echo $this->Form->input('descricao', array('required' => true));
	?>
<?php echo $this->Form->end(array('label' => 'Gravar', 'class' => 'btn btn-small btn-danger')); ?>

