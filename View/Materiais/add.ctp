<div class="widget-box">
  <div class="widget-header">
    <h4>NOVO PRODUTO</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php //echo $this->Form->create('Material',array('type' => 'file')); ?>
      <?php echo $this->Form->create('Material', array('class'=>'form-horizontal')); ?>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialClassificacoesId">Categoria</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <?php echo $this->Form->input('classificacoes_id', array('type' => 'select', 'label' => false, 'div' => false, 'class' => 'input-large', 'required' => true, 'empty' => true, 'options' => $classificacoes)); ?>
          </div>
        </div>
      </p>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialLocalizacoessId">Localiza&ccedil;&atilde;o</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-list"></i>
            </span>
            <?php echo $this->Form->input('localizacoes_id', array('type' => 'select', 'label' => false, 'div' => false, 'class' => 'input-large', 'required' => true, 'empty' => true, 'options' => $localizacoes)); ?>
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
            <?php echo $this->Form->input('barcode', array('label' => false, 'div' => false,  'class' => 'input-large', 'required' => false)); ?>
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
            <?php echo $this->Form->input('nome', array('label' => false, 'div' => false, 'class' => 'input-xxlarge', 'required' => true)); ?>
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
            <?php echo $this->Form->input('prateleira', array('label' => false, 'div' => false, 'class' => 'input-xlarge', 'required' => true)); ?>
          </div>
        </div>
      </p>

      <!-- <label> Imagem</label> -->
      <?php
          // echo $this->Form->file('uploadfile.', array('multiple'));
          //echo $this->Form->end('Enviar');
      ?>



      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialDescricao">Descrição</label>
          <div class="controls">
            <?php echo $this->Form->input('descricao', array('label' => false, 'div' => false, 'class' => 'input-xxlarge', 'required' => false)); ?>
          </div>
        </div>
      </p>

      <p>
        <div class="input-prepend">
          <label class="control-label" for="MaterialQuantidade">Quantidade</label>
          <div class="controls">
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <?php echo $this->Form->input('quantidade', array('label' => false, 'div' => false, 'class' => 'input-small', 'required' => false)); ?>
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
            <?php echo $this->Form->input('est_min', array('label' => false, 'div' => false, 'class' => 'input-small', 'required' => true)); ?>
          </div>
        </div>
      </p>


      <div class="row-fluid wizard-actions">
        <button class="btn btn-inverse" type="submit">
          <i class="icon-ok bigger-110"></i> Gravar
        </button>
      </div>

      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>

<script type="text/javascript">
// $('#MaterialBarcode').blur(function () {
//
//     var barcode = $('#MaterialBarcode').val();
//     //cnpj = cnpj.replace(/[^\d]+/g, '');
//     $.post('../../patrimonio/materiais/consultabarcode/' + barcode,
//             function (data) {
//                 if (data.Material) {
//                     bootbox.confirm("Codigo JÁ EXISTE DESEJA EDITAR ?", function (result) {
//                         if (result) {
//                             window.location.href = "../../../patrimonio/materiais/edit/" + data.Material.id;
//                         }
//                     });
//                 }
//             }, 'json');
// });
</script>
