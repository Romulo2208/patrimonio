$(function () {
    $('#EntradaDataEntrada').mask('99/99/9999');

    $("#EntradaMateriaisId").change(function () {
//        alert('1');
        window.location.href ="../../../patrimonio/materiais/entrada/" + $('#EntradaMateriaisId').val();
});

$("#EntradaBarcode").change(function () {
//        alert('1');
        window.location.href ="../../../patrimonio/materiais/entrada/" + $('#EntradaBarcode').val();
});

//    $("#EntradaBarcode").blur(function () {
////        alert('1');
//        var barcode = $('#EntradaBarcode').val();
//        $.post('../../../patrimonio/materiais/pesquisarbarcode/' + $('#EntradaBarcode').val(),
//                function (dados) {
////                    alert('2');
//                    $('#EntradaQuantidadeEstoque').val(dados.Material.quantidade);
//                    $('#EntradaMateriaisId').val(dados.Material.id);
//
//                }, 'json');
//    });

});
