
<div class="widget-box">
  <div class="widget-header">
    <h4>TRANSFERENCIA</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body" style="overflow-y: auto; max-height: 400px;">
    <div class="widget-main">

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Material</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; text-align: right;">
              <select name="select1" id="select1" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($itens)){ ?>
                  <?php foreach ($itens as $value) { ?>
                    <?php if($value['ItemRemessa']['realizou_saida'] <> 1) { ?>
                    <option
                      value="<?php echo $value['ItemRemessa']['id']; ?>"
                      data-material="<?php echo $value['ItemRemessa']['materiais_id']; ?>"
                      data-quantidade_transferida="<?php echo $value['ItemRemessa']['quantidade']; ?>"
                      data-setor_id="<?php echo $value['ItemRemessa']['setor_id']; ?>"
                      data-pedidos_id="<?php echo $value['ItemRemessa']['pedidos_id']; ?>"
                      data-orcamentos_id="<?php echo $value['ItemRemessa']['orcamentos_id']; ?>"
                      data-equipamentos_id="<?php echo $value['ItemRemessa']['equipamentos_id']; ?>"
                      ><?php echo $value['ItemRemessa']['quantidade']. ' - ' .$value['Material']['nome'] ; ?></option>
                  <?php }}} ?>
              </select>
              <?php if($transf['Transferencia']['id']) { ?>
                <input type="button" name="add_item" id="add_item" value="Adicionar &ggg;" />
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Transferencia</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; ">
              <select name="select2" id="select2" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($transferencias)){ ?>
                  <?php foreach ($transferencias as $value) { ?>
                    <option
                      value="<?php echo $value['ItemTransferencia']['id']; ?>"
                      data-material="<?php echo $value['ItemTransferencia']['materiais_id']; ?>"
                      data-quantidade_transferida="<?php echo $value['ItemTransferencia']['quantidade_transferida']; ?>"
                      data-transferencia_id="<?php echo $value['ItemTransferencia']['transferencia_id']; ?>"
                      data-pedidos_id="<?php echo $value['ItemTransferencia']['pedidos_id']; ?>"
                      ><?php echo $value['ItemTransferencia']['quantidade_transferida']. ' - ' .$value['Material']['nome'] ; ?></option>
                  <?php }} ?>
                </select>

                  <input type="button" name="remove_item" id="remove_item" value="&Ll; Remover"/>

              </div>
            </div>
          </div>
        </div>

    </div>
  </div>
</div>

<script type="text/javascript">
  $(function () {
    $('#add_item').click(function() {
      var associados = $('#select1 option:selected');
      associados.remove().appendTo('#select2');
      $.each(associados, function( index, value ) {
        $.post('/patrimonio/remessas/transferencia_filial/', {
          'data[ItemTransferencia][transferencia_id]': '<?php echo $transf['Transferencia']['id']; ?>',
          'data[ItemTransferencia][materiais_id]': $(value).data('material'),
          'data[ItemTransferencia][quantidade_transferida]': $(value).data('quantidade_transferida'),
          'data[ItemTransferencia][setor_id]': $(value).data('setor_id'),
          'data[ItemTransferencia][pedidos_id]': $(value).data('pedidos_id'),
          'data[ItemTransferencia][orcamentos_id]': $(value).data('orcamentos_id'),
          'data[ItemTransferencia][equipamentos_id]': $(value).data('equipamentos_id'),
          'data[ItemOrcamento][id]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });

    $('#remove_item').click(function() {
      var associados = $('#select2 option:selected');
      associados.remove().appendTo('#select3');
      $.each(associados, function( index, value ) {
        $.post('/patrimonio/remessas/delete_transferencia/', {
          'data[ItemTransferencia][materiais_id]': $(value).data('material'),
          'data[ItemTransferencia][quantidade_transferida]': $(value).data('quantidade_transferida'),
          'data[ItemTransferencia][transferencia_id]': $(value).data('transferencia_id'),
          'data[ItemTransferencia][pedidos_id]': $(value).data('pedidos_id'),
          'data[ItemTransferencia][id]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });

});
</script>
