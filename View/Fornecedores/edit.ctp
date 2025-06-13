<div class="page-header position-relative">
    <h1>
        <?php echo __('Fornecedor'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Editar'); ?>        </small>

        <?php echo $this->Html->link(__(' Listar Todos'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
            </h1>
</div>

<?php echo $this->Form->create('Fornecedor', array('class'=>'form-horizontal')); ?>
<?php echo $this->Form->input('id', array('required' => true)); ?>


            <div class="control-group">
                <label class="control-label" for="FornecedorCnpj4">CNPJ</label>
                <div class="controls">
                   <?php echo $this->Form->input('cnpj', array('label' => false,  'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorNomeFantasia">Nome Fantasia</label>
                <div class="controls">
                <?php echo $this->Form->input('nome_fantasia', array('label'=>false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorInscricaoEstadual">Inscrição Estadual</label>
                <div class="controls">
                <?php echo $this->Form->input('inscricao_estadual', array('label' => false, 'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorRazaoSocial">Razão Social</label>
                <div class="controls">
                        <?php echo $this->Form->input('razao_social', array('label' => false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorBanco">Banco</label>
                <div class="controls">
                        <?php echo $this->Form->input('banco', array('label' => false, 'class' => 'input-xlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorAgencia">Agencia</label>
                <div class="controls">
                        <?php echo $this->Form->input('agencia', array('label' => false, 'class' => 'input-small', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorConta">Conta</label>
                <div class="controls">
                        <?php echo $this->Form->input('conta', array('label' => false, 'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>    

            <div class="control-group">
                <label class="control-label" for="FornecedorTelefone">Telefone</label>
                <div class="controls">
                <?php echo $this->Form->input('telefone', array('label'=>false, 'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorFax">Fax</label>
                <div class="controls">
                <?php echo $this->Form->input('fax', array('label'=>false, 'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorEmail">Email</label>
                <div class="controls">
                <?php echo $this->Form->input('email', array('label'=>false, 'class' => 'input-xlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorCep">Cep</label>
                <div class="controls">
                <?php echo $this->Form->input('cep', array('label'=>false, 'class' => 'input-medium', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorEndereco">Endereço</label>
                <div class="controls">
                <?php echo $this->Form->input('endereco', array('label' => false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorBairro">Bairro</label>
                <div class="controls">
                <?php echo $this->Form->input('bairro', array('label'=>false, 'class' => 'input-xlarge', 'required' => true)); ?>
                </div>
            </div>


            <div class="control-group">
                <label class="control-label" for="FornecedorCidade">Cidade</label>
                <div class="controls">
                <?php echo $this->Form->input('cidade', array('label'=>false, 'class' => 'input-xxlarge', 'required' => true)); ?>
                </div>
            </div>

            <div class="control-group">
                <label class="control-label" for="FornecedorUf">UF</label>
                <div class="controls">
                <?php echo $this->Form->input('uf', array('label' => false, 'class' => 'input-mini', 'required' => true)); ?>
                </div>
            </div>

            <hr />
            <div class="row-fluid wizard-actions">
                <button class="btn btn-inverse" type="submit">
                    <i class="icon-ok bigger-110"></i> Gravar
                </button>
            </div>


<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script( array( 'fornecedor' ) ); ?>
