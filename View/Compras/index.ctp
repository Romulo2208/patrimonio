<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Compras'); ?>        </small>
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
                <li class="active"> <?php echo $this->Html->link(__("<i class='purple icon-credit-card bigger-110'></i> Requisi&ccedil;&atilde;o de Compras <span class='badge badge-important'></span>"),  array('controller' => 'compras', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='orange icon-briefcase bigger-110'></i> Or&ccedil;amentos <span class='badge badge-important'></span>"), array('controller' => 'orcamentos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='black icon-book bigger-110'></i> Protocolos <span class='badge badge-important'></span>"),  array('controller' => 'remessas', 'action' => 'protocolos'), array('class' => '', 'escape' => false)); ?> </li>
            </ul>
          <?php //} ?>

            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <p>
                      <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova Requisi&ccedil;&atilde;o</a>

                      <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
                      <input type="text" id="idSearch" placeholder="Codigo da Requisi&ccedil;&atilde;o" style="width: 200px; float: right;"/>
                  </p>
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Data e Hora</th>
                              <th style="width: 150px;">Codigo da Requisi&ccedil;&atilde;o</th>
                              <th style="width: 150px;">Unidade Requisitante</th>
                              <th style="width: 100px;">Solicitante</th>
                              <th style="width: 500px;">Observa&ccedil;&atilde;o</th>
                              <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                              <th style="width: 250px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($compras as $compra) { ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($compra['Compra']['data_hora_registro'])); ?></td>
                                <td><?php echo $compra['Compra']['id']; ?></td>
                                <td><?php echo $compra['Setor']['descricao']; ?></td>
                                <td><?php echo $compra['Usuario']['nome']; ?></td>
                                <td><?php echo $compra['Compra']['observacao']; ?></td>
                                <td style="text-align: center;"><?php if($compra['Compra']['situacao'] == 1){ echo "<span class='icon-edit gray bigger-160' title='Pendente'></span>";}
                                elseif ($compra['Compra']['situacao'] == 2) { echo "<span class='icon-edit blue bigger-160' title='Em Andamento'></span>";}
                                elseif ($compra['Compra']['situacao'] == 3) { echo "<span class='icon-edit green bigger-160' title='Aprovado'></span>";}
                                elseif ($compra['Compra']['situacao'] == 4) { echo "<span class='icon-edit red bigger-160' title='Bloqueado'></span>";}
                                else { echo "<span class='icon-edit orange bigger-160' title='Em Aberto'></span>";}?></td>
                                <td style="text-align: center;">
                                  <a href="<?php echo $this->Html->url(array('action' => 'edit', $compra['Compra']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>

                                    <!-- <a href="<?php// echo $this->Html->url(array('controller' => 'orcamentos', 'action' => 'edit', $compra['Orcamento']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Revisar</a> -->
                                    <a href="<?php echo $this->Html->url(array('action' => 'orcamentos', $compra['Compra']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Compra</a>


                                  <a href="<?php echo $this->Html->url(array('action' => 'imprimir', $compra['Compra']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Imprimir</a>
                                  <a href="<?php echo $this->Html->url(array('action' => 'cotacao', $compra['Compra']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Cotacao</a>
                                  <!-- <a href="<?php //echo $this->Html->url(array('action' => 'compras', $compra['Compra']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Compra</a> -->
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
        var url = '<?php echo $this->Html->url(array('controller' => 'compras'), true); ?>/index/';
        url += jQuery('#idSearch').val();
        location.href = url;
      });

      $(document).keypress(function(e) {
        if (e.which == 13) {
          var url = '<?php echo $this->Html->url(array('controller' => 'compras'), true); ?>/index/';
          url += jQuery('#idSearch').val();
          location.href = url;
        }
      });

    });
</script>
