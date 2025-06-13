<div class="page-header position-relative">
    <h1>
        <?php echo __('Estado'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Add'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Listar Associados'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
            </h1>
</div>
    
<?php echo $this->Form->create('Estado'); ?>
	<?php
		echo $this->Form->input('descricao', array('required' => true));
	?>
<?php echo $this->Form->end(array('label' => 'Gravar', 'class' => 'btn btn-small btn-danger')); ?>

