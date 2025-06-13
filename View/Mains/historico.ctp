<div class="widget-header">
    <div class="widget-toolbar">
        <b>Exp.</b> #auth.id:7 / log_model:Evento / log_type:delete
    </div>
</div>

<div class="widget-body">
    <table style="width: 100%;">
        <?php
            foreach ($log as $value) {
                $auth = $value['auth'];
                $log_type = $value['log_type'];
                $log_model = $value['log_model'];
                $log_data_hora = $value['log_data_hora'];
                unset($value['_id'],$value['auth'],$value['log_type'],$value['log_model'],$value['log_data_hora']); ?>  
        
                <tr>
                    <td valign="top" style="text-align: right; width: 160px;"> 
                        <?php echo $auth['nome']; ?> <br/>
                        <?php echo "{$log_model} ({$log_type})"; ?> <br/>
                        <?php echo date('Y-m-d H:i:s', $log_data_hora->sec); ?>
                    </td>
                    <td> <?php pr($value); ?> </td>
                </tr>
        <?php } ?>
    </table>   
</div>


