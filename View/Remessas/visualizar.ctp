<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Protocolos'); ?>        </small>
    </h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable">


            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <!-- <p>
                      <a href="<?php //echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova Requisi&ccedil;&atilde;o</a>
                  </p> -->
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Filial</th>
                              <th style="width: 250px;">Numero do Pedido</th>
                              <th style="width: 150px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($remessas as $remessa) { ?>
                            <tr>
                                <td><?php echo $remessa['Setor']['descricao']; ?></td>
                                <td><?php echo $remessa['ItemRemessa']['pedidos_id']; ?></td>
                                <td style="text-align: center;">
                                  <!-- <a href="<?php //echo $this->Html->url(array('action' => 'abrir', $orcamento['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Abrir</a>
                                  <a href="<?php //echo $this->Html->url(array('action' => 'imprimir', $orcamento['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Imprimir</a> -->
                                  <a href="<?php echo $this->Html->url(array('action' => 'transferencia', $remessa['ItemRemessa']['pedidos_id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Transferencia</a>
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
    });
</script>
