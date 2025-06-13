
<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO OR&Ccedil;AMENTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Orcamento', array('class' => 'form-horizontal')); ?>

      <span class="materiais" >
        <div class="row-fluid" style="margin-top: 10px;">
          <input type="hidden" name="data[0][Orcamento][usuarios_id]" value="<?php echo $this->Session->read('Auth.User.id'); ?>" />
          <input type="hidden" name="data[0][Orcamento][data_hora_registro]" value="<?php echo date('Y-m-d H:i:s'); ?>" />
          <input type="hidden" name="data[0][Orcamento][compras_id]" value="<?php echo $id; ?>" />
          <input type="hidden" name="data[0][Orcamento][situacao]" value="1" />

          <div class="input-prepend">
            <label>Fornecedor </label>
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <span class="input-icon">
              <select name="data[0][Orcamento][fornecedores_id]" class="input-large select clear" onchange="getDescricao(this);" required>
                <option value=''></option>
                <?php
                foreach ($fornecedores as $k => $f) {
                  echo "<option value='{$k}'>{$f}</option>";
                }
                ?>
              </select>
            </span>
          </div>

          <div class="input-prepend">
            <label>Prazo de Entrega</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="data[0][Orcamento][prazo_entrega]" class="input-small clear" min="0" required />
            </span>
          </div>

          <div class="input-prepend">
            <label>Pagamento</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="data[0][Orcamento][pagamento]" class="input-small clear" min="0" required />
            </span>
          </div>

          <div class="input-prepend">
            <label>Transportadora</label>
            <span class="add-on">
              <i class=" icon-exchange"></i>
            </span>
            <span class="input-icon input-icon-right">
              <input type="text" name="data[0][Orcamento][transportadora]" class="input-medium clear" min="0" required />
            </span>
          </div>

          <input type="hidden" name="data[0][Orcamento][realizou_remessa]" value="0"/>

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <button type="button" onclick="addOrcamento(this);" class="green"><i class="icon-plus"></i></button>
            </span>
          </div>

          <table class="table table-bordered table-striped" style="margin-top: 15px;">
            <thead>
              <tr>
                <td width="20">Quantidade</td>
                <td width="50">Maquin&aacute;rio</td>
                <td width="50">Filial</td>
                <td width="530">Descri&ccedil;&atilde;o</td>
                <td width="20">Unit&aacute;rio</td>
                <td width="40">total</td>
              </tr>
            </thead>
            <tbody>
              <?php foreach ($compras as $n => $compra): ?>
                <?php if($compra['ItemCompra']['comprou'] != '1'){ ?>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][id]" value="<?php echo $compra['ItemCompra']['id']; ?>"/>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][materiais_id]" value="<?php echo $compra['ItemCompra']['materiais_id']; ?>"/>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][equipamentos_id]" value="<?php echo $compra['ItemCompra']['equipamentos_id']; ?>"/>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][compras_id]" value="<?php echo $compra['Compra']['id']; ?>"/>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][setor_id]" value="<?php echo $compra['Setor']['id']; ?>"/>
                <input type="hidden" name="data[0][Item][<?php echo $n; ?>][pedidos_id]" value="<?php echo $compra['Pedido']['id']; ?>"/>

                <tr>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][quantidade]" class="input-mini quantidade" min="0" value="<?php echo $compra['ItemCompra']['quantidade_pedido']; ?>" readonly/></td>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][descricao]" class="input-medium" min="0" value="<?php echo $compra['Equipamento']['descricao']; ?>" readonly/></td>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][setor]" class="input-small" min="0" value="<?php echo $compra['Setor']['descricao']; ?>" readonly/></td>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][nome]" class="input-xxlarge" min="0" value="<?php echo $compra['Material']['nome']; ?>" readonly/></td>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][unitario]" class="input-mini clear unitario" min="0"  onchange="calc(this);" onkeypress="maskReal(this);" /></td>
                  <td><input type="text" name="data[0][Item][<?php echo $n; ?>][total]" class="input-mini clear total" min="0" onkeypress="maskReal(this);" /></td>
                </tr>
                <?php } ?>
              <?php endforeach; ?>
            </tbody>
          </table>
        </div>
      </span>

      <div class="control-group">
        <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
      </div>
      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>

<script type="text/javascript">
$(function () {
  $(".chzn-select").chosen();
});

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}

function addOrcamento(e) {
  var field = $(e).parent().parent().parent();
  var clone = $($(field)).clone().appendTo($(field).parent());

  $(clone).find('select, input').each(function() {
    let n = $(this).attr('name').substring(4, 8).replace(/[^\w\s]/gi, '');
    let name = $(this).attr('name').replace(n, parseInt(n) + 1);
    $(this).attr('name', name);
  });

  $(clone).find('.clear').val('');
  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeOrcamento(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));
}

function removeOrcamento(e) {
  $(e).parent().parent().parent().remove();
}

function calc(e) {
  let field = $(e).parent().parent();
  let quantidade = parseFloat($(field).find('.quantidade').val());
  let unitario = parseFloat($(field).find('.unitario').val());
  $(field).find('.total').val((quantidade * unitario).toFixed(2));
}

function maskReal(e) {
  var el = e
 	,exec = function(v) {
 	v = v.replace(/\D/g,"");
 	v = new String(Number(v));
 	var len = v.length;
 	if (1== len)
 	v = v.replace(/(\d)/,"0.0$1");
 	else if (2 == len)
 	v = v.replace(/(\d)/,"0.$1");
 	else if (len > 2) {
 	v = v.replace(/(\d{2})$/,'.$1');
 	}
 	return v;
 	};

 	setTimeout(function(){
 	el.value = exec(el.value);
 	},1);
 }

</script>
