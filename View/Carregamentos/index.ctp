<div class="page-header position-relative">
  <h1>
    <?php echo __('Carregamentos'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __(''); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">


      <div class="tab-content">
        <div id="item" class="tab-pane in active">
          <p>
              <?php if(!in_array($this->Session->read('Perfil.id'), array('11'))) { ?>
            <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Novo Carregamento</a>
            <?php } ?>
            <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
            <input type="text" id="idSearch" placeholder="Numero do Carregamento" style="width: 200px; float: right;"/>
          </p>
          <hr />

          <?php if(!in_array($this->Session->read('Perfil.id'), array('11'))) { ?>
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width: 250px;">Numero</th>
                <th style="width: 150px;">Placa</th>
                <th style="width: 350px;">Produto</th>
                <th style="width: 100px;">Quantidade</th>
                <th style="width: 200px;">Data</th>
                <th style="width: 150px;">Filial</th>
                <th style="width: 150px;">Observa&ccedil;&atilde;o</th>
                <?php if(!in_array($this->Session->read('Perfil.id'), array('11'))) { ?>
                <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                <?php } ?>
                <th style="width: 250px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($carregamentos as $carregamento) { ?>
                <tr>
                  <td><?php echo $carregamento['Carregamento']['id']; ?></td>
                  <td><?php echo $carregamento['Carregamento']['placa']; ?></td>
                  <td><?php echo $carregamento['Produto']['descricao']; ?></td>
                  <td><?php echo $carregamento['Carregamento']['quantidade']; ?></td>
                  <td><?php echo date('d/m/Y', strtotime($carregamento['Carregamento']['data_hora_registro'])); ?></td>
                  <td><?php echo $carregamento['Setor']['descricao']; ?></td>
                  <td><?php echo $carregamento['Carregamento']['observacao']; ?></td>
                  <?php if(!in_array($this->Session->read('Perfil.id'), array('11'))) { ?>
                  <td style="text-align: center;"><?php echo ($carregamento['Carregamento']['situacao'] == 0 ? "<span class='icon-edit gray bigger-160' title='Aguardando Carregamento'></span>" : "<span class='icon-check green bigger-160' title='Carregado'></span>"); ?></td>
                <?php } ?>
                  <td style="text-align: center;">
                    <?php if(!in_array($this->Session->read('Perfil.id'), array('11'))) { ?>
                      <a href="<?php echo $this->Html->url(array('action' => 'edit', $carregamento['Carregamento']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Editar</a>
                      <!-- <?php //echo $this->Form->postLink(__('<i class="icon-check icon-2x icon-only"/>'), array('action' => 'carregou', $carregamento['Carregamento']['id']), array('title' => 'Atualizar', 'class' => 'blue', 'escape' => false), __('Deseja atualizar este Carregamento?')); ?> -->
                      <!-- <a href="<?php //echo $this->Html->url(array('action' => 'carregou', $carregamento['Carregamento']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-check bigger-110"></i> OK</a> -->
                    <?php }else{ ?>
                      <?php echo $this->Form->postLink(__('<i class="icon-check icon-2x icon-only"/>'), array('action' => 'carregou', $carregamento['Carregamento']['id']), array('title' => 'Atualizar', 'class' => 'blue', 'escape' => false), __('Deseja atualizar este Carregamento?')); ?>
                      <!-- <a href="<?php //echo $this->Html->url(array('action' => 'carregou', $carregamento['Carregamento']['id']), true); ?>" class="btn btn-mini btn-info" data-rel="doc" target="blank"><i class="icon-check bigger-110"></i> OK</a> -->
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
          <?php }else{ ?>


          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                
                <th style="width: 150px; text-align: center; font-size: 2.0em;">Placa</th>
                <th style="width: 350px; text-align: center; font-size: 2.0em;">Produto</th>
                <th style="width: 100px; text-align: center; font-size: 2.0em;">Quantidade</th>
                <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                <th style="width: 250px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($carregamentos as $carregamento) { ?>
                <tr>
                  <td style="text-align: center; font-size: 2.0em;"><?php echo strtoupper($carregamento['Carregamento']['placa']); ?></td>
                  <td style="text-align: center; font-size: 2.0em;"><?php echo $carregamento['Produto']['descricao']; ?></td>
                  <td style="text-align: center; font-size: 2.0em;"><?php echo $carregamento['Carregamento']['quantidade']; ?></td>
                  <td style="text-align: center;"><?php echo ($carregamento['Carregamento']['situacao'] == 0 ? "<span class='icon-edit gray bigger-160' title='Aguardando Carregamento'></span>" : "<span class='icon-check green bigger-160' title='Carregado'></span>"); ?></td>
                  <td style="text-align: center;">
                      <?php echo $this->Form->postLink(__('<i class="icon-check icon-3x icon-only"/>'), array('action' => 'carregou', $carregamento['Carregamento']['id']), array('title' => 'Atualizar', 'class' => 'blue', 'escape' => false), __('Deseja atualizar este Carregamento?')); ?>
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
          <?php } ?>
          
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

  function confirmExclusao() {
    if (confirm("Tem certeza que deseja excluir essa categoria?")) {
      var url = '<?php echo $this->Html->url(array('controller' => 'carregamentos'), true); ?>/carregou/';
      location.href = url;
    }
  }

});
</script>
