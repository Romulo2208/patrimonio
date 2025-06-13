$(function() {
    $('#EventoStart').mask('99/99/9999');
    $('#EventoEnd').mask('99/99/9999');
    
    $('#EventoColor').ace_colorpicker();
    
    $('#EventoIdGrupo').change(function(){
        consultarTipo();
    });
    
    $('#EventoIdTipo').change(function(){
        consultarSubTipo();
        alertaPadrao();
    });
    
//    $('#btnConsultar').click(function(){
//        $.post($('#url').val()+'/getMatSedf', {
//            'data[matricula]': $('#matricula').val()
//        }, function(data){
//            $('#EventoObservacao').val(
//                    'Matrícula: ' + data[0].SA.MatSedf + '\n' +
//                    'Nome: ' + data[0].A.Nome 
//                    );
//            $('#situacao').html('<h4>Situação: ' + data[0].A.Situacao.replace(/,/g, '') + '</h4>');
//        }, 'json');
//    });

    $('#btnConsultar').click(function(){
        $.post($('#url').val()+'/getcpf', {
            'data[cpf]': $('#cpf').val()
        }, function(data){
            $('#EventoObservacao').val(
                    'Matrícula: ' + data[0].SA.matsedf + '\n' +
                    'CPF: ' + data[0].A.cpf + '\n' +
                    'Nome: ' + data[0].A.nome 
                    );
            $('#situacao').html('<h4>Situação: ' + data[0].S.descricao.replace(/,/g, '') + '</h4>');
        }, 'json');
    });

});

function consultarTipo(id, sub){
    $.post($('#url').val()+'/getTipo', {
        'data[Evento][id_grupo]': $('#EventoIdGrupo').val()
    }, function(data){
        $('#EventoIdSubTipo').html('<option value="">(nenhum)</option>');
        $('#EventoIdTipo').html('<option value="">(nenhum)</option>');
        for(var i=0;i<data.length;i++){
            $('#EventoIdTipo').append('<option value=\''+data[i].Tipo.id+'\'>'+data[i].Tipo.descricao+'</option>');
        }
        
        if(id && id !== '0') $('#EventoIdTipo').val(id);
        if(sub) consultarSubTipo(sub);
    }, 'json');
}

function consultarSubTipo(id){
    $.post($('#url').val()+'/getSubTipo', {
        'data[Evento][id_tipo]': $('#EventoIdTipo').val()
    }, function(data){
        $('#EventoIdSubTipo').html('<option value="">(nenhum)</option>');
        for(var i=0;i<data.length;i++){
            $('#EventoIdSubTipo').append('<option value=\''+data[i].SubTipo.id+'\'>'+data[i].SubTipo.descricao+'</option>');
        }
        if(id && id !== '0') $('#EventoIdSubTipo').val(id);
    }, 'json');
}

function alertaPadrao(){
    switch ($('#EventoIdTipo').val()){
        case '1': $('#EventoAlert').val('10'); break; //Chácara
        case '2': $('#EventoAlert').val('1'); break;  //Som
        case '3': $('#EventoAlert').val('1'); break;  //Ônibus
    }
}
