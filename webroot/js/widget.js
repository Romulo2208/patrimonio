$(function() {
    $('#dataAdmissao').mask('99/99/9999');
    $('#dataAposentadoria').mask('99/99/9999');
    $('#dataUltimoPagamento').mask('99/99/9999');

    /*
     * Contatos
     */
    carregar_contatos();
    $('#contatoTipo').change(function() {
        if ($('#contatoTipo').val() !== '0') {
            $('#contatoDescricao').attr('disabled', false);
            switch ($(this).find("option:selected").attr("title")) {
                case 'fone':
                case 'celular':
                    $('#contatoDescricao').val('');
                    $('#contatoDescricao').attr('type', 'tel');
                    $('#contatoDescricao').focusout(function() {
                        var phone, element;
                        element = $(this);
                        element.unmask();
                        phone = element.val().replace(/\D/g, '');
                        if (phone.length > 10) {
                            element.mask("(99) 99999-999?9");
                        } else {
                            element.mask("(99) 9999-9999?9");
                        }
                    }).trigger('focusout');
                    break;
                case 'email':
                    $('#contatoDescricao').attr('type', 'email');
                    $('#contatoDescricao').off('focusout');
                    $('#contatoDescricao').unmask();
                    $('#contatoDescricao').val('');
                    break;
            }
        } else {
            $('#contatoDescricao').attr('disabled', true);
        }
    });

    $('#btn-contato').click(function() {
        if ($('#contatoTipo').val() !== '0' && $('#contatoDescricao').val()) {
            $.post('/cadastro/contatos/add', {
                'data[Contato][tipo_contatos_id]': $('#contatoTipo').val(),
                'data[Contato][associados_id]': $('#AssociadoId').val(),
                'data[Contato][descricao]': $('#contatoDescricao').val()
            },
            function(data) {
                $('#contatoDescricao').val('');
                $('#contatoTipo').val('0');
                bootbox.alert(data);
                carregar_contatos();
            });
        } else {
            bootbox.alert('Preencha os campos com (*)!');
        }
    });

    /*
     * Disciplinas
     */

    carregar_disciplinas();
    $('#btn-disciplina').click(function() {
        if ($('#disciplina').val() !== '0') {
            $.post('/cadastro/disciplinas/addRelAssociado', {
                'data[Disciplina][associados_id]': $('#AssociadoId').val(),
                'data[Disciplina][disciplinas_id]': $('#disciplina').val(),
                'data[Disciplina][atuando]': $("input[id='atuando']:checked").val()
            },
            function(data) {
                $('#disciplina').val('0');
                carregar_disciplinas();
                bootbox.alert(data);
            });
        } else {
            bootbox.alert('Selecione uma disciplina!');
        }
    });

    /*
     * Carteirinhas
     */

    carregar_carteirinhas();
    $('#btn-carteirinha').click(function() {
        if ($('#local').val() !== '0' && $("input[id='pedido']:checked").val()) {
            $.post('/cadastro/carteirinhas/add', {
                'data[Carteirinha][associados_id]': $('#AssociadoId').val(),
                'data[Carteirinha][local]': $('#local').val(),
                'data[Carteirinha][pedido]': $("input[id='pedido']:checked").val()
            },
            function(data) {
                $('#local').val('0');
                bootbox.alert(data);
                carregar_carteirinhas();
            });
        } else {
            bootbox.alert('Preencha os campos com (*)!');
        }
    });

    /*
     * Escolas
     */

    carregar_escolas();
    $('#regional').change(function() {
        $.post('/cadastro/escolas/listarEscolas/' + $(this).val(), {},
                function(data) {
                    $('#escola').html('<option value="0">* Selecione uma Escola</option>');
                    if (data.length) {
                        $.each(data, function(i, item) {
                            $('#escola').append('<option value="' + item.Escola.id + '">' + item.Escola.escola + '</option>');
                        });
                    }
                }, 'json');
    });

    $('#btn-escola').click(function() {
        if ($('#regional').val() !== '0' && $('#escola').val() !== '0' && $('#turno').val() !== '0') {
            $.post('/cadastro/escolas/addRelAssociado', {
                'data[Escola][associados_id]': $('#AssociadoId').val(),
                'data[Escola][escolas_id]': $('#escola').val(),
                'data[Escola][turnos_id]': $("#turno").val()
            },
            function(data) {
                $('#regional').val('0');
                $('#escola').val('0');
                $('#turno').val('0');
                bootbox.alert(data);
                carregar_escolas();
            });
        } else {
            bootbox.alert('Preencha os campos com (*)!');
        }
    });

    /*
     * Secretarias
     */

    $("#autonomo").click(function() {
        if ($(this).is(':checked')) {
            $("#matSedf").val('autonomo').attr('readonly', true);
        } else {
            $("#matSedf").val('').attr('readonly', false);
        }
    });

    carregar_secretarias();
    $('#btn-secretaria').click(function() {
        if ($("#matSedf").val() && $("#dataAdmissao").val() && $("#funcao").val() !== '0' && $("#plano").val() !== '0') {
            $.post('/cadastro/secretarias/add', {
                'data[Secretaria][associados_id]': $('#AssociadoId').val(),
                'data[Secretaria][funcoes_id]': $('#funcao').val(),
                'data[Secretaria][planos_id]': $("#plano").val(),
                'data[Secretaria][matsedf]': $("#matSedf").val(),
                'data[Secretaria][data_admissao]': $("#dataAdmissao").val(),
                'data[Secretaria][data_aposentadoria]': $("#dataAposentadoria").val(),
                'data[Secretaria][aposentado]': $("input[id='aposentado']:checked").val(),
                'data[Secretaria][autonomo]': $("input[id='autonomo']:checked").val(),
                'data[Secretaria][desconto]': $("input[id='desconto']:checked").val()
            },
            function(data) {
                $('#plano').val('0');
                $('#funcao').val('0');
                $('#dataAdmissao').val('');
                $('#dataAposentadoria').val('');
                $("#matSedf").val('').attr('readonly', false);
                $("input[id='aposentado']:checked").attr('checked', false);
                $("input[id='autonomo']:checked").attr('checked', false);
                $("input[id='desconto']:checked").attr('checked', false);
                carregar_secretarias();
                console.log(data);
            });
        } else {
            bootbox.alert('Preencha os campos com (*)!');
        }
    });
});

function carregar_contatos() {
    $.post('/cadastro/contatos/listar', {
        'data[Contato][associados_id]': $('#AssociadoId').val()
    }, function(data) {
        $('#tb-contatos').html('');
        if (data.length) {
            $.each(data, function(i, item) {
                var row = '<tr>';
                row += '<td>' + item.tipo_contato + '</td>';
                row += '<td>' + item.descricao + '</td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:deletar_contato(' + item.id + ');">X</a></td>';
                row += '</tr>';
                $('#tb-contatos').append(row);
            });
        } else {
            $('#tb-contatos').append('<tr><td colspan="3"><center>Nenhum</center></td></tr>');
        }
    }, 'json');
}

function deletar_contato(id) {
    bootbox.confirm("Deletar registro?", function(result) {
        if (result) {
            $.post('/cadastro/contatos/delete/' + id, {
                'data[Contato][id]': id
            }, function(data) {
                bootbox.alert(data);
                carregar_contatos();
            });
        }
    });
}

function carregar_disciplinas() {
    $.post('/cadastro/disciplinas/listar', {
        'data[Disciplina][associados_id]': $('#AssociadoId').val()
    }, function(data) {
        $('#tb-disciplinas').html('');
        if (data.length) {
            $.each(data, function(i, item) {
                var row = '<tr>';
                row += '<td>' + item.disciplina + '</td>';
                row += '<td style="width: 50px;" >' + (item.atuando ? 'SIM' : 'N&Atilde;O') + '</td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:deletar_diciplina(' + item.id + ');">X</a></td>';
                row += '</tr>';
                $('#tb-disciplinas').append(row);
            });
        } else {
            $('#tb-disciplinas').append('<tr><td colspan="3"><center>Nenhum</center></td></tr>');
        }
    }, 'json');
}

function deletar_diciplina(id) {
    bootbox.confirm("Deletar registro?", function(result) {
        if (result) {
            $('#tb-disciplinas').html('');
            $.post('/cadastro/disciplinas/deleteRelAssociado/' + id, {
                'data[Disciplina][id]': id
            }, function(data) {
                bootbox.alert(data);
                carregar_disciplinas();
            });
        }
    });
}

function carregar_carteirinhas() {
    $.post('/cadastro/carteirinhas/listar', {
        'data[Carteirinha][associados_id]': $('#AssociadoId').val()
    }, function(data) {
        $('#tb-carteirinhas').html('');
        if (data.length) {
            $.each(data, function(i, item) {
                var row = '<tr>';
                row += '<td>' + (item.data_emissao ? item.data_emissao : 'AGUARDANDO') + '</td>';
                row += '<td>' + item.local + '</td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:deletar_carteirinha(' + item.id + ');">X</a></td>';
                row += '</tr>';
                $('#tb-carteirinhas').append(row);
            });
        } else {
            $('#tb-carteirinhas').append('<tr><td colspan="3"><center>Nenhum</center></td></tr>');
        }
    }, 'json');
}

function deletar_carteirinha(id) {
    bootbox.confirm("Deletar registro?", function(result) {
        if (result) {
            $.post('/cadastro/carteirinhas/delete/' + id, {
                'data[Carteirinha][id]': id
            }, function(data) {
                bootbox.alert(data);
                carregar_carteirinhas();
            });
        }
    });
}

function carregar_escolas() {
    $.post('/cadastro/escolas/listar', {
        'data[Escola][associados_id]': $('#AssociadoId').val()
    }, function(data) {
        $('#tb-escolas').html('');
        if (data.length) {
            $.each(data, function(i, item) {
                var row = '<tr>';
                row += '<td>' + item.escola + '</td>';
                row += '<td>' + item.turno + '</td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:deletar_escola(' + item.id + ');">X</a></td>';
                row += '</tr>';
                $('#tb-escolas').append(row);
            });
        } else {
            $('#tb-escolas').append('<tr><td colspan="3"><center>Nenhum</center></td></tr>');
        }
    }, 'json');
}

function deletar_escola(id) {
    bootbox.confirm("Deletar registro?", function(result) {
        if (result) {
            $.post('/cadastro/escolas/deleteRelAssociado/' + id, {
                'data[Escola][id]': id
            }, function(data) {
                bootbox.alert(data);
                carregar_escolas();
            });
        }
    });
}

function carregar_secretarias() {
    $.post('/cadastro/secretarias/listar', {
        'data[Secretaria][associados_id]': $('#AssociadoId').val()
    }, function(data) {
        $('#tb-secretarias').html('');
        if (data.length) {
            $.each(data, function(i, item) {
                var row = '<tr>';
                row += '<td>' + item.matsedf + '</td>';
                row += '<td style="width: 50px;" >' + (item.desconto ? 'SIM' : 'N&Atilde;O') + '</td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:mais(' + item.id + ');">Mais</a></td>';
                row += '<td style="width: 10px;" ><a href="javascript:return false;" onclick="javascript:deletar_secretaria(' + item.id + ');">X</a></td>';

                row += '</tr>';
                $('#tb-secretarias').append(row);
            });
        } else {
            $('#tb-secretarias').append('<tr><td colspan="3"><center>Nenhum</center></td></tr>');
        }
    }, 'json');
}

function deletar_secretaria(id) {
    bootbox.confirm("Deletar registro?", function(result) {
        if (result) {
            $.post('/cadastro/secretarias/delete/' + id, {
                'data[Secretaria][id]': id
            }, function(data) {
                bootbox.alert(data);
                carregar_secretarias();
            });
        }
    });
}

function mais(id) {
    location.href='/cadastro/secretarias/edit/' + id;
}