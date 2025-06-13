<div class="page-header position-relative">
  <h1>
    <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Produtos'); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
          <li> <?php echo $this->Html->link(__('<i class="blue icon-folder-open bigger-110"></i> Produto'), array('controller' => 'materiais', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li class="active"> <?php echo $this->Html->link(__("<i class='red icon-bullhorn bigger-110'></i> Estoque Minimo <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'estoque_minimo'), array('class' => '', 'escape' => false)); ?></li>
          <?php if(!in_array($this->Session->read('Perfil.id'), array('5'))) { ?>
          <li> <?php echo $this->Html->link(__("<i class='green icon-cloud-upload bigger-110'></i> Entrada <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'entrada'), array('class' => '', 'escape' => false)); ?> </li>
          <?php } ?>
          <li> <?php echo $this->Html->link(__("<i class='red icon-cloud-download bigger-110'></i> Saida <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'saida'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='orange icon-list bigger-110'></i> Pedidos <span class='badge badge-important'></span>"), array('controller' => 'pedidos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
          <li> <?php echo $this->Html->link(__("<i class='purple icon-credit-card bigger-110'></i> Requisi&ccedil;&atilde;o de Compras <span class='badge badge-important'></span>"),  array('controller' => 'compras', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='orange icon-briefcase bigger-110'></i> Or&ccedil;amentos <span class='badge badge-important'></span>"), array('controller' => 'orcamentos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='black icon-book bigger-110'></i> Protocolos <span class='badge badge-important'></span>"),  array('controller' => 'remessas', 'action' => 'protocolos'), array('class' => '', 'escape' => false)); ?> </li>
          <?php } ?>
      </ul>
      <?php //pr($this->Session->read('Auth'));exit; ?>

      <div class="tab-content">
        <div class="tab-pane in active">
          <p>
            <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3', '12'))) { ?>
            <?php echo $this->Html->link(__(' Criar Pedido'), array('action' => 'separar'), array('class' => 'btn btn-small btn-inverse icon-edit bigger-110 popup', 'style' => '')); ?>
            <?php } ?>
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
                <th><?php echo $this->Paginator->sort('quantidade'); ?></th>
                <th><?php echo $this->Paginator->sort('est_min', 'Estoque Minimo', array('escape' => false)); ?></th>
                <th class="actions"><?php echo __(''); ?></th>
              </tr>
            </thead>

            <tbody>
              <?php foreach ($materiais as $material): ?>
                <tr>
                  <td><?php echo h($material['Material']['id']); ?>&nbsp;</td>
                  <td><?php echo h($material['Material']['nome']); ?>&nbsp;</td>
                  <td><?php echo h($material['Classificacao']['descricao']); ?>&nbsp;</td>
                  <td><?php echo h($material['MaterialFilial']['quantidade']); ?>&nbsp;</td>
                  <td><?php echo h($material['MaterialFilial']['est_min']); ?>&nbsp;</td>
                  <td style="width: 180px; text-align: center;">
                    <?php if(!in_array($this->Session->read('Perfil.id'), array('3'))) { ?>
                    <?php echo $this->Html->link(__(' Editar'), array('action' => 'edit', $material['Material']['id']), array('class' => 'btn btn-mini btn-info icon-edit popup')); ?>
                    <!-- <?php //echo $this->Html->link(__(' Separar'), array('action' => 'separar'), array('class' => 'btn btn-mini btn-info icon-edit popup')); ?> -->
                  <?php } ?>
                  </td>
                </tr>
              <?php endforeach; ?>

            </tbody>
            <tfoot>

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
    var url = '<?php echo $this->Html->url(array('controller' => 'materiais'), true); ?>/index/';
    url += jQuery('#idSearch').val();
    location.href = url;
  });
});
</script>
