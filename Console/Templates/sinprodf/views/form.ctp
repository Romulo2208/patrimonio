<div class="page-header position-relative">
    <h1>
        <?php printf("<?php echo __('%s'); ?>", $singularHumanName); ?>
        <small>
            <i class="icon-double-angle-right"></i>
            <?php printf("<?php echo __('%s'); ?>", Inflector::humanize($action)); ?>
        </small>
        
        <?php echo "<?php echo \$this->Html->link(__(' Listar Associados'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>\n"; ?>
        <?php if (strpos($action, 'add') === false): ?>
            <?php echo "<?php echo \$this->Form->postLink(__(' Deletar'), array('action' => 'delete', \$this->Form->value('{$modelClass}.{$primaryKey}')), array('class' => 'btn btn-small btn-default icon-trash bigger-110'), __('Deletar %s?', \$this->Form->value('{$modelClass}.{$primaryKey}'))); ?>\n"; ?>
        <?php endif; ?>
    </h1>
</div>
    
<?php echo "<?php echo \$this->Form->create('{$modelClass}'); ?>\n"; ?>
<?php
    echo "\t<?php\n";
    foreach ($fields as $field) {
            if (strpos($action, 'add') !== false && $field == $primaryKey) {
                    continue;
            } elseif (!in_array($field, array('created', 'modified', 'updated'))) {
                    echo "\t\techo \$this->Form->input('{$field}', array('required' => true));\n";
            }
    }
    if (!empty($associations['hasAndBelongsToMany'])) {
            foreach ($associations['hasAndBelongsToMany'] as $assocName => $assocData) {
                    echo "\t\techo \$this->Form->input('{$assocName}', array('required' => true));\n";
            }
    }
    echo "\t?>\n";
?>
<?php echo "<?php echo \$this->Form->end(array('label' => 'Gravar', 'class' => 'btn btn-small btn-danger')); ?>\n"; ?>

