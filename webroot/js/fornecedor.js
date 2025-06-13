$(function() {
    $('#FornecedorCep').mask('99.999-999');
    $('#FornecedorCnpj').mask('99.999.999/9999-99');
    $('#FornecedorTelefone').mask('(99)99999999?9');
    $('#FornecedorFax').mask('(99)9999-9999');


    $('#FornecedorCnpj').blur(function () {



       $(this).parent().find('i').remove();
        $(this).parent().append('<i class="icon-ok" ></i>');

        var cnpj = $(this).val();

        if (validarCNPJ(cnpj)) {
        } else {
            //$('#FornecedorCnpj').focus();
            //alert('CNPJ inválido!');
            $(this).parent().find('i').remove();
            $(this).parent().append('<i class="icon-remove" ></i>');

        }

           var cnpj = $('#FornecedorCnpj').val();
        cnpj = cnpj.replace(/[^\d]+/g, '');
        $.post('../../patrimonio/fornecedores/consultacnpj/' + cnpj,
                function (data) {
                    if (data.Fornecedor) {
                        bootbox.confirm("CNPJ JÁ EXISTE DESEJA EDITAR ?", function (result) {
                            if (result) {
                                window.location.href = "../../../patrimonio/fornecedores/edit/" + data.Fornecedor.id;
                            }
                        });
                    }
                }, 'json');
    });

    $("#FornecedorCep").blur(function () {
        
        //$(this).parent().find('i').remove();
        $(validarCep(cep));
        var cep = $('#FornecedorCep').val();
        /*if (cep == (validarCep(cep))) {
            //alert('Informe o CEP antes de continuar');
            //$('#FornecedorCep').focus();
            $(this).parent().find('i').remove();
            $(this).parent().append('<i class="icon-remove" ></i>');
            return false;
        }*/
        //$('#FornecedorEndereco').val('Aguarde...');
        $.post('../../../patrimonio/mains/cep/' + $('#FornecedorCep').val(),
                function (dados) {
                    $('#FornecedorEndereco').val(dados.logradouro);
                    $('#FornecedorBairro').val(dados.bairro);
                    $('#FornecedorCidade').val(dados.localidade);
                    $('#FornecedorUf :selected').text(dados.uf);

                    //$('#btn_consulta').html('Consultar');
                }, 'json');
       /* $(this).parent().find('i').remove();
        $(this).parent().append('<i class="icon-ok" ></i>');*/
    });

});

function validarCep(cep){
    var cep = /^[0-9]{2}\.[0-9]{3}-[0-9]{3}$/;
    //var cep = cep.replace(/\D/g, '');

    if(cep == '') return false;


    if (cep.length != 8   ||
        cep == "00000000" ||
        cep == "11111111" ||
        cep == "22222222" ||
        cep == "33333333" ||
        cep == "44444444" ||
        cep == "55555555" ||
        cep == "66666666" ||
        cep == "77777777" ||
        cep == "88888888" ||
        cep == "99999999")
        //alert('1');
        return false;
}


function validarCNPJ(cnpj) {


    cnpj = cnpj.replace(/[^\d]+/g,'');

    if(cnpj == '') return false;

    if (cnpj.length != 14)
        return false;

    // Elimina CNPJs invalidos conhecidos
    if (cnpj == "00000000000000" ||
        cnpj == "11111111111111" ||
        cnpj == "22222222222222" ||
        cnpj == "33333333333333" ||
        cnpj == "44444444444444" ||
        cnpj == "55555555555555" ||
        cnpj == "66666666666666" ||
        cnpj == "77777777777777" ||
        cnpj == "88888888888888" ||
        cnpj == "99999999999999")
        return false;

    // Valida DVs
    tamanho = cnpj.length - 2;
    numeros = cnpj.substring(0,tamanho);
    digitos = cnpj.substring(tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(0))
        return false;

    tamanho = tamanho + 1;
    numeros = cnpj.substring(0,tamanho);
    soma = 0;
    pos = tamanho - 7;
    for (i = tamanho; i >= 1; i--) {
      soma += numeros.charAt(tamanho - i) * pos--;
      if (pos < 2)
            pos = 9;
    }
    resultado = soma % 11 < 2 ? 0 : 11 - soma % 11;
    if (resultado != digitos.charAt(1))
          return false;

    return true;
}


