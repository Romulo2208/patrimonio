$(function() {
    $("#NotaFiscalValorNota").maskMoney({thousands:'.', decimal:','});
    $('#NotaFiscalDataEmissao').mask('99/99/9999');
    
    $('#add_item').click(function() {
        var associados = $('#select1 option:selected');
        associados.remove().appendTo('#select2');
        $.each(associados, function( index, value ) {
            $.post('/patrimonio/itens/add', {
                'data[Item][nota_fiscal_id]': $('#NotaFiscalId').val(),
                'data[Item][patrimonio_id]': $(value).val()
            },
            function(data) {
                valor_nota();
            });
        });
    });
    
    $('#remove_item').click(function() {
        var associados = $('#select2 option:selected');
        associados.remove().appendTo('#select1');
        $.each(associados, function( index, value ) {
            $.post('/patrimonio/itens/delete', {
                'data[Item][nota_fiscal_id]': $('#NotaFiscalId').val(),
                'data[Item][patrimonio_id]': $(value).val()
            },
            function(data) {
                valor_nota();
            });
        });
    });
    
    $('#abri_patrimonio2').click(function() {
        //alert($('#select2').val());
        if($('#select2').val()){
           window.location.href = "../../patrimonios/edit/" + $('#select2').val();
        }
    });
    
    $('#abri_patrimonio').click(function() {
        //alert($('#select2').val());
        if($('#select1').val()){
           window.location.href = "../../patrimonios/edit/" + $('#select1').val();
        }
    });
    
    function valor_nota() {
        $.post('/patrimonio/itens/valor_nota', {
            'data[Item][nota_fiscal_id]': $('#NotaFiscalId').val()
        },
        function(data) {
            $('#NotaFiscalValorNota').val(data);
        });
    }
});