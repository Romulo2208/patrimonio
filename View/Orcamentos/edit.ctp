<?php

$count = 0;
$orcamento = array();
foreach ($orcamentos as $key => $value) {
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['usuarios_id'] = $value['Orcamento']['usuarios_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['data_hora_registro'] = $value['Orcamento']['data_hora_registro'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['compras_id'] = $value['Orcamento']['compras_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['situacao'] = $value['Orcamento']['situacao'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['fornecedores_id'] = $value['ItemOrcamento']['fornecedores_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['prazo_entrega'] = $value['ItemOrcamento']['prazo_entrega'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['pagamento'] = $value['ItemOrcamento']['pagamento'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Orcamento']['transportadora'] = $value['ItemOrcamento']['transportadora'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['materiais_id'] = $value['ItemOrcamento']['materiais_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['compras_id'] = $value['Orcamento']['compras_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['setor_id'] = $value['ItemOrcamento']['setor_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['equipamentos_id'] = $value['ItemOrcamento']['equipamentos_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['realizou_remessa'] = $value['ItemOrcamento']['realizou_remessa'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['pedidos_id'] = $value['ItemOrcamento']['pedidos_id'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['quantidade'] = $value['ItemOrcamento']['quantidade'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['aplicacao'] = $value['ItemOrcamento']['aplicacao'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['descricao'] = $value['Equipamento']['descricao'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['setor'] = $value['Setor']['descricao'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['nome'] = $value['Material']['nome'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['unitario'] = $value['ItemOrcamento']['unitario'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['total'] = $value['ItemOrcamento']['total'];
  $orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['comprar'] = $value['ItemOrcamento']['comprar'];
  //$orcamento[$value['ItemOrcamento']['fornecedores_id']]['Item'][$key]['enviar'] = $value['ItemOrcamento']['enviar'];
}
sort($orcamento);
?>

<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR OR&Ccedil;AMENTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Orcamento', array('class' => 'form-horizontal')); ?>

      <?php foreach ($orcamento as $key => $value) { ?>
        <span class="materiais">
          <div class="row-fluid" style="margin-top: 10px;">
            <input type="hidden" name="data[<?php echo $key; ?>][Orcamento][usuarios_id]" value="<?php echo $value['Orcamento']['usuarios_id']; ?>" />
            <input type="hidden" name="data[<?php echo $key; ?>][Orcamento][data_hora_registro]" value="<?php echo $value['Orcamento']['data_hora_registro']; ?>" />
            <input type="hidden" name="data[<?php echo $key; ?>][Orcamento][compras_id]" value="<?php echo $value['Orcamento']['compras_id']; ?>" />
            <input type="hidden" name="data[<?php echo $key; ?>][Orcamento][situacao]" value="<?php echo $value['Orcamento']['situacao']; ?>" />

            <div class="input-prepend">
              <label>Fornecedor </label>
              <span class="add-on">
                <i class="icon-list"></i>
              </span>
              <span class="input-icon">
                <select name="data[<?php echo $key; ?>][Orcamento][fornecedores_id]" class="input-large select clear" onchange="getDescricao(this);" required>
                  <option value=''></option>
                  <?php
                  foreach ($fornecedores as $k => $f) {
                    $selected = $k == $value['Orcamento']['fornecedores_id'] ? 'selected' : '';
                    echo "<option value='{$k}' {$selected} >{$f}</option>";
                  }
                  ?>
                </select>
              </span>
            </div>

            <div class="input-prepend">
              <label>Prazo de Entrega</label>
              <span class="add-on">
                <i class=" icon-edit"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="text" name="data[<?php echo $key; ?>][Orcamento][prazo_entrega]" class="input-small clear" min="0" required value="<?php echo $value['Orcamento']['prazo_entrega']; ?>" />
              </span>
            </div>

            <div class="input-prepend">
              <label>Pagamento</label>
              <span class="add-on">
                <i class=" icon-edit"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="text" name="data[<?php echo $key; ?>][Orcamento][pagamento]" class="input-small clear" min="0" required value="<?php echo $value['Orcamento']['pagamento']; ?>" />
              </span>
            </div>

            <div class="input-prepend">
              <label>Transportadora</label>
              <span class="add-on">
                <i class=" icon-edit"></i>
              </span>
              <span class="input-icon input-icon-right">
                <input type="text" name="data[<?php echo $key; ?>][Orcamento][transportadora]" class="input-medium clear" min="0" required value="<?php echo $value['Orcamento']['transportadora']; ?>" />
              </span>
            </div>

            <div class="input-prepend">
              <label> &nbsp; </label>
              <span class="input-icon input-icon-right">
                <?php if (sizeof($orcamento) == ++$count) { ?>
                  <button type="button" onclick="addOrcamento(this);" class="green"><i class="icon-plus"></i></button>
                <?php } else { ?>
                  <button type="button" onclick="removeOrcamento(this);" class="red"><i class="icon-trash"></i></button>
                <?php } ?>
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
                  <td width="10">Comprar</td>
                  <!-- <td width="10">Enviar</td> -->
                </tr>
              </thead>
              <tbody>
                <?php foreach ($value['Item'] as $n => $item): ?>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][materiais_id]" value="<?php echo $item['materiais_id']; ?>"/>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][compras_id]" value="<?php echo $item['compras_id']; ?>"/>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][setor_id]" value="<?php echo $item['setor_id']; ?>"/>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][pedidos_id]" value="<?php echo $item['pedidos_id']; ?>"/>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][equipamentos_id]" value="<?php echo $item['equipamentos_id']; ?>"/>
                  <input type="hidden" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][realizou_remessa]" value="<?php echo $item['realizou_remessa']; ?>"/>

                  <tr>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][quantidade]" class="input-mini quantidade" min="0" value="<?php echo $item['quantidade']; ?>" readonly/></td>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][descricao]" class="input-medium" min="0" value="<?php echo $item['descricao']; ?>" readonly/></td>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][setor]" class="input-small" min="0" value="<?php echo $item['setor']; ?>" readonly/></td>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][nome]" class="input-xxlarge" min="0" value="<?php echo $item['nome']; ?>" readonly/></td>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][unitario]" class="input-mini clear unitario valor" min="0" required value="<?php echo $item['unitario']; ?>" onchange="calc(this);" onkeypress="maskReal(this);" /></td>
                    <td><input type="text" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][total]" class="input-mini clear total" min="0" required value="<?php echo $item['total']; ?>" onkeypress="maskReal(this);" /></td>
                    <td>
                      <label>
                        <input type="checkbox" name="data[<?php echo $key; ?>][Item][<?php echo $n; ?>][comprar]" value="1" <?php echo $item['comprar'] ? 'checked' : ''; ?> />
                        <span class="lbl"></span>
                      </label>
                    </td>
                    <!-- <td>
                      <label>
                        <input type="checkbox" name="data[<?php //echo $key; ?>][Item][<?php //echo $n; ?>][enviar]" value="1" <?php //echo $item['enviar'] ? 'checked' : ''; ?> />
                        <span class="lbl"></span>
                      </label>
                    </td> -->
                  </tr>
                <?php endforeach; ?>
              </tbody>
            </table>

          </div>
        </span>
      <?php } ?>

      <div class="row-fluid" style="margin-top: 10px;">
        <div class="input-prepend">
            <label>Situa&ccedil;&atilde;o</label>
            <span class="add-on">
              <i class="icon-exchange"></i>
            </span>
            <?php echo $this->Form->input('situacao', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Pendente', '2' => 'Atendido'), 'default' => $situacao, 'required' => true, 'class' => 'input-medium')); ?>
        </div>

        <div class="input-prepend" style="float: right;">
          <label> &nbsp; </label>
          <span class="input-icon input-icon-right">
            <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
          </span>
        </div>
      <div/>
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
