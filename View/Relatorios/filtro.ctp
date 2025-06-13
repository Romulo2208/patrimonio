<?php //echo $this->Form->create('Relatorio', array('class'=>'form-horizontal')); ?>

<!--<select name="data[tipo_relatorio]" id="1">
    <option value=""/>
    <option value="geral" />Geral
    <option value="baixa" />Baixa
</select>-->

<div class="control-group">
    <label class="control-label" for="RelatorioLocalizacaoId">Localização</label> 
    <div class="controls">
    <?php echo $this->Form->input('localizacao_id', array('label' => false, 'class' => 'input-xlarge', 'empty'=> true)); ?>
    </div>  
</div>
<input type="button" name="gera_relatorio" id="gera_relatorio" value="Gerar Relatorio"/>
<br> <br> <br>
<label> Data Inicial:
<input type="text" name="registro" id="registro" placeholder="00/00/0000" class="input-small"/>
</label>

<label> Data Final:
<input type="text" name="data_final" id="data_final" placeholder="00/00/0000" class="input-small"/>
</label>
<br>
<input type="button" name="relatorio" id="relatorio" value="Relatorio"/>

<?php //echo $this->Form->end(); ?>
<?php echo $this->Html->script( array( 'filtro' ));?>