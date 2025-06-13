<div class="widget-box">
  <div class="widget-header">
    <h4>SALDO ANTERIOR DO OLEO DIESEL S10</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>



  <div class="widget-body">
    <div class="widget-main">
      <?php
      echo $this->Form->create('SaldoAnterior', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('setor_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.setor')));
      echo $this->Form->input('materiais_id', array('type' => 'hidden', 'value' => 1241));
      // echo $this->Form->input('data', array('type' => 'hidden', 'value' => date('Y-m-d')));
      ?>

      <span class="materiais" >
        <div class="row-fluid" style="margin-top: 10px;">

          <div class="input-prepend">
            <label>Saldo Anterior do Diesel S10</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon">
              <input type="text" name="SaldoAnterior[quantidade]" class="input-medium" min="0" onkeypress="maskReal(this);" />
            </span>
          </div>

          <br><br>
            <div class="input-prepend">
              <label>Data</label>
                <span class="add-on">
                  <i class="icon-calendar"></i>
                </span>
                <input type="text" id="data" name="SaldoAnterior[data]" class="input-small" required />
            </div>



        </div>

      <div class="control-group">
        <button type="submit" class="btn btn-info" style=" float: right; margin-top: 15px;"><i class="icon-ok icon-white"></i> Gravar</button>
      </div>
      <?php echo $this->Form->end(); ?>



    </div>
  </div>
</div>

<script type="text/javascript">
    $(function () {
      $('#data').mask('99/99/9999');


    });

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
