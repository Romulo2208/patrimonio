<div class="widget-box">
  <div class="widget-header">
    <h4>IMPORTAR XML</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>

  <div class="widget-body">
    <div class="widget-main">
      <?php echo $this->Form->create('Material', array('class'=>'form-horizontal', 'enctype' => 'multipart/form-data')); ?>

      <p>
        <div class="input-prepend">
          <!-- <label class="control-label" for="MaterialBarcode">Codigo de Barras</label> -->
          <div class="">
            <!-- <span class="add-on">
              <i class="icon-qrcode"></i>
            </span> -->

            <input type="file" name="data[xml]" accept="text/xml" />
          </div>
        </div>
      </p>

      <div class="row-fluid wizard-actions">
        <button class="btn btn-inverse" type="submit">
          <i class="icon-cloud-upload bigger-110"></i> Importar
        </button>
      </div>

      <?php echo $this->Form->end(); ?>

    </div>
  </div>
</div>
