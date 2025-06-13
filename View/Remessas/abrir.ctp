<div class="widget-box">
  <div class="widget-header">
    <h4>PROTOCOLO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Remessa', array('class' => 'form-horizontal')); ?>

      <table class="table table-bordered table-striped" style="margin-top: 15px;">
        <thead>
          <tr>
            <td width="20">Pedido</td>
            <td width="50">Data do Pedido</td>
            <td width="50">Quantidade</td>
            <td width="130">Descri&ccedil;&atilde;o</td>
            <td width="20">Filial</td>
            <td></td>
          </tr>
        </thead>
        <tbody>
          <?php foreach ($remessas as $remessa) { ?>
            <tr>
              <td><?php echo $remessa['Pedido']['id']; ?></td>
              <td><?php echo $remessa['Pedido']['data_hora_registro']; ?></td>
              <td><?php echo $remessa['ItemRemessa']['quantidade']; ?></td>
              <td><?php echo $remessa['Material']['nome']; ?></td>
              <td><?php echo $remessa['Setor']['descricao']; ?></td>
              <!-- <td>
                <label>
                  <input type="checkbox"/>
                  <span class="lbl"></span>
                </label>
              </td> -->
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
