$(function() {
    $.fn.datepicker.dates['pt-BR'] = {
        days: ['Domingo','Segunda-feira','Terça-feira','Quarta-feira','Quinta-feira','Sexta-feira','Sábado'],
        daysShort: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        daysMin: ['Dom','Seg','Ter','Qua','Qui','Sex','Sáb'],
        months: ['Janeiro','Fevereiro','Março','Abril','Maio','Junho','Julho','Agosto','Setembro','Outubro','Novembro','Dezembro'],
        monthsShort: ['Jan','Fev','Mar','Abr','Mai','Jun','Jul','Ago','Set','Out','Nov','Dez'],
        today: "Hoje"
    };
    
    $('.date-picker').datepicker({language:'pt-BR'});
    
    $('.time-picker').timepicker({
            minuteStep: 1,
            showSeconds: true,
            showMeridian: false,
            defaultTime: ''
    });
});

function msg(title, text){
    $.extend($.gritter.options, { 
        position: 'bottom-right',
        time: 3000
    });
        
    $.gritter.add({
        title: title,
        text: text,
        class_name: 'gritter-light',
        position: 'bottom-left'
    });
}

