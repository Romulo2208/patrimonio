<div class="maincontent">
    <div class="contentinner content-dashboard">
        <div class="mediamgr_head nomargin">
            <ul class="mediamgr_menu">
                <li class="marginleft15 adicionar">
                    <?php echo $this->Html->link(__('Novo Usuário'), array('action' => 'add'), array('class' => 'btn')); ?>
                </li>
                <li class="right marginleft15">
                    <a class="btn"><span class="icon-search"></span></a>
                </li>
                <li class="right">
                    <form>
                        <input type="text" id="filekeyword" class="filekeyword" placeholder="Pesquisar" />
                    </form>
                </li>

            </ul>
            <span class="clearall"></span>
        </div>

        <div class="widgetcontent">
            <div class="ui-tabs" style="border-top: 0;">
                <table class="table table-bordered" id="dyntable">
                    <thead>
                    <th><?php echo $this->Paginator->sort('nome'); ?></th>
                    <th><?php echo $this->Paginator->sort('login'); ?></th>
                    <th style="width: 50px;"><?php echo $this->Paginator->sort('status'); ?></th>
                    <th style="width: 200px;"></th>
                    </thead>
                    <tbody>
                        <?php foreach ($usuarios as $usuario): ?>
                            <tr>
                                <td><?php echo h($usuario['Usuario']['nome']); ?>&nbsp;</td>
                                <td><?php echo h($usuario['Usuario']['login']); ?>&nbsp;</td>
                                <td style="text-align: center;"><span class="<?php echo (h($usuario['Usuario']['status']) == 1 ? "icon-star" : "icon-star-empty"); ?>"></span></td>
                                <td class="actions" style="width: 200px; text-align: center;">
                                    <?php echo $this->Html->link(__('Editar'), array('action' => 'edit', $usuario['Usuario']['id']), array('class' => 'btn editar')); ?>
                                    <?php echo $this->Form->postLink(__('Deletar'), array('action' => 'delete', $usuario['Usuario']['id']), array('class' => 'btn deletar'), __('Deseja deletar %s?', $usuario['Usuario']['nome'])); ?>
                                </td>
                            </tr>
                        <?php endforeach; ?>
                    </tbody>
                    <tfoot>
                        <tr>
                            <td colspan="5">
                                <?php echo $this->element('paginacao'); ?>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>
        </div>
    </div>
</div>