<script type="text/javascript">
  $(document).ready(function () {
    $('.sendInvoiceEmail').on('click', function (e) {
      e.preventDefault();
      e.stopPropagation();
      if (confirm('Enviar factura a ' + $('#email').val() + '?')) {
        $('#loadigPage').show('slow');
        $.ajax({
          url: '/admin/facturas/enviar',
          type: 'POST',
          data: {
            id: $(this).data('id'),
            _token: "{{csrf_token()}}"
          }
        })
            .done(function (resp) {
              if (resp == 'OK')
                window.show_notif('success', 'Factura enviada');
              else
                window.show_notif('error', resp);
            })
            .fail(function () {
              window.show_notif('error', 'Factura no enviada');
            })
            .always(function () {
              $('#loadigPage').hide('slow');
            });
      }
    });
  });
</script>