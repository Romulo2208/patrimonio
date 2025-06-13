<div class="page-header position-relative">
    <h1>
        <?php echo __('Nota Fiscal'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Index'); ?> </small>
    </h1>
</div>

<p>
  <?php echo $this->Html->link(__(' Nova Nota'), array('action' => 'add'), array('class' => 'btn btn-small btn-inverse icon-edit bigger-110 popup', 'style' => '')); ?>
  <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
  <input type="text" id="idSearch" placeholder="N&uacute;mero ou Valor da Nota" style="width: 200px; float: right;"/>
</p>
<hr />

<table class="table table-striped table-bordered table-hover">
    <thead>
        <tr>
            <th><?php echo $this->Paginator->sort('natureza_operacao', ('Natureza da Opera&ccedil;&atilde;o'), array('escape'=>false)); ?></th>
            <th><?php echo $this->Paginator->sort('numero_nota', ('N&uacute;mero da Nota'), array('escape'=>false)); ?></th>
            <th><?php echo $this->Paginator->sort('data_emissao', ('Data Emiss&atildeo'), array('escape'=>false)); ?></th>
            <th><?php echo $this->Paginator->sort('valor_nota', ('Valor da Nota'), array('escape'=>false)); ?></th>
            <th class="actions"><?php echo __(''); ?></th>
        </tr>
    </thead>

    <tbody>
        <?php foreach ($notasFiscais as $notaFiscal): ?>
        <tr>
            <td><?php echo h($notaFiscal['NotaFiscal']['natureza_operacao']); ?>&nbsp;</td>
            <td><?php echo h($notaFiscal['NotaFiscal']['numero_nota']); ?>&nbsp;</td>
            <td><?php echo h($notaFiscal['NotaFiscal']['data_emissao']); ?>&nbsp;</td>
            <td><?php echo h($notaFiscal['NotaFiscal']['valor_nota']); ?>&nbsp;</td>
            <td style="width: 180px; text-align: center;">
			<?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $notaFiscal['NotaFiscal']['id']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>
            </td>
        </tr>
        <?php endforeach; ?>
    </tbody>
    <tfoot>
        <tr>
            <td colspan="5">
                <?php echo $this->element('paginacao'); ?>
            </td>
        </tr>
    </tfoot>
</table>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#btnSearch').click(function() {
            var url = '<?php echo $this->Html->url(array('controller' => 'notas_fiscais'), true); ?>/index/';
            if($('#idSearch').val()){
                url += jQuery('#idSearch').val();
            }

            /*if($('#idSearch1').val()){
                 url += '0/'+ jQuery('#idSearch1').val();
            }*/

            location.href = url;
        });
    });
</script>
