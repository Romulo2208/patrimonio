$(function () {
    $('#PatrimonioDataRegistro').mask('99/99/9999');
    $('#PatrimonioDataBaixa').mask('99/99/9999');
    $("#PatrimonioValor").maskMoney({thousands: '.', decimal: ','});

    $('#duplicar').click(function () {
        //alert("1");
        bootbox.confirm("Duplicar Patrimonio?", function (result) {
            if (result) {
                window.location.href = "/patrimonio/patrimonios/duplicar/" + $('#PatrimonioId').val();
            }
        });
    }
    );

    $('#PatrimonioFirma').change(function () {
        $.get('/patrimonio/patrimonios/getCodigoByFirma/' + $(this).val(),
                function (data) {
                    if (data.length) {
                        var num = parseInt(data[0].codigo);
                        $('#PatrimonioCodigo').val((num ? num : 0) + 1);
                    }
                }, 'json');
    });
});
