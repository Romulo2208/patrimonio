<div class="page-header position-relative">
    <h1>
        <?php echo __('Nota Fiscal'); ?>        <small>
            <i class="icon-double-angle-right"></i>
            <?php echo __('Anexo'); ?>        </small>

        <?php echo $this->Html->link(__(' Listar Todas'), array('action' => 'index'), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
        <?php echo $this->Html->link(__(' Voltar'), array('action' => 'edit', $this->Form->value('NotaFiscal.id')), array('class' => 'btn btn-small btn-default icon-book bigger-110')); ?>
    </h1>
</div>

<style type="text/css">
    /*    .widget-body img {
            border: 1px solid #E8E8E8;
            cursor: pointer;
            padding: 5px;
            margin: 6px;
        }*/
</style>

<script type="text/javascript">
    $(function() {
        $('#file').ace_file_input({
            no_file: 'Nenhum arquivo ...',
            btn_choose: 'Escolher',
            btn_change: 'Trocar',
            droppable: false,
            onchange: null,
            thumbnail: false //| true | large
                    //whitelist:'gif|png|jpg|jpeg'
                    //blacklist:'exe|php'
                    //onchange:''
                    //
        });
    });
</script>

<div class="span7">
    <div class="widget-box">
        <div class="widget-header">
            <h4>Documentos</h4>
            <span class="widget-toolbar">
                <a href="#" data-action="collapse"> <i class="icon-chevron-up"></i> </a>
            </span>
        </div>

        <div class="widget-body" style="float: left; width: 508px;">
            <form action="" method="post" enctype="multipart/form-data">
                <input type="submit" value="Gravar" class="btn btn-small"/>
                <div style="width: 400px; float: left; margin: 1px 15px 0 0;">
                    <input type="file" id="file" name="filedata" />
                </div>
            </form>
            <hr />
	<ul>
                <?php
                    if (sizeof($imagens)):
                        foreach ($imagens as $obj):
                            echo "<li> <a href='../view/{$obj->file['_id']}'> [download] </a> {$obj->getFilename()} - ";
                            echo "<a href='../excluir/{$this->Form->value('NotaFiscal.id')}/{$obj->file['_id']}'>";
                            echo "<i class='icon-remove red'></i>";
                            echo "</a> </li>";
                        endforeach;
                    else:
                        echo "<center>Nenhum arquivo encontrado</center>";
                    endif;
                ?>
            </ul>
        </div>
    </div>
</div>

