
<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR PEDIDO</h4>

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
            <h4>Pedidos</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 260px; text-align: right;">
              <select name="select1" id="select1" multiple="multiple" style="width: 100%; height: 250px;">
                <?php if(sizeof($itens)){ ?>
                  <?php foreach ($itens as $value) { ?>
                    <?php if(!$value['ItemPedido']['quantidade_fornecido'] <> '') { ?>
                    <option
                      value="<?php echo $value['ItemPedido']['id']; ?>"
                      data-material="<?php echo $value['ItemPedido']['materiais_id']; ?>"
                      data-quantidade_pedido="<?php echo $value['ItemPedido']['quantidade_pedido']; ?>"
                      data-quantidade_fornecido="<?php echo $value['ItemPedido']['quantidade_fornecido']; ?>"
                      data-pedidos_id="<?php echo $value['ItemPedido']['pedidos_id']; ?>"
                      data-aplicacao="<?php echo $value['ItemPedido']['aplicacao']; ?>"
                      data-equipamentos_id="<?php echo $value['ItemPedido']['equipamentos_id']; ?>"
                      ><?php echo $value['ItemPedido']['quantidade_pedido'] .' - '. $value['Material']['nome']; ?></option>
                  <?php }}} ?>
              </select>

              <?php if($this->request->data['Compra']['id']) { ?>
                <input type="button" name="add_item" id="add_item" value="Adicionar &ggg;" />
              <?php } ?>
            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Compras</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 260px; ">
              <select name="select2" id="select2" multiple="multiple" style="width: 100%; height: 250px;">
                <?php if(sizeof($compras)){ ?>
                  <?php foreach ($compras as $value) { ?>
                    <option
                      value="<?php echo $value['ItemCompra']['id']; ?>"
                      data-material="<?php echo $value['ItemCompra']['materiais_id']; ?>"
                      data-quantidade_pedido="<?php echo $value['ItemCompra']['quantidade_pedido']; ?>"
                      data-quantidade_fornecido="<?php echo $value['ItemCompra']['quantidade_fornecido']; ?>"
                      data-pedidos_id="<?php echo $value['ItemCompra']['pedidos_id']; ?>"
                      data-aplicacao="<?php echo $value['ItemCompra']['aplicacao']; ?>"
                      data-equipamentos_id="<?php echo $value['ItemCompra']['equipamentos_id']; ?>"
                      ><?php echo $value['ItemCompra']['quantidade_pedido'] .' - '. $value['Material']['nome']; ?></option>
                  <?php }} ?>
                </select>

                <?php if($this->request->data['Pedido']['id']) { ?>
                  <input type="button" name="remove_item" id="remove_item" value="&Ll; Remover"/>
                <?php } ?>
              </div>
            </div>
          </div>
        </div>

    </div>
  </div>
    <br><br>
</div>

<script type="text/javascript">
  $(function () {
    $('#add_item').click(function() {
      var associados = $('#select1 option:selected');
      associados.remove().appendTo('#select2');
      $.each(associados, function( index, value ) {
        $.post('/patrimonio/pedidos/pedido/', {
          'data[ItemCompra][compras_id]': '<?php echo $this->request->data['Compra']['id']; ?>',
          'data[ItemCompra][materiais_id]': $(value).data('material'),
          'data[ItemCompra][quantidade_pedido]': $(value).data('quantidade_pedido'),
          'data[ItemCompra][quantidade_fornecido]': $(value).data('quantidade_fornecido'),
          'data[ItemCompra][pedidos_id]': $(value).data('pedidos_id'),
          'data[ItemCompra][aplicacao]': $(value).data('aplicacao'),
          'data[ItemCompra][equipamentos_id]': $(value).data('equipamentos_id'),
          'data[ItemPedido][id]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });

    $('#remove_item').click(function() {
      var associados = $('#select2 option:selected');
      associados.remove().appendTo('#select1');
      $.each(associados, function( index, value ) {
        $.post('/patrimonio/pedidos/compra/', {
          'data[ItemPedido][pedidos_id]': '<?php echo $this->request->data['Pedido']['id']; ?>',
          'data[ItemPedido][materiais_id]': $(value).data('material'),
          'data[ItemPedido][quantidade_pedido]': $(value).data('quantidade_pedido'),
          'data[ItemPedido][quantidade_fornecido]': $(value).data('quantidade_fornecido'),
          'data[ItemPedido][aplicacao]': $(value).data('aplicacao'),
          'data[ItemPedido][equipamentos_id]': $(value).data('equipamentos_id'),
          'data[ItemCompra][id]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });
  });
</script>
