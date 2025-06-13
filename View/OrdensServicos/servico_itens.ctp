<div class="widget-box">
  <div class="widget-header">
    <h4>DESCRIMINA&Ccedil;&Atilde;O DOS SERVI&Ccedil;O</h4>

    <span class="widget-toolbar">
      <a href="#" data-action="close" onclick="javascript: location.reload();">
        <i class="icon-remove"></i>
      </a>
    </span>
  </div>



  <div class="widget-body">
    <div class="widget-main">
      <?php
      echo $this->Form->create('ServicoItem', array('class' => 'form-horizontal'));
      echo $this->Form->input('usuarios_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.id')));
      echo $this->Form->input('setores_id', array('type' => 'hidden', 'value' => $this->Session->read('Auth.User.setor')));
      //echo $this->Form->input('situacao', array('type' => 'hidden', 'value' => 1));
      //echo $this->Form->input('id', array('type' => 'hidden'));
      //echo $this->Form->input('ordens_servicos_id', array('type' => 'hidden'));
      // echo $this->Form->input('data_hora_registro', array('type' => 'hidden', 'value' => date('Y-m-d H:i:s')));
      $count = 0;
      sort($servicos);
      ?>

      <span class="materiais" >
        <?php foreach ($servicos as $id => $servico) { ?>
        <div class="row-fluid" style="margin-top: 10px;">
          <div class="input-prepend">
            <label>Descrimina&ccedil;&atilde;o dos Servi&ccedil;o</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon">
              <input type="text" name="servico[descricao][]" class="input-xlarge" min="0" value="<?php echo $servico['ServicoItem']['descricao']; ?>"/>
            </span>
          </div>

          <div class="input-prepend">
            <label>Pre&ccedil;o</label>
            <span class="add-on">
              <i class="icon-plus"></i>
            </span>
            <span class="input-icon">
              <input type="text" name="servico[preco][]" class="input-small" min="0" onkeypress="maskReal(this);" value="<?php echo $servico['ServicoItem']['precos']; ?>"/>
            </span>
          </div>

          <div class="input-prepend">
            <label> &nbsp; </label>
            <span class="input-icon input-icon-right">
              <?php if (sizeof($servicos) == ++$count) { ?>
                <button type="button" onclick="addMaterial(this);" class="green"><i class="icon-plus"></i></button>
              <?php } else { ?>
                <button type="button" onclick="removeMaterial(this);" class="red"><i class="icon-trash"></i></button>
              <?php } ?>
              <!-- <input type="hidden" name="material[item][]" value="<?php //echo $item['ItemPedido']['id']; ?>" /> -->
            </span>
          </div>
        </div>
        <input type="hidden" name="ServicoItem[id]" class="input-small" min="0" value="<?php echo $servico['ServicoItem']['id']; ?>"/>
        <input type="hidden" name="ServicoItem[ordens_servicos_id]" class="input-small" min="0" value="<?php echo $servico['ServicoItem']['ordens_servicos_id']; ?>"/>
        <?php } ?>
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
  $('#OrdemServicoData').datepicker({
    dateFormat: 'yy-mm-dd',
    language: 'pt-BR',
    minDate: 0,
    maxDate: 'today'
  });

});

function getDescricao(e) {
  $(e).parent().find('.descricao').val($(e).find('option:selected').text());
}

function addMaterial(e) {

  var field = $(e).parent().parent().parent();
  $("span.materiais").append(globalField);

  var clone = $("span.materiais .row-fluid:last");
  //var clone = $($(field)).clone().appendTo($(field).parent());

  $(clone).find('select, input').val('');

  $(field).find('button').html('<i class="icon-trash"></i>').removeClass('green').addClass('red').attr('onclick','removeMaterial(this);');
  $(clone).find('small.count').text((parseInt($(field).find('small.count').text())+1));

}

function removeMaterial(e) {
  $(e).parent().parent().parent().remove();
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

$(function () {

  globalField = "<div class=\"row-fluid\" style=\"margin-top: 5px;\">" + $("span.materiais .row-fluid:last").html() + "</div>";

});


$(".chzn-select").chosen({width: "500px"});

</script>
