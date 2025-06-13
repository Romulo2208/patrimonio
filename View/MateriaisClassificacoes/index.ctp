<div class="page-header position-relative">
  <h1>
    <?php echo __('Categoria'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Materiais'); ?>        </small>
  </h1>
</div>

<script type="text/javascript">
    jQuery(document).ready(function() {
        jQuery('#btnSearch').click(function() {
            var url = '<?php echo $this->Html->url(array('controller' => 'materiais_classificacoes'), true); ?>/index/';
            url += jQuery('#idSearch').val();
            location.href = url;
        });
    });
</script>

<!-- <input type="text" id="idSearch" placeholder="Descri&ccedil;&atilde;o ou C&oacute;digo" style="width: 200px;"/>
<input type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="top: -5px;"> -->


<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">


      <div class="tab-content">
        <div class="tab-pane in active">
          <p>
            <?php echo $this->Html->link(__(' Nova Categoria'), array('action' => 'add'), array('class' => 'btn btn-small btn-inverse icon-edit bigger-110 popup', 'style' => '')); ?>
            <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
            <input type="text" id="idSearch" placeholder="Categoria" style="width: 200px; float: right;"/>
          </p>
          <hr />
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th><?php echo $this->Paginator->sort('descricao'); ?></th>
                <th class="actions"><?php echo __(''); ?></th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($classificacoes as $classificacoe): ?>
                <tr>
                  <td><?php echo h($classificacoe['MaterialClassificacao']['descricao']); ?>&nbsp;</td>
                  <td style="width: 180px; text-align: center;">
                    <?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $classificacoe['MaterialClassificacao']['id']), array('class' => 'btn btn-mini btn-info icon-edit popup')); ?>
                    <?php echo $this->Html->link(__(' Deletar'), array('action' => 'delete', $classificacoe['MaterialClassificacao']['id']), array('class' => 'btn btn-mini btn-info icon-edit popup')); ?>
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

        </div>
      </div>
    </div>
  </div>
</div>
