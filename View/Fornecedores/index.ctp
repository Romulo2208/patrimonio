<div class="page-header position-relative">
    <h1>
        <?php echo __('Fornecedor'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Index'); ?>        </small>

        <?php echo $this->Html->link(__(' Adicionar'), array('action' => 'add'), array('class' => 'btn btn-small btn-default icon-ok bigger-110')); ?>
    </h1>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#btnSearch').click(function() {
            var url = '<?php echo $this->Html->url(array('controller' => 'fornecedores'), true); ?>/index/';
            url += jQuery('#idSearch').val();
            location.href = url;
        });
    });
</script>

<input type="text" id="idSearch" placeholder="Nome Fantasia" style="width: 200px;"/> 
<input type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="top: -5px;">

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('nome_fantasia'); ?></th>
            <th><?php echo $this->Paginator->sort('razao_social', 'Raz&atilde;o Social', array('escape' => false)); ?></th>
            <th><?php echo $this->Paginator->sort('cnpj'); ?></th>
            <th class="actions"><?php echo __(''); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($fornecedores as $fornecedor): ?>
        <tr>
            <td><?php echo h($fornecedor['Fornecedor']['nome_fantasia']); ?>&nbsp;</td>
            <td><?php echo h($fornecedor['Fornecedor']['razao_social']); ?>&nbsp;</td>
            <td><?php echo h($fornecedor['Fornecedor']['cnpj']); ?>&nbsp;</td>
            <td style="width: 180px; text-align: center;">
                <?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $fornecedor['Fornecedor']['id']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>
            </td>
        </tr>
<?php endforeach; ?>

    </tbody>
    <tfoot>
        <tr>
            <td colspan="14">
                <?php echo $this->element('paginacao'); ?>
            </td>
        </tr>
    </tfoot>
</table>
