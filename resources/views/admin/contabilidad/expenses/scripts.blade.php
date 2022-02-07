<script type="text/javascript">
  
  var expense_year = 0;
  var expense_month = 0;
  var dataTable = function (month) {
      $('#month').val(month);
      $('.month_select.active').removeClass('active');
      expense_year = year;
      expense_month = month;
      $('#loadigPage').show('slow');
      $.ajax({
      url: '/admin/gastos/gastosLst',
          type: 'POST',
          data: {month: month, '_token': "{{csrf_token()}}"},
          success: function (response) {
          if (response.status === 'true') {

          $('#ms_' + month).addClass('active');
              $('#tableItems').html('');
              $('#totalMounth').html(response.totalMounth);
              $('#totalMounth').data('orig', response.totalMounth);
              $.each((response.respo_list), function (index, val) {
              var row = '<tr data-id="' + val.id + '" data-import="' + val.import + '"><td>' + val.date + '</td>';
                  row += '<td class="editable" data-type="concept">' + val.concept + '</td>';
                  row += '<td class="editable selects stype" data-type="type" data-current="' + val.type_v + '" >' + val.type + '</td>';
                  row += '<td class="editable selects spayment" data-type="payment" data-current="' + val.typePayment_v + '" >' + val.typePayment + '</td>';
                  row += '<td class="editable" data-type="price">' + val.import + '</td>';
                  row += '<td><button data-id="' + val.id + '" type="button" class="del_expense btn btn-danger btn-xs"><i class="fa fa-trash"></i></button>';
                  row += '<td class="editable" data-type="comm">' + val.comment + '</td>';
                  row += '<td class="editable selects suser" data-type="usr" data-current="' + val.to_user + '">' + val.usr + '</td>';
                  $('#tableItems').append(row);
              });
          } else {
          window.show_notif('error', 'El listado está vacío no ha sido guardado.');
          }
          $('#loadigPage').hide('slow');
          },
          error: function (response) {
          window.show_notif('error', 'No se ha podido obtener los detalles de la consulta.');
              $('#loadigPage').hide('slow');
          }
      });
  }

  $(document).ready(function () {
 
  
  dataTable({!!$current!!});
  
  
   $('#modalAddNew').on('submit', '#formNewExpense', function (e) {
      e.preventDefault();
      $.ajax({
        url: $(this).attr('action'),
        type: 'POST',
        data: $(this).serializeArray(),
        success: function (response) {
          if (response == 'ok') {
            $('#import').val('');
            $('#concept').val('');
            $('#comment').val('');
            alert('Gasto Agregado');
          } else
            alert(response);
        }
      });
    });
      
    $('#modalAddNew').on('click', '#reload', function (e) {
      location.reload();
    });
    /********************************************************************/
    /********************************************************************/
    const hTable = $('#tableItems');
    function edit(currentElement, type) {
      switch (type) {
        case 'price':
          var input = $('<input>', {type: "number", class: type})
                  .val(currentElement.html())
          currentElement.data('value', currentElement.html());
          currentElement.html(input);
          input.focus();
          break;
        case 'type':
          var select = $('<select>', {class: ' form-control'});
          select.data('t', 'type');
<?php
foreach ($gType as $k => $v) {
  echo "var option = $('<option></option>');
                            option.attr('value', '$k');
                            option.text('$v');
                            select.append(option);";
}
?>
          currentElement.data('value', currentElement.html());
          select.val(currentElement.data('current'));
          currentElement.html(select);
          break;
        case 'payment':
          var select = $('<select>', {class: ' form-control'});
          select.data('t', 'payment');
<?php
foreach ($typePayment as $k => $v) {
  echo "var option = $('<option></option>');
                            option.attr('value', '$k');
                            option.text('$v');
                            select.append(option);";
}
?>
          currentElement.data('value', currentElement.html());
          select.val(currentElement.data('current'));
          currentElement.html(select);
          break;
        case 'usr':
          var select = $('<select>', {class: ' form-control'});
          select.data('t', 'user');
<?php
foreach ($lstUsr as $k => $v) {
  echo "var option = $('<option></option>');
                            option.attr('value', '$k');
                            option.text('$v');
                            select.append(option);";
}
?>
          currentElement.data('value', currentElement.html());
          select.val(currentElement.data('current'));
          currentElement.html(select);
          break;
        default:
          var input = $('<input>', {type: "text", class: type})
                  .val(currentElement.html())
          currentElement.data('value', currentElement.html());
          currentElement.html(input);
          input.focus();
          break;
      }

    }
    hTable.on('click', '.editable', function () {
      var that = $(this);
      if (!that.hasClass('tSelect')) {
        clearAll();
        that.data('val', that.text());
        that.addClass('tSelect')
        var type = $(this).data('type');
        edit($(this), type);
      }
    });
    hTable.on('keyup', '.tSelect', function (e) {
      if (e.keyCode == 13) {
        var id = $(this).closest('tr').data('id');
        var input = $(this).find('input');
        updValues(id, input.attr('class'), input.val(), $(this));
      } else {
        hTable.find('.tSelect').find('input').val($(this).find('input').val());
      }
    });
    hTable.on('change', '.selects', function (e) {
      var id = $(this).closest('tr').data('id');
      var input = $(this).find('select');
      updValues(id, input.data('t'), input.val(), $(this), $(this).find('option:selected').text());
    });
    var clearAll = function () {
      hTable.find('.tSelect').each(function () {
        $(this).text($(this).data('value')).removeClass('tSelect');
      });
    }

    var updValues = function (id, type, value, obj, text = null) {
      var url = "/admin/gastos/update";
      $.ajax({
        type: "POST",
        method: "POST",
        url: url,
        data: {_token: "{{ csrf_token() }}", id: id, val: value, type: type},
        success: function (response)
        {
          if (response == 'ok') {
            clearAll();
            window.show_notif('success', 'Registro Actualizado');
            if (text)
              obj.text(text);
            else
              obj.text(value);
          } else {
            window.show_notif('error', 'Registro NO Actualizado');
          }
        }
      });
    }

    var filters = {
      type: -1,
      paym: -1,
      usr: -1,
    };
    var filterTable = function () {
      var all = false;
      if (filters.type == -1 && filters.paym == -1 && filters.usr == -1) {
        all = true;
      }
      var total = 0;
      $('#tableItems tr').each(function () {
        $(this).show();
        if (!all) {
//filter by type
          if (filters.type != -1) {
            var cell = $(this).find('.stype');
            if (cell.data('current') != filters.type) {
              cell.closest('tr').hide();
              return;
            }
          }
//filter by type payment
          if (filters.paym != -1) {
            var cell = $(this).find('.spayment');
            if (cell.data('current') != filters.paym) {
              cell.closest('tr').hide();
              return;
            }
          }
//filter by Users
          if (filters.usr != -1) {
            var cell = $(this).find('.suser');
            console.log(cell.data('current'),filters.usr);
            if (cell.data('current') != filters.usr) {
              cell.closest('tr').hide();
              return;
            }
          }
          total += parseFloat($(this).data('import'));
        }
      });
      if (all)
        $('#totalMounth').text($('#totalMounth').data('orig'));
      else
        $('#totalMounth').text(window.formatterEuro.format(total));
    }
    $('#s_type').on('change', function () {
      var value = $(this).val();
      filters.type = value;
      filterTable();
    });
    $('#s_payment').on('change', function () {
      var value = $(this).val();
      filters.paym = value;
      filterTable();
    });
    $('#s_usr').on('change', function () {
      var value = $(this).val();
      filters.usr = value;
      filterTable();
    });
    $('.month_select').on('click', function () {
      
    });
    $('.selectDate').on('click', 'li', function(event){
      event.preventDefault();
      $('#s_payment').val(-1);
      $('#s_type').val(-1);
      $('#s_usr').val(-1);
      filters = {
        type: -1,
        paym: -1,
        usr: -1,
      };
      dataTable( $(this).data('val'));
      $('.selectDate li').removeClass('active');
      $(this).addClass('active');
//      location.assign("/admin/gastos/" + val);
    });
  
        
    /********************************************************************/
    
    $('#tableItems').on('click', '.del_expense', function () {
      if (confirm('Eliminar el registro definitivamente?')) {
        var id = $(this).data('id');
        $.ajax({
          url: '/admin/gastos/del',
          type: 'POST',
          data: {id: id, '_token': "{{csrf_token()}}"},
          success: function (response) {
            dataTable($('#year').val(), $('#month').val());
          }
        });
      }
    });
    
    
    /********************************************************************/
    
    
    var chart_1 = {
        labels: [<?php echo implode(',',$labels_1)?>],
        datasets: [
          {
            data: [<?php echo implode(',',$values_1)?>],
            backgroundColor: [<?php echo implode(',',$bColor_1)?>],
          }
        ]
      }
    getPieChart('chart_1',chart_1);
      
    new Chart(document.getElementById("chartTotalByMonth"), {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($lstMonths as $v) echo "'" . $v . "',";?>
          ],
          datasets: [ <?php echo $tYearMonths;?>]
        },
        options: {
          title: {
            display: true,
            text: 'Total x Año'
          }
        }
      });
    new Chart(document.getElementById("chartTotalByMonth"), {
        type: 'line',
        data: {
          labels: [
            <?php foreach ($lstMonths as $v) echo "'" . $v . "',";?>
          ],
          datasets: [ <?php echo $tYearMonths;?>]
        },
        options: {
          title: {
            display: true,
            text: 'Total x Año/Mes'
          }
        }
      });
      
      var myBarChart = new Chart('chartTotalByYear', {
        type: 'bar',
        data: {
          labels: [
              <?php foreach ($totalYear as $k=>$v){ echo "'" . $k. "'," ;} ?>
          ],
          datasets: [
            {
              label: "Gastos por Temp",
              backgroundColor: 'rgba(54, 162, 235, 0.2)',
              borderColor: 'rgba(54, 162, 235, 1)',
              borderWidth: 1,
              data: [
                  <?php foreach ($totalYear as $k=>$v){ echo "'" . round($v). "'," ;} ?>
              ],
            }
          ]
          }
      });
      
            
  });
  jQuery(function () {
    App.initHelpers(['datepicker']);
});
</script>