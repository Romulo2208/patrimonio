<?php ?>

<div class="page-header position-relative">
  <h1> Nota Fiscal <small> <i class="icon-double-angle-right"></i> <?php echo $this->Form->value('NotaFiscal.numero_nota'); ?> </small> </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
        <li class="active">
          <a data-toggle="tab" href="#nota-fiscal" class="nota-fiscal">
            <i class="icon-folder-close bigger-110"></i>
            Nota Fiscal
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#item" class="item">
            <i class="icon-check bigger-110"></i>
            Itens
            <span class="badge badge-important"><?php //echo sizeof($itens); ?></span>
          </a>
        </li>

        <li>
          <a data-toggle="tab" href="#financeiro" class="financeiro">
            <i class="icon-usd bigger-110"></i>
            Financeiro
            <span class="badge badge-important"><?php //echo sizeof($observacoes); ?></span>
          </a>
        </li>
      </ul>

      <div class="tab-content">
        <div id="nota-fiscal" class="tab-pane in active">
          <?php echo $this->Form->create('NotaFiscal', array('class'=>'form-horizontal')); ?>
          <?php echo $this->Form->input('id', array('required' => true)); ?>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">Natureza da Opera&ccedil;&atilde;o</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-pencil"></i>
                </span>
                <?php echo $this->Form->input('natureza_operacao', array('type' => 'text', 'label' => false, 'div' => false, 'empty' => true, 'class' => 'input-xxlarge', 'required' => true)); ?>
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">Fornecedor</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-list"></i>
                </span>
                <?php echo $this->Form->input('fornecedores_id', array('type' => 'select', 'label' => false, 'div' => false, 'empty' => true, 'class' => 'input-xxlarge', 'required' => true, 'option' => $fornecedores)); ?>
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">N&uacute;mero da Nota</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-edit"></i>
                </span>
                <?php echo $this->Form->input('numero_nota', array('type' => 'text', 'label' => false, 'div' => false,  'class' => 'input-large', 'required' => true)); ?>
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">Data de Emiss&atilde;o</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-calendar"></i>
                </span>
                <?php echo $this->Form->input('data_emissao', array('type' => 'text', 'label' => false, 'div' => false,  'class' => 'input-medium', 'data-date-format' => 'dd/mm/yyyy',  'required' => true)); ?>
              </div>
            </div>
          </p>

          <div class="form-actions" style="background-color: #fff;">
            <button class="btn btn-inverse" type="submit">
              <i class="icon-ok bigger-110"></i> Gravar
            </button>
            <!-- &nbsp; &nbsp; &nbsp;
            <button class="btn btn-inverse" type="button" onclick="javascript: location.href='../digitalizar/<?php echo $this->Form->value('NotaFiscal.id'); ?>'" >
              <i class="icon-folder-open bigger-110"></i> Arquivos
            </button>
            &nbsp; &nbsp; &nbsp;
            <button class="btn btn-inverse" type="button" onclick="javascript: window.open('../imprimir/<?php echo $this->Form->value('NotaFiscal.id'); ?>', '_blank')">
              <i class="icon-print bigger-110"></i> Imprimir
            </button> -->
          </div>

          <?php echo $this->Form->end(); ?>

        </div>

        <div id="item" class="tab-pane">
          <p>
            <a class='colorbox btn btn-small btn-primary btn-inverse' href="#Item"><i class="icon-edit"></i> Novo Item</a>
          </p>
          <hr />
          <table class="table table-striped table-bordered table-hover">
            <thead>
              <tr>
                <th>Tipo</th>
                <th>C&oacute;digo</th>
                <th>Especifica&ccedil;&atilde;o</th>
                <th>Valor</th>
                <th class="actions" style="width: 50px;"></th>
              </tr>
            </thead>
            <tbody
              <?php $total = 0; ?>
              <?php foreach ($itens as $item) { ?>
                <?php $total += $item['Item']['valor_total']; ?>
                <?php if(key($item) == 'Patrimonio') { ?>
                  <tr>
                    <td>Patrim&ocirc;nio</td>
                    <td><?php echo h($item['Patrimonio']['firma'].$item['Patrimonio']['codigo']); ?>&nbsp;</td>
                    <td><?php echo h($item['Patrimonio']['descricao']); ?>&nbsp;</td>
                    <td>R$ <?php echo number_format($item['Item']['valor_total'],2,',','.'); ?>&nbsp;</td>
                    <td><?php echo $this->Form->postLink(__('Deletar'), array('controller' => 'itens', 'action' => 'delete', $item['Item']['id'], 'PatrimonioItem', $this->Form->value('NotaFiscal.id')), array('class' => ''), __('Deseja deletar %s?', h($item['Patrimonio']['firma'].$item['Patrimonio']['codigo']))); ?></td>
                  </tr>
                <?php } ?>
                <?php if(key($item) == 'Material') { ?>
                  <tr>
                    <td>Material</td>
                    <td><?php echo h($item['Material']['id']); ?>&nbsp;</td>
                    <td><?php echo h("{$item['Material']['nome']} - {$item['Material']['descricao']}"); ?>&nbsp;</td>
                    <td>R$ <?php echo number_format($item['Item']['valor_total'],2,',','.'); ?>&nbsp;</td>
                    <td><?php echo $this->Form->postLink(__('Deletar'), array('controller' => 'itens', 'action' => 'delete', $item['Item']['id'], 'MaterialItem', $this->Form->value('NotaFiscal.id')), array('class' => ''), __('Deseja deletar %s?', h($item['Material']['nome']))); ?></td>
                  </tr>
                <?php } ?>
                <?php if(key($item) == 'Servico') { ?>
                  <tr>
                    <td>Servi&ccedil;o</td>
                    <td><?php echo h($item['Servico']['id']); ?>&nbsp;</td>
                    <td><?php echo h($item['Servico']['descricao']); ?>&nbsp;</td>
                    <td>R$ <?php echo number_format($item['Item']['valor_total'],2,',','.'); ?>&nbsp;</td>
                    <td><?php echo $this->Form->postLink(__('Deletar'), array('controller' => 'itens', 'action' => 'delete', $item['Item']['id'], 'ServicoItem', $this->Form->value('NotaFiscal.id')), array('class' => ''), __('Deseja deletar %s?', h($item['Servico']['descricao']))); ?></td>
                  </tr>
                <?php } ?>
              <?php } ?>
            </tbody>
          </table>
        </div>

        <div id="financeiro" class="tab-pane">
          <?php echo $this->Form->create('Pagamento', array('url' => array('controller' => 'notas_fiscais', 'action' => 'pagamento'), 'class'=>'form-horizontal')); ?>
          <?php echo $this->Form->input('id', array('type' => 'hidden')); ?>
          <?php echo $this->Form->input('notas_fiscais_id', array('type' => 'hidden', 'value' => $this->Form->value('NotaFiscal.id'))); ?>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">Forma de pagamento</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-list"></i>
                </span>
                <?php echo $this->Form->input('tipo_pagamentos_id', array('type' => 'select', 'label' => false, 'div' => false, 'empty' => true, 'class' => 'input-medium', 'required' => true, 'options' => array('1'=>'Dinheiro','2'=>'Cheque','3'=>'Boleto'))); ?>
              </div>
            </div>
          </p>

          <p>
            <div class="input-prepend">
              <label class="control-label" for="">Valor Total</label>
              <div class="controls">
                <span class="add-on">
                  <i class="icon-usd"></i>
                </span>
                <?php echo $this->Form->input('valor_total', array('type' => 'text', 'label' => false, 'div' => false,  'class' => 'input-small valor', 'required' => true)); ?> <br/>
                <span class="help-inline">Total cadastrado na nota <b>R$ <?php echo number_format($total,2,',','.'); ?></b></span>
                </div>
            </div>
          </p>



          <div class="form-actions" style="background-color: #fff;">
            <button class="btn btn-inverse" type="submit">
              <i class="icon-ok bigger-110"></i> Gravar
            </button>
          </div>

          <?php echo $this->Form->end(); ?>

        </div>
      </div>
    </div>
  </div>
</div>

<div style="display: none;" class="add-item">
  <div class="widget-box">
    <div class="widget-header">
      <h4>NOVO ITEM</h4>
    </div>

    <div class="widget-body">
      <div class="widget-main" style="padding: 0;">
        <?php echo $this->Form->create('NotaFiscal', array('url' => array('controller' => 'itens', 'action' => 'add'), 'class'=>'form-horizontal')); ?>
        <input type="hidden" name="data[Item][notas_fiscais_id]"  value="<?php echo $this->Form->value('NotaFiscal.id'); ?>" />
        <p>
          <div class="input-prepend">
            <label class="control-label" for="">Tipo</label>
            <div class="controls">
              <span class="add-on">
                <i class="icon-list"></i>
              </span>
              <select class="input-medium" name="data[Item][tipo]" onchange="tipo(this);" required>
                <option value=""></option>
                <option value="ServicoItem">Servi&ccedil;o</option>
                <option value="MaterialItem">Material</option>
                <option value="PatrimonioItem">Patrim&ocirc;nio</option>
              </select>
            </div>
          </div>
        </p>

        <p>
          <div class="input-prepend">
            <label class="control-label" for="">Especifica&ccedil;&atilde;o</label>
            <div class="controls" id="especificacao">
              <span class="add-on">
                <i class="icon-list"></i>
              </span>

              <select class="input-xlarge empty" required> <option value=""></option> </select>

              <select class="input-xlarge PatrimonioItem" name="data[Item][patrimonios_id]" style="display: none;" required>
                <option value=""></option>
                <?php foreach ($patrimonios as $patrimonio): ?>
                  <option value="<?php echo $patrimonio['Patrimonio']['id']; ?>"><?php echo "{$patrimonio['Patrimonio']['firma']} {$patrimonio['Patrimonio']['codigo']} - {$patrimonio['Patrimonio']['descricao']}"; ?></option>
                <?php endforeach; ?>
              </select>

              <select class="input-xlarge MaterialItem" name="data[Item][materiais_id]" style="display: none;" required>
                <option value=""></option>
                <?php foreach ($materiais as $material): ?>
                  <option value="<?php echo $material['Material']['id']; ?>"><?php echo $material['Material']['nome']; ?></option>
                <?php endforeach; ?>
              </select>

              <input type="text" class="input-xlarge ServicoItem" name="data[Item][descricao]" style="display: none;" required />
            </div>
          </div>
        </p>

        <p>
          <div class="input-prepend">
            <label class="control-label" for="">Quantidade</label>
            <div class="controls">
              <span class="add-on">
                <i class="icon-plus"></i>
              </span>
              <input type="number" class="input-small" id="quantidade" name="data[Item][quantidade]" required />
            </div>
          </div>
        </p>

        <p>
          <div class="input-prepend">
            <label class="control-label" for="">Valor Unit&aacute;rio</label>
            <div class="controls">
              <span class="add-on">
                <i class="icon-usd"></i>
              </span>
              <input type="text" class="input-small valor" id="unitario" name="data[Item][valor_unitario]" required />
            </div>
          </div>
        </p>

        <p>
          <div class="input-prepend">
            <label class="control-label" for="">Valor Total</label>
            <div class="controls">
              <span class="add-on">
                <i class="icon-usd"></i>
              </span>
              <input type="text" class="input-small valor" name="data[Item][valor_total]" required />
            </div>
          </div>
        </p>

        <div class="form-actions" style="margin: -15px; margin-top: 15px;">
          <button class="btn btn-inverse" type="submit">
            <i class="icon-ok bigger-110"></i> Gravar
          </button>
        </div>
        <?php echo $this->Form->end(); ?>
      </div>
    </div>
  </div>
</div>

<input type="hidden" id="url" value="<?php echo $this->Html->url('/', true); ?>" />
<script type="text/javascript">
$(function() {
  $('.valor').priceFormat({
      prefix: false,
      centsSeparator: ',',
      thousandsSeparator: '.'
  });

  $('#NotaFiscalDataEmissao').datepicker({
      dateFormat: 'yy-mm-dd',
      language: 'pt-BR',
      minDate: 0,
      maxDate: 'today'
  });

  var url = window.location.href.split('#');
  if(url.length>1) {
    $('a.'+url.pop()).click();
  }

  $('a.colorbox').colorbox({
    html: $('div.add-item').html(),
    onLoad: function () {
      $('#cboxClose, #cboxTitle, #cboxCurrent, #cboxNext, #cboxPrevious').remove();
    },
    onComplete:function() {
      $('.valor').priceFormat({
          prefix: false,
          centsSeparator: ',',
          thousandsSeparator: '.'
      });
    }
  });
});

function tipo(e) {
  $('select.empty, select.PatrimonioItem, select.MaterialItem, input.ServicoItem').attr('disabled', true).hide();
  $('#quantidade, #unitario').attr('disabled', false);

  if($(e).val() === 'MaterialItem') {
    $('select.MaterialItem').attr('disabled', false).show();
  }
  if($(e).val() === 'PatrimonioItem') {
    $('select.PatrimonioItem').attr('disabled', false).show();
  }
  if($(e).val() === 'ServicoItem') {
    $('input.ServicoItem').attr('disabled', false).show();
    $('#quantidade, #unitario').attr('disabled', true);
  }
}
</script>
