<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Transferencias'); ?>        </small>
    </h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable">


            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <p>
                      <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova Transferencia</a>
                      <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
                      <input type="text" id="idSearch" placeholder="Numero da Transferencia" style="width: 200px; float: right;"/>
                  </p>
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Data e Hora</th>
                              <th style="width: 150px;">Numero da Transferencia</th>
                              <th style="width: 100px;">Filial</th>
                              <th style="width: 100px;">Solicitante</th>
                              <th style="width: 350px;">Observa&ccedil;&atilde;o</th>
                              <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                              <th style="width: 250px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($transferencias as $transferencia) { ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($transferencia['Transferencia']['data_hora_registro'])); ?></td>
                                <td><?php echo $transferencia['Transferencia']['id']; ?></td>
                                <td><?php echo $transferencia['Setor']['descricao']; ?></td>
                                <td><?php echo $transferencia['Usuario']['nome']; ?></td>
                                <td><?php echo $transferencia['Transferencia']['observacao']; ?></td>
                                <td style="text-align: center;"><?php if($transferencia['Transferencia']['situacao'] == 1){ echo "<span class='icon-edit gray bigger-160' title='Pendente'></span>";}
                                elseif ($transferencia['Transferencia']['situacao'] == 2) { echo "<span class='icon-edit green bigger-160' title='Aprovado'></span>";}
                                else { echo "<span class='icon-edit orange bigger-160' title='Em Aberto'></span>";}?></td>
                                <td style="text-align: center;">
                                <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'edit', $transferencia['Transferencia']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>
                                <?php } ?>
                                <?php if($this->Session->read('Auth.User.id') <> 4546){ ?>
                                  <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'entrada', $transferencia['Transferencia']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Entrada</a>
                                <?php }} ?>
                                  <a href="<?php echo $this->Html->url(array('action' => 'imprimir', $transferencia['Transferencia']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> imprimir</a>
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
        var url = '<?php echo $this->Html->url(array('controller' => 'transferencias'), true); ?>/index/';
        url += jQuery('#idSearch').val();
        location.href = url;
      });

      $(document).keypress(function(e) {
        if (e.which == 13) {
          var url = '<?php echo $this->Html->url(array('controller' => 'transferencias'), true); ?>/index/';
          url += jQuery('#idSearch').val();
          location.href = url;
        }
      });
    });
</script>
