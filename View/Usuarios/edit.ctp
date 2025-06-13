<div class="maincontent">
    <div class="contentinner">
        <div class="mediamgr_head nomargin">
            <ul class="mediamgr_menu">
                <li class="marginleft15 deletar"><?php echo $this->Form->postLink(__('Deletar'), array('action' => 'delete', $this->Form->value('Usuario.id')), array('class' => 'btn'), __('Deseja deletar %s?', $this->Form->value('Usuario.nome'))); ?></li>
                <li class="marginleft15 listar"><?php echo $this->Html->link(__('Listar Usuários'), array('action' => 'index'), array('class' => 'btn')); ?></li>
            </ul>
            <span class="clearall"></span>
        </div>

        <div class="widgetcontent bordered">
        <?php echo $this->Form->create('Usuario'); ?>
        <div class="control-group">
            <label class="control-label" for="form-field-1">Selecione um setor</label>
            <div class="controls">
                <?php echo $this->Form->input('setor', array('label' => false, 'type' => 'select', 'empty' => true, 'options' => $setores, 'required' => false, 'class' => 'input-large')); ?>
            </div>
        </div>

        <div class="input-prepend">
            <label>Situa&ccedil;&atilde;o</label>
            <!-- <span class="add-on">
              <i class="icon-exchange"></i>
            </span> -->
            <?php echo $this->Form->input('status', array('label' => false, 'div' => false, 'type' => 'select', 'options' => array('1'=>'Ativo','0'=>'Inativo'), 'required' => true, 'class' => 'input-medium')); ?>
        </div>

            <?php
                echo $this->Form->input('id', array('type' => 'hidden'));
                echo $this->Form->input('perfil', array('empty' => '(nenhum)', 'required' => 'required'));
                // echo $this->Form->input('setor', array('empty' => '(nenhum)', 'required' => 'required'));
		echo $this->Form->input('nome');
		echo $this->Form->input('login', array('type' => 'email'));
                echo $this->Form->input('senha', array('value' => ''));
                ?>
            <button type="submit" class="btn btn-warning"><i class="icon-ok icon-white"></i> Alterar</button>
        <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
