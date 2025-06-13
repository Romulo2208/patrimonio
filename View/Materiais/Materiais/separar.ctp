
<div class="widget-box">
  <div class="widget-header">
    <h4>PEDIDO</h4>

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
            <h4>Itens</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; text-align: right;">
              <select name="select1" id="select1" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($itens)){ ?>
                  <?php foreach ($itens as $value) {  ?>
                    <option
                      value="<?php echo $value['Material']['id']; ?>"
                      data-material="<?php echo $value['Material']['id']; ?>"
                      data-quantidade_pedido="<?php echo 0; ?>"
                      data-quantidade_fornecido="<?php echo 0; ?>"
                      data-pedidos_id="<?php echo $pedido['Pedido']['id']; ?>"
                      ><?php echo $value['Material']['nome']; ?></option>
                  <?php }} ?>
              </select>
              
                <input type="button" name="add_item" id="add_item" value="Adicionar &ggg;" />

            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Pedidos</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; ">
              <select name="select2" id="select2" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($pedidos)){ ?>
                  <?php foreach ($pedidos as $value) { ?>
                    <option
                      value="<?php echo $value['ItemPedido']['pedidos_id']; ?>"
                      data-materiais_id="<?php echo $value['ItemPedido']['materiais_id']; ?>"
                      data-id="<?php echo $value['ItemPedido']['id']; ?>"
                      ><?php echo $value['Material']['nome']; ?></option>
                  <?php }} ?>
                </select>

                  <input type="button" name="remove_item" id="remove_item" value="&Ll; Remover"/>

                  <!-- <select name="select3" id="select3" multiple="multiple" style="width: 0%; height: 0px;" hidden/> -->

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
        $.post('/patrimonio/materiais/pedido/', {
          'data[ItemPedido][materiais_id]': $(value).data('material'),
          'data[ItemPedido][quantidade_pedido]': $(value).data('quantidade_pedido'),
          'data[ItemPedido][quantidade_fornecido]': $(value).data('quantidade_fornecido'),
          'data[ItemPedido][pedidos_id]': $(value).data('pedidos_id')
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
        $.post('/patrimonio/materiais/delete_pedido/', {
          'data[ItemPedido][id]': $(value).data('id')
        },
        function(data) {
          console.log(data);
        });
      });
    });

});
</script>
