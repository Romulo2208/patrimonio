$(function () {
    $('#SaidaDataSaida').mask('99/99/9999');

    $("#SaidaMateriaisId").change(function () {
//
// ////        alert('1');
// //        var produto = $('#SaidaMateriaisId').val();
// //        $.post('../../../patrimonio/materiais/pesquisarproduto/' + $('#SaidaMateriaisId').val(),
// //                function (dados) {
// ////                    console.log(dados);
// ////                    alert('2');
// //                    $('#SaidaQuantidadeEstoque').val(dados.Material.quantidade);
// ////                    alert('3');
// //                }, 'json');
//
       window.location.href ="../../../patrimonio/materiais/saida/" + $('#SaidaMateriaisId').val();
});

  $("#SaidaBarcode").change(function () {

      window.location.href ="../../../patrimonio/materiais/saida/" + $('#SaidaBarcode').val();

//        var barcode = $('#SaidaBarcode').val();
//        $.post('../../../patrimonio/materiais/pesquisarbarcode/' + $('#SaidaBarcode').val(),
//                function (dados) {
////                    alert('2');
//                    $('#SaidaQuantidadeEstoque').val(dados.Material.quantidade);
//                    $('#SaidaMateriaisId').val(dados.Material.id);
//
//                }, 'json');
    });

});

