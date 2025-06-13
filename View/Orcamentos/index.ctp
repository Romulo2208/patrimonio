<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Orcamentos'); ?>        </small>
    </h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable">
            <?php //if(!in_array($this->Session->read('Perfil.id'), array('3'))) { ?>
              <ul class="nav nav-tabs" id="myTab">
                  <li> <?php echo $this->Html->link(__('<i class="blue icon-folder-open bigger-110"></i> Produto'), array('controller' => 'materiais', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                  <li> <?php echo $this->Html->link(__("<i class='green icon-cloud-upload bigger-110'></i> Entrada <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'entrada'), array('class' => '', 'escape' => false)); ?> </li>
                  <li> <?php echo $this->Html->link(__("<i class='red icon-cloud-download bigger-110'></i> Saida <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'saida'), array('class' => '', 'escape' => false)); ?> </li>
                  <li> <?php echo $this->Html->link(__("<i class='orange icon-list bigger-110'></i> Pedidos <span class='badge badge-important'></span>"), array('controller' => 'pedidos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                  <li> <?php echo $this->Html->link(__("<i class='purple icon-credit-card bigger-110'></i> Requisi&ccedil;&atilde;o de Compras <span class='badge badge-important'></span>"),  array('controller' => 'compras', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                  <li class="active"> <?php echo $this->Html->link(__("<i class='orange icon-briefcase bigger-110'></i> Or&ccedil;amentos <span class='badge badge-important'></span>"), array('controller' => 'orcamentos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                  <li> <?php echo $this->Html->link(__("<i class='black icon-book bigger-110'></i> Protocolos <span class='badge badge-important'></span>"),  array('controller' => 'remessas', 'action' => 'protocolos'), array('class' => '', 'escape' => false)); ?> </li>
              </ul>
            <?php //} ?>

            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <p>
                      <!-- <a href="<?php //echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova Requisi&ccedil;&atilde;o</a> -->
                      <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
                      <input type="text" id="idSearch" placeholder="Numero do Or&ccedil;amento" style="width: 200px; float: right;"/>
                  </p>
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Data e Hora</th>
                              <th style="width: 150px;">Numero do Or&ccedil;amento</th>
                              <th style="width: 250px;">Solicitante</th>
                              <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                              <th style="width: 150px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($orcamentos as $orcamento) { ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($orcamento['Orcamento']['data_hora_registro'])); ?></td>
                                <td><?php echo $orcamento['Orcamento']['id']; ?></td>
                                <td><?php echo $orcamento['Usuario']['nome']; ?></td>
                                <td style="text-align: center;"><?php echo ($orcamento['Orcamento']['situacao'] == 1 ? "<span class='icon-edit red bigger-160' title='Pendente'></span>" : "<span class='icon-check green bigger-160' title='Atendido'></span>"); ?></td>
                                <td style="text-align: center;">
                                  <a href="<?php echo $this->Html->url(array('action' => 'edit', $orcamento['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>
                                  <a href="<?php echo $this->Html->url(array('action' => 'remessa', $orcamento['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Remessa</a>
                                  <!-- <a href="<?php //echo $this->Html->url(array('action' => 'imprimir', $orcamento['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Imprimir</a> -->
                                </td>
                            </tr>
                          <?php } ?>
                      </tbody>
                      <tfoot>
                          <tr>
                              <td colspan="10">
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
    $(function () {
      $('a.popup').colorbox({
          onLoad: function () {
              $('#cboxClose, #cboxTitle, #cboxCurrent, #cboxNext, #cboxPrevious').remove();
          }
      });

    jQuery('#btnSearch').click(function() {
        var url = '<?php echo $this->Html->url(array('controller' => 'orcamentos'), true); ?>/index/';
        url += jQuery('#idSearch').val();
        location.href = url;
      });

    $(document).keypress(function(e) {
        if (e.which == 13) {
          var url = '<?php echo $this->Html->url(array('controller' => 'orcamentos'), true); ?>/index/';
          url += jQuery('#idSearch').val();
          location.href = url;
        }
      });
    });
</script>
