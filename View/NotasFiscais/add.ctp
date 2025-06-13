<div class="page-header position-relative">
    <h1>
        <?php echo __('Nota Fiscal'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Adicionar'); ?>        </small>
        
        <?php echo $this->Html->link(__(' Listar Todas'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
            </h1>
</div>
    
<?php echo $this->Form->create('NotaFiscal'); ?>
<div class="span10">
    <div class="widget-box">
        <div class="widget-header">
            <h4>Dados</h4>

            <span class="widget-toolbar">
                <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
        </div>

        <div class="widget-body">
            <table>
                <tr>
                    <td style="width: 200px;"></td>
                    <td style="width: 200px;"></td>
                </tr>
                <tr>
                    <td><?php echo $this->Form->input('numero_nota', array('label' => 'N&uacute;mero da Nota', 'type' => 'number', 'class' => 'input-small', 'required' => true)); ?></td>
                    <td><?php echo $this->Form->input('data_emissao', array('label' => 'Data de Emiss&atilde;o', 'type' => 'text', 'class' => 'input-small', 'required' => true)); ?></td>
                </tr>
            </table>

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
        </div>
    </div>
</div>

<?php echo $this->Form->end(); ?>
<?php echo $this->Html->script( array( 'nota_fiscal' ) ); ?>
    
