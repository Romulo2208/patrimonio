$(function () {
    $('#registro').mask('99/99/9999');

    $('#gera_relatorio').click(function () {
        var relatorio = $('#localizacao_id option:selected');
        window.location.href = "../../patrimonio/relatorios/relatorio/localizacao?localizacao_id=" + $('#localizacao_id').val();
        //alert('relatorio');
    });

    $('#registro').blur(function () {
        window.location.href = "../../patrimonio/relatorios/relatorio/data?data_registro=" + $('#registro').val();
    })
});
    