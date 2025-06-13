<div class="page-header position-relative">
  <h1>
    <?php echo __('Ordem de Servi&ccedil;o'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('OS'); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">


      <div class="tab-content">
        <div id="item" class="tab-pane in active">
          <p>
            <a href="<?php echo $this->Html->url(array('action' => 'add'), true); ?>" class="btn btn-small btn-inverse icon-edit popup"  data-rel="doc"> Nova O.S</a>
            <button type="button" id="btnSearch" class="btn btn-small" value="Pesquisar" style="float: right;"><span class='icon-search'></span></button>
            <input type="text" id="idSearch" placeholder="Numero da O.S" style="width: 200px; float: right;"/>
          </p>
          <hr />
          <table class="table table-bordered table-striped">
            <thead>
              <tr>
                <th style="width: 200px;">Ordem de Servi&ccedil;o</th>
                <th style="width: 200px;">Filial</th>
                <th style="width: 350px;">Servi&ccedil;o Executado</th>
                <th style="width: 100px;">Equipamento</th>
                <th style="width: 200px;">Especifica&ccedil;&atilde;o</th>
                <th style="width: 150px;">Data de Entrada</th>
                <th style="width: 250px;">Usuario</th>
                <th style="width: 50px;">Situa&ccedil;&atilde;o</th>
                <th style="width: 250px;"></th>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($servicos as $servico) { ?>
                <tr>
                  <td><?php echo $servico['OrdemServico']['id']; ?></td>
                  <td><?php echo $servico['Setor']['descricao']; ?></td>
                  <td><?php echo ($servico['OrdemServico']['servico'] == 1 ? "Oficina" : "Industria"); ?></td>
                  <td><?php echo $servico['OrdemServico']['equipamento']; ?></td>
                  <td><?php echo $servico['OrdemServico']['especificacao_servico']; ?></td>
                  <td><?php echo $servico['OrdemServico']['data']; ?></td>
                  <td><?php echo $servico['Usuario']['nome']; ?></td>
                  <td style="text-align: center;"><?php if($servico['OrdemServico']['situacao'] == 1){ echo "<span class='icon-edit gray bigger-160' title='Pendente'></span>";}
                  elseif ($servico['OrdemServico']['situacao'] == 2) { echo "<span class='icon-edit orange bigger-160' title='Aprovado'></span>";}
                  elseif ($servico['OrdemServico']['situacao'] == 3) { echo "<span class='icon-edit green bigger-160' title='Atendido'></span>";}
                  ?></td>
                  <td style="text-align: center;">
                      <a href="<?php echo $this->Html->url(array('action' => 'edit', $servico['OrdemServico']['id']), true); ?>" class="btn btn-mini btn-info popup" data-rel="doc"><i class="icon-edit bigger-110"></i>    O.S  </a>
                      <a href="<?php echo $this->Html->url(array('action' => 'servico_itens', $servico['OrdemServico']['id']), true); ?>" class="btn btn-mini btn-success popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Servi&ccedil;o</a>
                      <a href="<?php echo $this->Html->url(array('action' => 'servico_pecas', $servico['OrdemServico']['id']), true); ?>" class="btn btn-mini btn-inverse popup" data-rel="doc"><i class="icon-edit bigger-110"></i> Pe&ccedil;as</a>
                     <?php if($servico['OrdemServico']['aprovacao'] <> 2){ ?>
                      <a href="<?php echo $this->Html->url(array('action' => 'imprimir', $servico['OrdemServico']['id']), true); ?>" class="btn btn-mini btn-light" data-rel="doc" target="blank"><i class="icon-file bigger-110"></i> Imprimir O.S</a>
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
    var url = '<?php echo $this->Html->url(array('controller' => 'ordens_servicos'), true); ?>/index/';
    url += jQuery('#idSearch').val();
    location.href = url;
  });

  $(document).keypress(function(e) {
    if (e.which == 13) {
      var url = '<?php echo $this->Html->url(array('controller' => 'ordens_servicos'), true); ?>/index/';
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
