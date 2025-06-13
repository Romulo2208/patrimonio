<div class="widget-box">
  <div class="widget-header">
    <h4>EDITAR PRODUTO</h4>

    <?php //pr($this->Form->request['data']['Material']['id']);exit; ?>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Material', array('class'=>'form-horizontal'));
      echo $this->Form->input('id', array('required' => true));
      ?>


     <!-- <p>
          <div style="width: 200px; float: right;">
              <?php //echo $this->Html->image('uploads/'.$this->Form->request['data']['Material']['id'].'.png', array('width'=>'150px', 'float'=>'rigth')); ?>
          </div>
      </p> -->

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialClassificacoesId">Classifica&ccedil;&atilde;o</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <?php echo $this->Form->input('classificacoes_id', array('type' => 'select', 'label' => false, 'div' => false, 'class' => 'input-large', 'required' => true, 'empty' => true, 'options' => $classificacoes, 'disabled' => false)); ?>
          </div>
        </div>
      </p>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialBarcode">Codigo do Produto</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-qrcode"></i>
            </span>
            <?php //echo $this->Form->input('barcode', array('label' => false, 'div' => false,  'class' => 'input-large', 'required' => false, 'disabled' => true)); ?>
            <input type="text" name="Material[barcode]" class="input-large" value="<?php echo $itens[0]['Material']['barcode']; ?>" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?> readonly <?php } ?>/>
          </div>
        </div>
      </p>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialNome">Produto</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-pencil"></i>
            </span>
            <?php //echo $this->Form->input('nome', array('label' => false, 'div' => false, 'class' => 'input-xxlarge', 'required' => true, 'disabled' => true)); ?>
            <input type="text" name="Material[nome]" class="input-xxlarge" value="<?php echo $itens[0]['Material']['nome']; ?>" <?php if(in_array($this->Session->read('Perfil.id'), array('2', '3'))) { ?> readonly <?php } ?>/>
          </div>
        </div>
      </p>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialPrateleira">Prateleira</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-pencil"></i>
            </span>
            <?php //echo $this->Form->input('nome', array('label' => false, 'div' => false, 'class' => 'input-xxlarge', 'required' => true, 'disabled' => true)); ?>
            <input type="text" name="Material[prateleira]" class="input-xlarge" value="<?php echo $filiais['MaterialFilial']['prateleira']; ?>"/>
          </div>
        </div>
      </p>

      <?php //if(!in_array($this->Session->read('Perfil.id'), array('2', '3','12'))) { ?>
      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialDescricao">Descrição</label>
          <div class="controls">
            <?php echo $this->Form->input('descricao', array('label' => false, 'div' => false, 'class' => 'input-xxlarge', 'required' => false, 'value' => $itens[0]['Material']['descricao'])); ?>
          </div>
        </div>
      </p>
      <?php //} ?>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialQuantidade">Quantidade</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <?php echo $this->Form->input('quantidade', array('label' => false, 'div' => false, 'class' => 'input-small', 'required' => true, 'value' => $filiais['MaterialFilial']['quantidade'])); ?>
          </div>
        </div>
      </p>

      <br>
      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialEstMin">Estoque Minimo</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <?php echo $this->Form->input('est_min', array('label' => false, 'div' => false, 'class' => 'input-small', 'required' => true, 'value' => $filiais['MaterialFilial']['est_min'])); ?>
          </div>
        </div>
      </p>

      <!-- <p>
        <div class="input-prepend">
          <div class="controls">
            <?php echo $this->Html->image('uploads/'.$this->Form->request['data']['Material']['id'].'.png', array('width'=>'100px')); ?>
          </div>
        </div>
      </p> -->



      <div class="row-fluid wizard-actions">
        <button class="btn btn-inverse" type="submit">
          <i class="icon-ok bigger-110"></i> Gravar
        </button>
      </div>

      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>
