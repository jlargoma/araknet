<div class="box-payment-btn tex-center">
  <h4>Generar Link Notificación</h4>                        
  <div class="my-1">
      <button type="button" class="btn btn-default btnStripe my-1" data-t="mail">
          <i class="fa fa-envelope"></i> Enviar Mail
      </button>
  </div>
  <div class="my-1">
      <button type="button" class="btn btn-default btnStripe my-1" data-t="wsp">
          <i class="fa fa-whatsapp"></i> Enviar WSP 
      </button>
  </div>
  <div class="my-1">
      <button type="button" class="btn btn-default btnStripe my-1" data-t="copy">
          <i class="fa fa-copy"></i> Copiar link
      </button>
  </div>
  <textarea id="cpy_link" style="height: 0px; width: 0px; border: none; display: none;"></textarea>
</div>
<script type="text/javascript">
$(document).ready(function () {
    $('.btnStripe').on('click', function(){
        var type = $(this).data('t');
        var posting = $.post( '/admin/send/cobro-gral', { 
                            _token: '{{csrf_token()}}',
                            id_coach: $('#id_coach').val(),
                            u_email: $('#NC_email').val(),
                            u_phone: $('#NC_phone').val(),
                            idDate: $('#idDate').val(),
                            importe: $('#priceRate').val(),
                            type: type
                        });
            posting.done(function (data) {
                if (data[0] == 'OK') {
                    if (type == 'mail') {
                        window.show_notif('success', data[1]);
                    }
                    if (type == 'wsp') {
                        if (window.detectMob()) {
                            var url = 'whatsapp://send?text=' + encodeURI(data[1]);
                        } else {
                            var url = 'https://web.whatsapp.com/send?phone=' + $('#u_phone').val() + '&text=' + encodeURI(data[1]);
                        }
                        const newWindow = window.open(url, '_blank', 'noopener,noreferrer')
                        if (newWindow)
                            newWindow.opener = null
                    }
                    if (type == 'copy') {
                        $('#cpy_link').val(data[1]);
                        document.getElementById("cpy_link").style.display = "block";
                        document.getElementById("cpy_link").select();
                        document.execCommand("copy");
                        document.getElementById("cpy_link").style.display = "none";
                        window.show_notif('success', 'Mensaje copiado');
                    }

                } else {
                  window.show_notif('error', data[1]);
                }
                
            });


        });



    });
</script>