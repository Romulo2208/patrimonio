<div class="page-header position-relative">
    <h1>
        <?php printf("<?php echo __('%s'); ?>", $singularHumanName); ?>
        <small>
            <i class="icon-double-angle-right"></i>
            <?php printf("<?php echo __('%s'); ?>", Inflector::humanize($action)); ?>
        </small>
        
        <?php echo "<?php echo \$this->Html->link(__(' Adicionar {$pluralHumanName}'), array('action' => 'add'), array('class' => 'btn btn-small btn-default icon-ok bigger-110')); ?>\n"; ?>
    </h1>
</div>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <?php foreach ($fields as $field): ?>
                <th><?php echo "<?php echo \$this->Paginator->sort('{$field}'); ?>"; ?></th>
            <?php endforeach; ?>
            <th class="actions"><?php echo "<?php echo __(''); ?>"; ?></th>
        </tr>
    </thead>

    <tbody>
        <?php
        echo "<?php foreach (\${$pluralVar} as \${$singularVar}): ?>\n";
        echo "\t<tr>\n";
        foreach ($fields as $field) {
            $isKey = false;
            if (!empty($associations['belongsTo'])) {
                foreach ($associations['belongsTo'] as $alias => $details) {
                    if ($field === $details['foreignKey']) {
                        $isKey = true;
                        echo "\t\t<td>\n\t\t\t<?php echo \$this->Html->link(\${$singularVar}['{$alias}']['{$details['displayField']}'], array('controller' => '{$details['controller']}', 'action' => 'view', \${$singularVar}['{$alias}']['{$details['primaryKey']}'])); ?>\n\t\t</td>\n";
                        break;
                    }
                }
            }
            if ($isKey !== true) {
                echo "\t\t<td><?php echo h(\${$singularVar}['{$modelClass}']['{$field}']); ?>&nbsp;</td>\n";
            }
        }

        echo "\t\t<td style=\"width: 180px; text-align: center;\">\n";
        echo "\t\t\t<?php echo \$this->Html->link(__(' Editar'), array('action' => 'edit', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>\n";
        echo "\t\t\t<?php echo \$this->Form->postLink(__(' Deletar'), array('action' => 'delete', \${$singularVar}['{$modelClass}']['{$primaryKey}']), array('class' => 'btn btn-mini btn-danger icon-trash'), __('Deletar %s?', \${$singularVar}['{$modelClass}']['{$primaryKey}'])); ?>\n";
        echo "\t\t</td>\n";
        echo "\t</tr>\n";
        echo "<?php endforeach; ?>\n";
        ?>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="<?php echo (sizeof($fields) + 1) ?>">
                <?php echo "<?php echo \$this->element('paginacao'); ?>\n"; ?>
            </td>
        </tr>
    </tfoot>
</table>
