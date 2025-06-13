<div class="page-header position-relative">
  <h1>
    <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Produtos'); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
          <li class="active"> <?php echo $this->Html->link(__('<i class="blue icon-folder-open bigger-110"></i> Produto'), array('controller' => 'materiais_filiais', 'action' => 'filial09'), array('class' => '', 'escape' => false)); ?> </li>
      </ul>
      <?php //pr($this->Session->read('Auth'));exit; ?>

      <div class="tab-content">
        <div class="tab-pane in active">
          <p>
            <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
            <input type="text" id="idSearch" placeholder="Nome ou Codigo do Produto" style="width: 200px; float: right;"/>
          </p>
          <hr />
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th><?php echo $this->Paginator->sort('Numero'); ?></th>
                <th><?php echo $this->Paginator->sort('nome'); ?></th>
                <th><?php echo $this->Paginator->sort('Classificacao.descricao', 'Classifica&ccedil;&atilde;o', array('escape' => false)); ?></th>
                <th><?php echo $this->Paginator->sort('descricao', 'Descri&ccedil;&atilde;o', array('escape' => false)); ?></th>
                <th><?php echo $this->Paginator->sort('quantidade'); ?></th>
                <th><?php echo $this->Paginator->sort('Filial'); ?></th>
                <th><?php echo $this->Paginator->sort('data_registro', '&Uacute;ltima Atualiza&ccedil;&atilde;o', array('escape' => false)); ?></th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($materiais as $material): ?>
                <tr>
                  <td><?php echo h($material['Material']['id']); ?>&nbsp;</td>
                  <td><?php echo h($material['Material']['nome']); ?>&nbsp;</td>
                  <td><?php echo h($material['Classificacao']['descricao']); ?>&nbsp;</td>
                  <td><?php echo h($material['Material']['descricao']); ?>&nbsp;</td>
                  <td><?php echo h($material['MaterialFilial']['quantidade']); ?>&nbsp;</td>
                  <td><?php echo h($material['Setor']['descricao']); ?>&nbsp;</td>
                  <td><?php echo date('d/m/Y H:i', strtotime($material['Material']['data_registro'])); ?>&nbsp;</td>
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

<script type="text/javascript">
jQuery(document).ready(function() {
  $('a.popup').colorbox({
      onLoad: function () {
          $('#cboxClose, #cboxTitle, #cboxCurrent, #cboxNext, #cboxPrevious').remove();
      }
  });

  jQuery('#btnSearch').click(function() {
    var url = '<?php echo $this->Html->url(array('controller' => 'materiais_filiais'), true); ?>/';
    url += jQuery('#idSearch').val();
    location.href = url;
  });

  $(document).keypress(function(e) {
    if (e.which == 13) {
      var url = '<?php echo $this->Html->url(array('controller' => 'materiais_filiais'), true); ?>/';
      url += jQuery('#idSearch').val();
      location.href = url;
    }
  });
});
</script>
