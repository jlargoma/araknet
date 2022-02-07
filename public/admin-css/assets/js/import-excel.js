$(document).ready(function () {
  function tableHeaderOption(id) {
    var dropdowm = $('<select/>');
    dropdowm.addClass('colExcel');
    dropdowm.attr('name', 'column_' + id);
    dropdowm.data('col_id', id);
    dropdowm.append('<option value="">--</option>');
    dropdowm.append('<option value="QUITAR">-QUITAR-</option>');
//          Fecha Concepto Tipo Método de pago Sitio Comentario
    var lst = [
      {n: 'date', t: 'Fecha'},
      {n: 'import', t: 'Monto'},
      {n: 'concept', t: 'Concepto'},
      {n: 'type', t: 'Tipo'},
      {n: 'typePayment', t: 'Método de pago'},
      {n: 'comment', t: 'Comentario'},
      {n: 'filter', t: 'Filtrar'},
    ]

    var select = '';
    for (var i in lst) {
      select = (id == i) ? 'selected' : '';
      dropdowm.append('<option value="' + lst[i].n + '" ' + select + '>' + lst[i].t + '</option>');
    }
    return dropdowm;
  }

  function generateTable(data) {
    var rows = data.split("\n");
    /********   HEADERs   ****************/
    var cells = rows[0].split("\t");
    var tHead = $('<tHead />');
    var row = $('<tr />');
    for (var c in cells) {
      var th = $('<th/>');
      th.addClass('exc_col_' + c);
      th.append(tableHeaderOption(c));
      row.append(th);
    }
    tHead.append(row);
    $('#excel_table').append(tHead);

    /********   BODYs   ****************/
    var tBody = $('<tBody />');
    rows.pop();
    for (var y in rows) {
      var cells = rows[y].split("\t");
      var row = $('<tr />');
      for (var x in cells) {
        var input = $('<input/>');
        input.attr('name', 'cell_' + x + '[]');
        input.val(cells[x]);
        var td = $('<td/>').append(input);
        td.addClass('exc_col_' + x);
        row.append(td);
      }
      tBody.append(row);
    }
    $('#excel_table').append(tBody);
  }

  $('#importExcel').on('change', function () {
    generateTable($(this).val());
    $(this).val('');
    $(this).remove();
    $('.btnImportExcel').remove();
    $('.btnSendImportExcel').show();

  });

  $('#excel_table').on('change', '.colExcel', function () {
    if ($(this).val() == 'QUITAR') {
      $('.exc_col_' + $(this).data('col_id')).remove();
      ;
    }
  });
});