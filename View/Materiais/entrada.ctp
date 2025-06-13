<div class="page-header position-relative">
  <h1>
    <?php echo __('Materiais'); ?> <small> <i class="icon-double-angle-right"></i> <?php echo __('Entrada'); ?>        </small>
  </h1>
</div>

<div class="row-fluid">
  <div class="span12">
    <div class="tabbable">
      <ul class="nav nav-tabs" id="myTab">
          <li> <?php echo $this->Html->link(__('<i class="blue icon-folder-open bigger-110"></i> Produto'), array('controller' => 'materiais', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='reed icon-bullhorn bigger-110'></i> Estoque Minimo <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'estoque_minimo'), array('class' => '', 'escape' => false)); ?></li>
          <li class="active"> <?php echo $this->Html->link(__("<i class='green icon-cloud-upload bigger-110'></i> Entrada <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'entrada'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='red icon-cloud-download bigger-110'></i> Saida <span class='badge badge-important'></span>"), array('controller' => 'materiais', 'action' => 'saida'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='orange icon-list bigger-110'></i> Pedidos <span class='badge badge-important'></span>"), array('controller' => 'pedidos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <?php if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
          <li> <?php echo $this->Html->link(__("<i class='purple icon-credit-card bigger-110'></i> Requisi&ccedil;&atilde;o de Compras <span class='badge badge-important'></span>"),  array('controller' => 'compras', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='orange icon-briefcase bigger-110'></i> Or&ccedil;amentos <span class='badge badge-important'></span>"), array('controller' => 'orcamentos', 'action' => 'index'), array('class' => '', 'escape' => false)); ?> </li>
          <li> <?php echo $this->Html->link(__("<i class='black icon-book bigger-110'></i> Protocolos <span class='badge badge-important'></span>"),  array('controller' => 'remessas', 'action' => 'protocolos'), array('class' => '', 'escape' => false)); ?> </li>
          <?php } ?>
      </ul>





      <div class="tab-content">
        <div class="tab-pane in active">
          <div class="row-fluid">
						<div class="span4">
              <?php echo $this->Form->create('Entrada', array('class'=>'form-horizontal'));   ?>
              <?php echo $this->Form->input('usuarios_id', array('type'=>'hidden', 'value' => $this->Session->read('Auth.User.id'))); ?>
              <?php echo $this->Form->input('setor_id', array('type'=>'hidden', 'value' => $this->Session->read('Auth.User.setor'))); ?>

              <p>
                <?php //echo $this->Html->link(__(' Importar XML'), array('action' => 'importar'), array('class' => 'btn btn-small btn-success icon-cloud-upload bigger-110 popup', 'style' => '')); ?>

                <div class="input-prepend">
                  <label class="control-label" for="MaterialBarcode">Codigo do Produto</label>
                  <div class="controls">
                    <span class="add-on">
                      <i class="icon-qrcode"></i>
                    </span>
                    <?php echo $this->Form->input('barcode', array('label' => false, 'div' => false,  'class' => 'input-large', 'required' => false)); ?>
                  </div>
                </div>
              </p>

              <p>
                <div class="row-fluid">
                  <label class="control-label" for="EntradaMateriaisId">Produto</label>
                  <div class="controls">
                    <!-- <span class="add-on">
                      <i class="icon-list"></i>
                    </span> -->
                    <?php echo $this->Form->input('materiais_id', array('label' => false, 'div' => false, 'empty'=>true,  'class' => 'chzn-select', 'data-placeholder' => 'Selecione o produto', 'style'=>'width: 500px;', 'required' => true)); ?>

                    <!-- <select id="EntradaMateriaisId" name="data[Entrada][materiais_id]" class="chzn-select" data-placeholder="Selecione o produto" style="width: 295px;" >
                      <option value='0'></option>

                    // foreach ($materiais1 as $key => $value) {
                    //     $selected = $value['Material']['id'] == $this->request->data['Entrada']['materiais_id'] ? 'selected' : null;
                    //   echo "<option value='{$key}' {$selected}>{$value['Material']['barcode']} - {$value['Material']['nome']}</option>";
                    }

                    </select> -->
                  </div>
                </div>
              </p>

              <p>
                <div class="input-prepend">
                  <label class="control-label" for="EntradaDataEntrada">Data</label>
                  <div class="controls">
                    <span class="add-on">
                      <i class="icon-calendar"></i>
                    </span>
                    <?php echo $this->Form->input('data_entrada', array('label' => false, 'div' => false,  'type'=> 'text',  'class' => 'input-small', 'data-date-format' => 'dd/mm/yyyy', 'required' => true)); ?>
                  </div>
                </div>
              </p>

              <p>
                <div class="input-prepend">
                  <label class="control-label" for="EntradaQuantidadeEstoque">Quantidade Estoque</label>
                  <div class="controls">
                    <span class="add-on">
                      <i class="icon-plus"></i>
                    </span>
                    <?php echo $this->Form->input('quantidade_estoque', array('label' => false,  'div' => false,  'class' => 'input-small', 'readonly'=>true)); ?>
                  </div>
                </div>
              </p>

              <p>
                <div class="input-prepend">
                  <label class="control-label" for="EntradaQuantidadeEntrada">Quantidade Entrada</label>
                  <div class="controls">
                    <span class="add-on">
                      <i class="icon-plus"></i>
                    </span>
                    <?php echo $this->Form->input('quantidade_entrada', array('label' => false,  'div' => false,  'class' => 'input-small', 'required' => true)); ?>
                  </div>
                </div>
              </p>

              <div class="form-actions" style="background-color: #fff;">
                <button class="btn btn-inverse" type="submit">
                  <i class="icon-ok bigger-110"></i> Gravar
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                  <i class="icon-undo bigger-110"></i> Limpar
                </button>
              </div>

              <?php echo $this->Form->end(); ?>
              <?php echo $this->Html->script( array( 'entrada' ) ); ?>
            </div>

            <div class="span2"></div>

						<!-- <div class="span6">
              <table class="table table-striped table-bordered table-hover">
                <thead>
                  <tr>
                    <th><?php //echo $this->Paginator->sort('Material.nome', 'Produto', array('escape' => false)); ?></th>
                    <th><?php //echo $this->Paginator->sort('Entrada.data_entrada', 'Data', array('escape' => false)); ?></th>
                    <th><?php //echo $this->Paginator->sort('Entrada.quantidade_entrada', 'Quantidade', array('escape' => false)); ?></th>
                    <th><?php //echo $this->Paginator->sort('Usuario.nome', 'Usu&aacute;rio', array('escape' => false)); ?></th>
                    <!--<th class="actions"><?php// echo __(''); ?></th>-->
                  <!-- </tr>
                </thead>

                <tbody> -->
                  <?php //foreach ($entradas as $entrada): ?>
                    <!-- <tr>
                      <td><?php //echo h($entrada['Material']['nome']); ?>&nbsp;</td>
                      <td><?php //echo h($entrada['Entrada']['data_entrada']); ?>&nbsp;</td>
                      <td><?php //echo h($entrada['Entrada']['quantidade_entrada']); ?>&nbsp;</td>
                      <td><?php //echo h($entrada['Usuario']['nome']); ?>&nbsp;</td>
                      <!--            <td style="width: 180px; text-align: center;">
                      <?php //echo $this->Html->link(__(' Editar'), array('action' => 'edit', $entrada['Entrada']['id']), array('class' => 'btn btn-mini btn-info icon-edit')); ?>
                    </td>-->
                  <!-- </tr> -->
                <?php //endforeach; ?>

              <!-- </tbody>
              <tfoot>
                <tr>
                  <td colspan="14"> -->
                    <?php //echo $this->element('paginacao'); ?>
                  <!-- </td>
                </tr>
              </tfoot>
            </table>
            </div> -->

					</div>

      </div>
    </div>
  </div>
</div>
</div>

<script type="text/javascript">
    $(function () {
        $('#EntradaDataEntrada').datepicker({
            dateFormat: 'yy-mm-dd',
            language: 'pt-BR',
            minDate: 0,
            maxDate: 'today'
        });

        $(".chzn-select").chosen();

    });
</script>
