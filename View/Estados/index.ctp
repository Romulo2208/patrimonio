<div class="page-header position-relative">
    <h1>
        <?php echo __('Estado'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Index'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Adicionar Estados'), array('action' => 'add'), array('class' => 'btn btn-small btn-default icon-ok bigger-110')); ?>
    </h1>
</div>

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
                            <th><?php echo $this->Paginator->sort('id'); ?></th>
                            <th><?php echo $this->Paginator->sort('descricao'); ?></th>
                        <th class="actions"><?php echo __(''); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($estados as $estado): ?>
	<tr>
		<td><?php echo h($estado['Estado']['id']); ?>&nbsp;</td>
		<td><?php echo h($estado['Estado']['descricao']); ?>&nbsp;</td>
		<td style="width: 180px; text-align: center;">
			<?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $estado['Estado']['id']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>
			<?php echo $this->Form->postLink(__(' Deletar'), array('action' => 'delete', $estado['Estado']['id']), array('class' => 'btn btn-mini btn-danger icon-trash'), __('Deletar %s?', $estado['Estado']['id'])); ?>
		</td>
	</tr>
<?php endforeach; ?>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="3">
                <?php echo $this->element('paginacao'); ?>
            </td>
        </tr>
    </tfoot>
</table>
