$(document).ready(function () {
    $('#searchRoutes').keypress(function () {
        var stringToSearch = $(this).val();
        if (stringToSearch.length > 3) {
            $.get("/admin/searchRoutes", {stringToSearch: stringToSearch}).done(function (data) {
                $('#searchResults').show();
                $('#searchResults').empty().append(data);
            });
        }
        if (stringToSearch.length > 0) {
            $('#searchResults').hide();
        }
    });

  $('.reload').on('click',function(){location.reload();});
//  $('.modal_reload').on('hidden.bs.modal', function () {location.reload();});

  $('form').on('submit', function(){
    if ($(this).data('ajax')) return '';
    $('body').append('<div class="loading"><i class="fa fa-spinner fa-spin"></i><br/>Enviando</div>');
  });

  window["show_notif"] = function(status,message){
    $.notify(message,status,{ z_index: 1031}); 
    };
    
    
  window["detectMob"] =  function (){
      const toMatch = [
      /Android/i,
      /webOS/i,
      /iPhone/i,
      /iPad/i,
      /iPod/i,
      /BlackBerry/i,
      /Windows Phone/i
      ];
      //alert(navigator.userAgent);
      return toMatch.some((toMatchItem) => {
      return navigator.userAgent.match(toMatchItem);
      });
    }


  window["formatDate"] = function (date) {
    var d = new Date(date),
        month = '' + (d.getMonth() + 1),
        day = '' + d.getDate(),
        year = d.getFullYear();

    if (month.length < 2) 
        month = '0' + month;
    if (day.length < 2) 
        day = '0' + day;

    return [year, month, day].join('-');
  }
  
    window["formatterEuro"] =  new Intl.NumberFormat('de-DE', {
        style: 'currency',
        currency: 'EUR',
        minimumFractionDigits: 0
     })
         
    $('.subMenu.open').on('click', function () {
        $(this).find('ul').slideToggle('slow');
    })
    
    $(".only-numbers").keydown(function (e) {
        // Allow: backspace, delete, tab, escape, enter and .
        if ($.inArray(e.keyCode, [46, 8, 9, 27, 13, 110, 190]) !== -1 ||
            // Allow: Ctrl/cmd+A
            (e.keyCode == 65 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+C
            (e.keyCode == 67 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: Ctrl/cmd+X
            (e.keyCode == 88 && (e.ctrlKey === true || e.metaKey === true)) ||
            // Allow: home, end, left, right
            (e.keyCode >= 35 && e.keyCode <= 39)) {
            // let it happen, don't do anything
              return;
        }
        // Ensure that it is a number and stop the keypress
        if ((e.shiftKey || (e.keyCode < 48 || e.keyCode > 57)) && (e.keyCode < 96 || e.keyCode > 105)) {
            e.preventDefault();
        }

    });
    
});