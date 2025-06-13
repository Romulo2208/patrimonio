<div class="page-header position-relative">
    <h1>
        <?php echo __('Patrim&ocirc;nio'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Index'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Adicionar'), array('action' => 'add'), array('class' => 'btn btn-small btn-default icon-ok bigger-110')); ?>
    </h1>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#btnSearch').click(function() {
            var url = '<?php echo $this->Html->url(array('controller' => 'patrimonios'), true); ?>/index/';
            url += jQuery('#idSearch').val();
            location.href = url;
        });
    });
</script>

<input type="text" id="idSearch" placeholder="Descri&ccedil;&atilde;o ou C&oacute;digo" style="width: 200px;"/> 
<input type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="top: -5px;">

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('descricao', 'Descriç&atilde;o', array('escape'=> false)); ?></th>
            <th><?php echo $this->Paginator->sort('codigo',('C&oacute;digo'), array('escape'=>false)); ?></th>
            <th><?php echo $this->Paginator->sort('firma'); ?></th>
            <th><?php echo $this->Paginator->sort('data_registro', 'Data'); ?></th>
            <th><?php echo $this->Paginator->sort('localizacao_id', ('Localizaç&atilde;o'), array('escape'=>false)); ?></th>
            <th><?php echo $this->Paginator->sort('conservacao_id', ('Conservaç&atilde;o'), array('escape'=>false)); ?></th>

            <th class="actions"><?php echo __(''); ?></th>
        </tr>
    </thead>
    <tbody>
        <?php foreach ($patrimonios as $patrimonio): ?>
        <tr>
            <td><?php echo substr($patrimonio['Patrimonio']['descricao'], 0, 50); ?>&nbsp;...</td>
            <td><?php echo h($patrimonio['Patrimonio']['codigo']); ?>&nbsp;</td>
            <td><?php echo h($patrimonio['Patrimonio']['firma']); ?>&nbsp;</td>
            <td><?php echo h($patrimonio['Patrimonio']['data_registro']); ?>&nbsp;</td>
            <td><?php echo h($patrimonio['Localizacao']['descricao']); ?>&nbsp;</td>
            <td><?php echo h($patrimonio['Conservacao']['descricao']); ?>&nbsp;</td>

            <td style="width: 180px; text-align: center;">
			<?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $patrimonio['Patrimonio']['id']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="12">
                <?php echo $this->element('paginacao'); ?>
            </td>
        </tr>
    </tfoot>
</table>
