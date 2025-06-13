
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
                    <?php if($value['ItemRemessa']['realizou_entrada'] <> 1) { ?>
                    <option
                      value="<?php echo $value['ItemRemessa']['pedidos_id']; ?>"
                      data-material="<?php echo $value['ItemRemessa']['materiais_id']; ?>"
                      data-quantidade="<?php echo $value['ItemRemessa']['quantidade']; ?>"
                      data-data_hora_registro="<?php echo date('Y-m-d'); ?>"
                      data-pedidos_id="<?php echo $value['ItemRemessa']['pedidos_id']; ?>"
                      data-estoque="<?php echo $value['Material']['quantidade']; ?>"
                      data-remessas_id="<?php echo $value['ItemRemessa']['remessas_id']; ?>"
                      data-usuarios_id="<?php echo $this->Session->read('Auth.User.id'); ?>"
                      data-setor_id="<?php echo $this->Session->read('Auth.User.setor'); ?>"
                      ><?php echo $value['ItemRemessa']['quantidade'] .' - ' .$value['Material']['nome']; ?></option>
                  <?php }}} ?>
              </select>


                <input type="button" name="add_item" id="add_item" value="Adicionar &ggg;" />
            </div>
          </div>
        </div>
      </div>

      <div class="span6">
        <div class="widget-box">
          <div class="widget-header">
            <h4>Entrada</h4>
            <span class="widget-toolbar">
              <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
          </div>

          <div class="widget-body">
            <div class="widget-main" style="height: 180px; ">
              <select name="select2" id="select2" multiple="multiple" style="width: 100%; height: 120px;">
                <?php if(sizeof($entradas)){ ?>
                  <?php foreach ($entradas as $value) { ?>
                    <option
                      value="<?php echo $value['Entrada']['id']; ?>"
                      data-quantidade_entrada="<?php echo $value['Entrada']['quantidade_entrada']; ?>"
                      data-materiais_id="<?php echo $value['Entrada']['materiais_id']; ?>"
                      data-pedidos_id="<?php echo $value['Entrada']['pedidos_id']; ?>"
                      data-id="<?php echo $value['Entrada']['id']; ?>"
                      ><?php echo $value['Entrada']['quantidade_entrada'] .' - ' .$value['Material']['nome']; ?></option>
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
        $.post('/patrimonio/remessas/entrada/', {
          'data[Entrada][materiais_id]': $(value).data('material'),
          'data[Entrada][quantidade_entrada]': $(value).data('quantidade'),
          'data[Entrada][data_entrada]': $(value).data('data_hora_registro'),
          'data[Entrada][pedidos_id]': $(value).data('pedidos_id'),
          'data[Entrada][usuarios_id]': $(value).data('usuarios_id'),
          'data[Entrada][remessas_id]': $(value).data('remessas_id'),
          'data[Entrada][setor_id]': $(value).data('setor_id'),
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
        $.post('/patrimonio/remessas/delete_entrada/', {
          'data[Material][id]': $(value).data('materiais_id'),
          'data[Material][quantidade]': $(value).data('quantidade_entrada'),
          'data[Material][pedidos_id]': $(value).data('pedidos_id'),
          'data[Entrada][id]': $(value).val(),
          'data[Entrada][quantidade_entrada]': $(value).val()
        },
        function(data) {
          console.log(data);
        });
      });
    });

});
</script>
