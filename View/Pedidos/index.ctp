<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Pedidos'); ?>        </small>
    </h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable">
          <?php //if(!in_array($this->Session->read('Perfil.id'), array('3'))) { ?>
            <ul class="nav nav-tabs" id="myTab">
                <li> <?php echo $this->Html->link(__('<i class="blue icon-folder-open bigger-110"></i> Produto'), array('controller' => 'materiais', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='reed icon-bullhorn bigger-110'></i> Estoque Minimo <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'estoque_minimo'), array('class' => '', 'escape' => false)); ?></li>
                <li> <?php echo $this->Html->link(__("<i class='green icon-cloud-upload bigger-110'></i> Entrada <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'entrada'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='red icon-cloud-download bigger-110'></i> Saida <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'saida'), array('class' => '', 'escape' => false)); ?> </li>
                <li class="active"> <?php echo $this->Html->link(__("<i class='orange icon-list bigger-110'></i> Pedidos <span class='badge badge-important'></span>"), array('controller' => 'pedidos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
                <li> <?php echo $this->Html->link(__("<i class='purple icon-credit-card bigger-110'></i> Requisi&ccedil;&atilde;o de Compras <span class='badge badge-important'></span>"),  array('controller' => 'compras', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='orange icon-briefcase bigger-110'></i> Or&ccedil;amentos <span class='badge badge-important'></span>"), array('controller' => 'orcamentos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
                <li> <?php echo $this->Html->link(__("<i class='black icon-book bigger-110'></i> Protocolos <span class='badge badge-important'></span>"),  array('controller' => 'remessas', 'action' => 'protocolos'), array('class' => '', 'escape' => false)); ?> </li>
                <?php } ?>
            </ul>
          <?php //} ?>

            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <p>
                      <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Novo Pedido</a>

                      <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?>
                        <a href="<?php echo $this->Html->url(array('action' => 'pedidos_pendente'), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Material Pendente</a>
                      <?php } ?>

                      <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
                      <input type="text" id="idSearch" placeholder="Codigo do Pedido" style="width: 200px; float: right;"/>
                  </p>
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Data e Hora</th>
                              <th style="width: 150px;">Codigo do Pedido</th>
                              <th style="width: 100px;">Unidade Requisitante</th>
                              <th style="width: 150px;">Aprovador</th>
                              <th style="width: 600px;">Observa&ccedil;&atilde;o</th>
                              <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                              <th style="width: 150px;"></th>
                              <th style="width: 150px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($pedidos as $pedido) { ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($pedido['Pedido']['data_hora_registro'])); ?></td>
                                <td><?php echo $pedido['Pedido']['id']; ?></td>
                                <td><?php echo $pedido['Setor']['descricao']; ?></td>
                                <td><?php echo $pedido['Usuario']['nome']; ?></td>
                                <td><?php echo $pedido['Pedido']['observacao']; ?></td>
                                <td style="text-align: center;"><?php if($pedido['Pedido']['situacao'] == 1){ echo "<span class='icon-edit gray bigger-160' title='Pendente'></span>";}
                                elseif ($pedido['Pedido']['situacao'] == 2) { echo "<span class='icon-edit green bigger-160' title='Aprovado'></span>";}
                                elseif ($pedido['Pedido']['situacao'] == 3) { echo "<span class='icon-edit orange bigger-160' title='Atendido Parcialmente'></span>";}
                                else { echo "<span class='icon-edit red bigger-160' title='Bloqueado'></span>";}?></td>
                                <td style="text-align: center;">
                                  <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'separar', $pedido['Pedido']['id']), true); ?>" class="btn btn-mini btn-primary popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Separar</a>
                                <?php } ?>
                                  <?php if(!in_array($this->Session->read('Perfil.id'),array('2', '3','12')) || $pedido['Pedido']['aprovacao'] != 1) { ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'edit', $pedido['Pedido']['id']), true); ?>" class="btn btn-mini btn-success popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>
                                <?php } ?>
                                <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'transferencia_pedido', $pedido['Pedido']['id']), true); ?>" class="btn btn-mini btn-inverse popup" data-rel="doc" target="blank"><i class="icon-edit bigger-110"></i> CI Filial</a>
                                <?php } ?>
                                </td>
                                <td>
                                  <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
                                  <?php echo $this->Html->link(__(' Deletar'), array('action' => 'delete', $pedido['Pedido']['id']), array('class' => 'btn btn-mini btn-danger icon-edit popup')); ?>
                                  <!-- <a href="<?php //echo $this->Html->url(array('action' => 'transferencia_pedido', $pedido['Pedido']['id']), true); ?>" class="btn btn-mini btn-inverse popup" data-rel="doc"><i class="icon-edit bigger-110"></i> CI Filial</a> -->
                                <?php } ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'imprimir', $pedido['Pedido']['id']), true); ?>" class="btn btn-mini btn-light" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Visualizar</a>
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
        var url = '<?php echo $this->Html->url(array('controller' => 'pedidos'), true); ?>/index/';
        url += jQuery('#idSearch').val();
        location.href = url;
      });

      $(document).keypress(function(e) {
        if (e.which == 13) {
          var url = '<?php echo $this->Html->url(array('controller' => 'pedidos'), true); ?>/index/';
          url += jQuery('#idSearch').val();
          location.href = url;
        }
      });

    });
</script>
