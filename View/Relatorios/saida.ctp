<?php $bootstrap = "http://{$_SERVER['HTTP_HOST']}/app/bootstrap"; ?>

<div class="page-header position-relative">
  <h1> Relat&oacute;rio <small> <i class="icon-double-angle-right"></i> Saidas </small>
  </h1>
</div>

<table id="dyntable" class="table table-striped table-bordered table-hover">
  <thead>
    <tr>
      <th>NOME</th>
      <th>LOCALIZA&Ccedil;&Atilde;O</th>
      <th>Data da Saida</th>
      <th>QUANTIDADE</th>
      <th>CATEGORIA</th>
      <th>CONFER&Ecirc;NCIA</th>
    </tr>
  </thead>
  <tbody>
    <?php foreach ($saidas as $saida): ?>
      <tr>
        <td><?php echo h($saida['Material']['nome']); ?>&nbsp;</td>
        <td><?php echo h($saida['Localizacao']['descricao']); ?>&nbsp;</td>
        <td><?php echo h($saida['Saida']['data_saida']); ?>&nbsp;</td>
        <td><?php echo h($saida['Saida']['quantidade_saida']); ?>&nbsp;</td>
        <td><?php echo h($saida['MaterialClassificacao']['descricao']); ?>&nbsp;</td>
        <td>&nbsp;</td>
      </tr>
    <?php endforeach; ?>
  </tbody>
</table>

<script type="text/javascript">
$(document).ready(function () {
  $('#dyntable').dataTable({
    "dom": 'TC<"clear">lfrtip',
    "columnDefs": [
      {type: 'non-empty-string', targets: 0} // define 'name' column as non-empty-string type
    ],
    "aoColumns": [
      null,
      null,
      null,
      null,
      null,
      {"bVisible": false}
    ],
    tableTools: {
      "sSwfPath": "<?php echo $bootstrap; ?>/assets/js/DataTables/extensions/TableTools/swf/copy_csv_xls_pdf.swf",
      "aButtons": [
        {
          "sExtends": "collection",
          "sButtonText": "Salvar Como",
          "aButtons": [
            {
              "sExtends": "pdf",
              "mColumns": "visible",
              "sButtonText": "PDF",
              "sPdfMessage": "BRITACAL <?php echo date('d/m/Y'); ?>",
              "sFileName": "materiais.pdf",
              "sTitle": "Materiais",
              "fnComplete": function (nButton, oConfig, oFlash, sFlash) {
                msg('Relatório', 'Salvo com sucesso!');
              },
              "oSelectorOpts": {
                filter: "applied"
              }
            },
            {
              "sExtends": "csv",
              "mColumns": "visible",
              "sButtonText": "CSV",
              "sPdfMessage": "SINPRO-DF <?php echo date('d/m/Y'); ?>",
              "sFileName": "materiais.csv",
              "sTitle": "Materiais",
              "fnComplete": function (nButton, oConfig, oFlash, sFlash) {
                msg('Relatório', 'Salvo com sucesso!');
              },
              "oSelectorOpts": {
                filter: "applied"
              }
            }
          ]
        },
      ]
    },
    "colVis": {
      "buttonText": "Ocultar Colunas",
      "showAll": "Mostrar Todos"
    },

    "oLanguage": {
      "sLengthMenu": "Mostrar _MENU_ registros por p&aacute;gina",
      "sInfoFiltered": "(filtrado de _MAX_ registros)",
      "sZeroRecords": "Nenhum registro encontrado",
      "sInfo": "Mostrando _START_ a _END_ de _TOTAL_ registros",
      "sSearch": "Pesquisar: ",
      "oPaginate": {
        "sFirst": "In&iacute;cio",
        "sPrevious": "Anterior",
        "sNext": "Pr&oacute;ximo",
        "sLast": "&Uacute;ltimo"
      }
    }

  }).columnFilter();

});

jQuery.extend( jQuery.fn.dataTableExt.oSort, {
  "non-empty-string-pre": function(str) {
    return removeDiacritics(str)
  },
  "non-empty-string-asc": function (str1, str2) {
    if(str1 == "")
    return 1;
    if(str2 == "")
    return -1;
    return ((str1 < str2) ? -1 : ((str1 > str2) ? 1 : 0));
  },

  "non-empty-string-desc": function (str1, str2) {
    if(str1 == "")
    return 1;
    if(str2 == "")
    return -1;
    return ((str1 < str2) ? 1 : ((str1 > str2) ? -1 : 0));
  }
} );


//$.fn.dataTable.ext.order.intl();
// jQuery.extend(jQuery.fn.dataTableExt.oSort, {
//     "date-uk-pre": function (a) {
//         var ukDatea = a.split('/');
//         return (ukDatea[2] + ukDatea[1] + ukDatea[0]) * 1;
//     },
//     "date-uk-asc": function (a, b) {
//         return ((a < b) ? -1 : ((a > b) ? 1 : 0));
//     },
//     "date-uk-desc": function (a, b) {
//         return ((a < b) ? 1 : ((a > b) ? -1 : 0));
//     }
// });
//
// function dateFormat(date) {
//     var dateArr = date.split("/");
//     return dateArr[2] + "-" + dateArr[1] + "-" + dateArr[0];
// }
</script>
