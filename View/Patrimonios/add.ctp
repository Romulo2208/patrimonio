<div class="page-header position-relative">
    <h1>
        <?php echo __('Patrim&ocirc;nio'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Adicionar'); ?>        </small>

        <?php echo $this->Html->link(__(' Listar Todos'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
            </h1>
</div>

<?php echo $this->Form->create('Patrimonio', array('class'=>'form-horizontal')); ?>


                <div class="control-group">
                    <label class="control-label" for="PatrimonioFirma">Codigo</label>
                    <div class="controls">
                    <?php echo $this->Form->input('firma', array('label' => false, 'class' => 'input-small', 'required' => true)); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="PatrimonioCodigo">Registro</label>
                    <div class="controls">
                    <?php echo $this->Form->input('codigo', array('label' => false, 'type' => 'text', 'class' => 'input-small' , 'required' => true)); ?>
                    </div>
                </div>

                <div class="control-group">
                    <label class="control-label" for="PatrimonioValor">Valor</label>
                    <div class="controls">
                    <?php echo $this->Form->input('valor', array('label'=>false, 'type' => 'text', 'class' => 'input-medium', 'required' => true)); ?>
                    </div>
                </div>

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

            <hr />
            <div class="row-fluid wizard-actions">
                <button class="btn btn-inverse" type="submit">
                    <i class="icon-ok bigger-110"></i> Gravar
                </button>
                &nbsp; &nbsp; &nbsp;
                <button class="btn" type="reset">
                    <i class="icon-undo bigger-110"></i> Limpar
                </button>
            </div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script( array( 'patrimonio' ) ); ?>
