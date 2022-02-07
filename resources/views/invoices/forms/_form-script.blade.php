<style type="text/css">
  h3.invoice {
    background-color: #5c90d2;
    padding: 7px;
    color: #FFF;
  }
  button.btn.btn-danger.rmItem {
    margin-top: 23px;
  }
  .table thead tr th{color: #565656;}
  #itemInvoices td{
    padding: 7px !important;
  }
  #itemInvoices input{
    padding: 0 5px;
  }
  #itemInvoices textarea.form-control.itemname {
    height: 2.9em;
    padding: 4px 5px;
    overflow: hidden;
  }
  #itemInvoices button.rmItem {
    color: red;
    padding: 2px;
    font-weight: bold;
  }
  tfoot#summary td{
    padding: 12px !important;
    border-bottom: 1px solid #6d5cae !important;
    border-top: 1px solid #6d5cae !important;
  }
</style>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker.min.js')}}"></script>
<script src="{{asset('/admin-css/assets/js/plugins/bootstrap-datetimepicker/moment.min.js')}}"></script>
<link rel="stylesheet" href="{{asset('/admin-css/assets/js/plugins/bootstrap-datepicker/bootstrap-datepicker3.min.css')}}">
<script>
  $(function () {
    App.initHelpers(['datepicker']);
    function removeIVA(price,iva){
      return parseFloat(price / (1 + iva/100),2);
    }

    var calculSummary = function(){
      
      var ivas = {};
      var bruto = 0;
      var total = 0;
      var subtotal = 0;
       $('#itemInvoices tr').each(function(v,obj){
         var iva = parseFloat($(this).find('.iva').val());
         var val = parseFloat($(this).find('.prices').val());
         if (isNaN(val) || val<1) return;
         
         total += val;
         if (!isNaN(iva) && isFinite(iva)){
          var subtotal = removeIVA(val,iva);
          bruto += subtotal;
          if (iva>0){
             if (typeof ivas[iva] == "undefined")
               ivas[iva] = 0;

             ivas[iva] += val-subtotal;
          }
        } else {
          subtotal += val;
        }
        
       })
       
       
      var summary = $('#summary');
      summary.empty();
      var tr = $('<tr>');
      
      tr.append('<td colspan="3">Bruto</td>');
      tr.append('<td>'+parseFloat(bruto).toFixed(2)+'</td>');
      summary.append(tr);
      
      for(var i in ivas){
        tr = $('<tr>');
        tr.append('<td colspan="3">IVA '+i+'%</td>');
        tr.append('<td>'+parseFloat(ivas[i]).toFixed(2)+'</td>');
        summary.append(tr);
      }
      tr = $('<tr>');
      tr.append('<td colspan="3">Total</td>');
      tr.append('<td>'+parseFloat(total).toFixed(2)+'</td>');
      summary.append(tr);
    }
    
    
    
    
    
    $('#delete').on('click', function (event) {
      event.stopPropagation();  
      if (confirm('Eliminar la factura?')) {
        var data = {
          id: $(this).data('id'),
          _token: "{{csrf_token()}}"
        };
        $.ajax({
          url: "{{route('invoice.delete')}}",
          data: data,
          type: 'DELETE',
          success: function (result) {
            if (result == 'OK') {
              window.show_notif('success', 'Registro Eliminado.');
              setTimeout(function(){location.reload();},500);
              
//              location.href = '/admin/facturas';
            } else {
              window.show_notif('error', 'Registro no encontrado');
            }
          },
          error: function (e) {
            console.log(e);
            window.show_notif('error', 'Error de sistema');
          }
        });
      }
    });
    $('#addItem').on('click', function (event) {
      event.stopPropagation();  
      var tr = $('<tr>');
      tr.append('<td><textarea name="item[]" class="form-control itemname"></textarea></td>');
      tr.append('<td><input type="number" step="0.01" name="iva[]" class="form-control iva" value="10"></td>');
      tr.append('<td><input type="number" step="0.01" name="price[]" class="form-control prices" ></td>');
      tr.append('<td><button type="button" class="rmItem">X</button></td>');

      $('#itemInvoices').append(tr);
    });

    $('#itemInvoices').on('click', '.rmItem', function (event) {
      event.stopPropagation();
      var name = $(this).closest('tr').find('.itemname').val();
      if (confirm('Eliminar ' + name + '?')) {
        $(this).closest('tr').remove();
      }
    });

    $('#itemInvoices').on('keyup', '.form-control', function (event) {
      event.stopPropagation();
      calculSummary();
    });
    
    
    
    
    
    calculSummary();
  });
</script>