$(document).ready(function() { 
  window["show_notif"] = function(title,status,message){
    
    var icon = 'fa';
    switch(status){
      case "success":
        icon += ' fa-check';
        break;
      case "error":
        icon += ' fa-exclamation';
        break;
      case "success":
        icon += ' fa-exclamation';
        break;
    }
    var titleVar = '';
    if (title !== '') titleVar = '<strong>'+title+'</strong>, ';
    $.notify({
          title: titleVar,
          icon: icon,
          message: message
      },{
          type: status,
          animate: {
              enter: 'animated fadeInUp',
              exit: 'animated fadeOutRight'
          },
          placement: {
              from: "top",
              align: "left"
          },
          offset: 20,
          spacing: 10,
          z_index: 1031,
          allow_dismiss: true,
          delay: 1000,
          timer: 3000,
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
     
});

