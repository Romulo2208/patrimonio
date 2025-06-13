<div class="maincontent">
    <div class="contentinner">
        <div class="mediamgr_head nomargin">
            <ul class="mediamgr_menu">
                <li class="marginleft15 listar"><?php echo $this->Html->link(__('Listar Usuários'), array('action' => 'index'), array('class' => 'btn')); ?></li>
            </ul>
            <span class="clearall"></span>
        </div>
        
        <div class="widgetcontent bordered">
        <?php echo $this->Form->create('Usuario'); ?>
            <?php
		echo $this->Form->input('nome');
		echo $this->Form->input('login', array('type' => 'email'));
		echo $this->Form->input('senha', array('type' => 'password', 'required' => 'required'));
        echo $this->Form->input('status', array('type' => 'hidden', 'value' => '0'));

            ?>
            <button type="submit" class="btn btn-warning"><i class="icon-ok icon-white"></i> Gravar</button>
        <?php echo $this->Form->end(); ?>
        </div>
    </div>
</div>
