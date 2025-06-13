<div class="page-header position-relative">
    <h1>
        <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Remessas'); ?>        </small>
    </h1>
</div>

<div class="row-fluid">
    <div class="span12">
        <div class="tabbable">


            <div class="tab-content">
              <div id="item" class="tab-pane in active">
                  <p>
                      <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova Remessa</a>

                      <br><br>
                      <?php echo $this->Html->link(__(' Upload PDF'), array('action' => 'upload'), array('class' => 'btn btn-small btn-inverse icon-edit bigger-110 popup', 'style' => '')); ?>

                      <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
                      <input type="text" id="idSearch" placeholder="Numero da Remessa" style="width: 200px; float: right;"/>
                  </p>
                  <hr />
                  <table class="table table-bordered table-striped">
                      <thead>
                          <tr>
                              <th style="width: 150px;">Data e Hora</th>
                              <th style="width: 150px;">Numero da Remessa</th>
                              <th style="width: 100px;">Solicitante</th>
                              <th style="width: 400px;">Observa&ccedil;&atilde;o</th>
                              <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                              <th style="width: 250px;"></th>
                          </tr>
                      </thead>
                      <tbody>
                          <?php foreach ($remessas as $remessa) { ?>
                            <tr>
                                <td><?php echo date('d/m/Y H:i', strtotime($remessa['Remessa']['data_hora_registro'])); ?></td>
                                <td><?php echo $remessa['Remessa']['id']; ?></td>
                                <td><?php echo $remessa['Usuario']['nome']; ?></td>
                                <td><?php echo $remessa['Remessa']['observacao']; ?></td>
                                <td style="text-align: center;"><?php if($remessa['Remessa']['situacao'] == 1){ echo "<span class='icon-edit gray bigger-160' title='Pendente'></span>";}
                                elseif ($remessa['Remessa']['situacao'] == 2) { echo "<span class='icon-edit green bigger-160' title='Aprovado'></span>";}
                                elseif ($remessa['Remessa']['situacao'] == 3) { echo "<span class='icon-edit blue bigger-160' title='Em Andamento'></span>";}
                                elseif ($remessa['Remessa']['situacao'] == 4) { echo "<span class='icon-edit red bigger-160' title='Bloqueado'></span>";}
                                else { echo "<span class='icon-edit orange bigger-160' title='Separado'></span>";}?></td>
                                <td style="text-align: center;">
                                  <a href="<?php echo $this->Html->url(array('action' => 'edit', $remessa['Remessa']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>
                                  <a href="<?php echo $this->Html->url(array('action' => 'imprimir', $remessa['Remessa']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Imprimir</a>
                                  <?php  if($remessa['Remessa']['pdf'] == 1) { ?>
                                  <a href="../../webroot/img/uploads/<?php echo $remessa['Remessa']['id']; ?>.pdf" class="btn btn-mini btn-danger icon-file" data-rel="doc" target="blank"> PDF</a>
                                <?php } ?>
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
        var url = '<?php echo $this->Html->url(array('controller' => 'remessas'), true); ?>/index/';
        url += jQuery('#idSearch').val();
        location.href = url;
      });

     $(document).keypress(function(e) {
        if (e.which == 13) {
          var url = '<?php echo $this->Html->url(array('controller' => 'remessas'), true); ?>/index/';
          url += jQuery('#idSearch').val();
          location.href = url;
        }
      });

    });
</script>
