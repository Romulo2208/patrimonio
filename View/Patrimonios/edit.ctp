<?php ?>

<div class="page-header position-relative">
    <h1>
        <?php echo __('Patrim&ocirc;nio'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Editar'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Listar Todos'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
    </h1>
</div>

<?php 
    echo $this->Form->create('Patrimonio', array('class' => 'form-horizontal'));
    echo $this->Form->input('id', array('required' => true));
?>

<?php if(($this->Form->value('Patrimonio.data_baixa')!='00/00/0000') && ($this->Form->value('Patrimonio.data_baixa')!=null)){?>
<div class="alert alert-info">
    <button class="close" type="button" data-dismiss="alert">
        <i class="icon-remove"></i>
    </button>
          <?php echo 'Patrimonio foi dado baixa no dia:'.  $this->Form->value('Patrimonio.data_baixa');?>
</div>
<?php }?>
    
            <div class="control-group">
                    <label class="control-label" for="PatrimonioFirma">Codigo</label> 
                    <div class="controls">
                        <span class="input-icon">
                           <?php echo $this->Form->input('firma', array('label' => false, 'class' => 'input-small', 'required' => true)); ?> 
                        </span>
                        <span class="input-icon" style="float:right;">
                           <?php if(isset($notafiscal['NotaFiscal']['id'])) { ?>
                            <a href="../../notas_fiscais/edit/<?php echo $notafiscal['NotaFiscal']['id']; ?>" class="btn btn-inverse btn-small"><i class="icon-folder-open-alt bigger-110"></i> Nota Fiscal</a>
                        <?php } ?>
                        </span>
                    </div>
            </div>
                
                <div class="control-group">
                    <label class="control-label" for="PatrimonioCodigo">Registro</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('codigo', array('label' => false, 'type' => 'text', 'class' => 'input-small', 'required' => true)); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="PatrimonioValor">Valor</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('valor', array('label'=>false, 'type' => 'text', 'class' => 'input-medium', 'required' => true)); ?>
                    </div>
                </div>
                    
<!--                    <td style="text-align: right;">
                        <?php if($notafiscal['NotaFiscal']['id']) { ?>
                            <a href="../../notas_fiscais/edit/<?php echo $notafiscal['NotaFiscal']['id']; ?>" class="btn btn-inverse"><i class="icon-folder-open-alt bigger-110"></i> Nota Fiscal</a>
                        <?php } ?>
                    </td>-->
               
                
                <div class="control-group">
                    <label class="control-label" for="PatrimonioDescricao">Descrição</label> 
                    <div class="controls">
                   <?php echo $this->Form->input('descricao', array('label' => false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                    </div>
                </div>    
               
                <div class="control-group">
                    <label class="control-label" for="PatrimonioLocalizacaoId">Localização</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('localizacao_id', array('label' => false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="PatrimonioConservacaoId">Conservação</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('conservacao_id', array('label' => false, 'class' => 'input-medium', 'required' => true)); ?>
                    </div>
                </div>
                
                <div class="control-group">
                    <label class="control-label" for="PatrimonioFornecedorId">Fornecedor</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('fornecedor_id', array( 'label'=>false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                    </div>
                </div>
            
                 <div class="control-group">
                    <label class="control-label" for="PatrimonioDataRegistro">Data Registro</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('data_registro', array('label'=>false, 'type' => 'text', 'class' => 'input-small', 'required' => true)); ?>
                    </div>
                 </div>
            
                <div class="control-group">
                    <label class="control-label" for="PatrimonioDataBaixa">Data Baixa</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('data_baixa', array('label'=>false, 'type' => 'text', 'class' => 'input-small')); ?>
                    </div>
                </div>
            
                <div class="control-group">
                    <label class="control-label" for="PatrimonioMotivoBaixa">Motivo Baixa</label> 
                    <div class="controls">
                    <?php echo $this->Form->input('motivo_baixa', array('label'=>false, 'class' => 'input-xxlarge')); ?>
                    </div>
                </div>
               
            <hr/>
            <div class="row-fluid wizard-actions">
                <button class="btn btn-inverse" type="submit">
                    <i class="icon-ok bigger-110"></i> Gravar
                </button>
                <button class="btn btn-inverse" type="button" id="duplicar">
                        <i class="icon-edit bigger-110"></i> Duplicar
                </button>
<!--               <button class="btn btn-inverse" type="submit">
                    <i class="icon-ok bigger-110"></i> Duplicar
                </button>-->
                
            </div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script( array( 'patrimonio' ) ); ?>
