<div class="widget-box">
  <div class="widget-header">
    <h4>REMESSA</h4>

    <?php //pr($itens[0]['ItemOrcamento']['orcamentos_id']);exit;
          //pr($itens[0]['ItemOrcamento']['materiais_id']);exit;
          //pr($itens[0]['ItemOrcamento']['pedidos_id']);exit;
          //pr($remessas[0]['ItemRemessa']['orcamentos_id']);exit;
          //pr($remessas[0]['ItemRemessa']['materiais_id']);exit;
          //pr($remessas[0]['ItemRemessa']['pedidos_id']);exit;

    ?>

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
                    <?php if($value['ItemOrcamento']['realizou_remessa'] !='' && $value['ItemOrcamento']['realizou_remessa'] !=1){ ?>
                    <option
                      value="<?php echo $value['ItemOrcamento']['id']; ?>"
                      data-material="<?php echo $value['ItemOrcamento']['materiais_id']; ?>"
                      data-quantidade="<?php echo $value['ItemOrcamento']['quantidade']; ?>"
                      data-setor_id="<?php echo $value['ItemOrcamento']['setor_id']; ?>"
                      data-pedidos_id="<?php echo $value['ItemOrcamento']['pedidos_id']; ?>"
                      data-orcamentos_id="<?php echo $value['ItemOrcamento']['orcamentos_id']; ?>"
                      data-equipamentos_id="<?php echo $value['ItemOrcamento']['equipamentos_id']; ?>"
                      data-setor_id="<?php echo $value['ItemOrcamento']['setor_id']; ?>"
                      ><?php echo $value['ItemOrcamento']['quantidade']. ' - ' .$value['Material']['nome'] ; ?></option>
                  <?php }}} ?>
              </select>
              <?php if($transf['Remessa']['id']) { ?>
                <input type="button" name="add_item" id="add_item" value="Adicionar &ggg;" />
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Remessa</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; ">
              <select name="select2" id="select2" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($remessas)){ ?>
                  <?php foreach ($remessas as $value) { ?>
                    <option
                      value="<?php echo $value['ItemRemessa']['id']; ?>"
                      data-material="<?php echo $value['ItemRemessa']['materiais_id']; ?>"
                      data-quantidade="<?php echo $value['ItemRemessa']['quantidade']; ?>"
                      data-setor_id="<?php echo $value['ItemRemessa']['setor_id']; ?>"
                      data-pedidos_id="<?php echo $value['ItemRemessa']['pedidos_id']; ?>"
                      data-orcamentos_id="<?php echo $value['ItemRemessa']['orcamentos_id']; ?>"
                      data-remessas_id="<?php echo $value['ItemRemessa']['remessas_id']; ?>"
                      ><?php echo $value['ItemRemessa']['quantidade']. ' - ' .$value['Material']['nome'] ; ?></option>
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
        $.post('/patrimonio/orcamentos/remessa_central/', {
          'data[ItemRemessa][remessas_id]': '<?php echo $transf['Remessa']['id']; ?>',
          'data[ItemRemessa][materiais_id]': $(value).data('material'),
          'data[ItemRemessa][quantidade]': $(value).data('quantidade'),
          'data[ItemRemessa][setor_id]': $(value).data('setor_id'),
          'data[ItemRemessa][pedidos_id]': $(value).data('pedidos_id'),
          'data[ItemRemessa][orcamentos_id]': $(value).data('orcamentos_id'),
          'data[ItemRemessa][equipamentos_id]': $(value).data('equipamentos_id'),
          'data[ItemRemessa][setor_id]': $(value).data('setor_id'),
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
        $.post('/patrimonio/orcamentos/delete_remessa/', {
          'data[ItemRemessa][remessas_id]': '<?php echo $transf['Remessa']['id']; ?>',
          'data[ItemRemessa][materiais_id]': $(value).data('material'),
          'data[ItemRemessa][quantidade]': $(value).data('quantidade'),
          'data[ItemRemessa][setor_id]': $(value).data('setor_id'),
          'data[ItemRemessa][pedidos_id]': $(value).data('pedidos_id'),
          'data[ItemRemessa][orcamentos_id]': $(value).data('orcamentos_id'),
          'data[ItemRemessa][setor_id]': $(value).data('setor_id'),
          'data[ItemRemessa][id]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });

});
</script>
