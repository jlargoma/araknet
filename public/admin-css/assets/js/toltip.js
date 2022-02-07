$(document).ready(function () {
  function showInfo(e, data) {
    var obj = $('toltip');
    var html = '<h3>' + data.n + '</h3>';
    if (data.d) html += '<p>' + data.d + '</p>';
    if (data.cn) html += '<p>' + data.cn + '</p>';
    html += '<p>' + data.p + '</p>';
    html += '<h4>' + data.s + '</h4>';
    if (data.dc)
      html += '<p>' + data.dc + ' / ' + data.mc + '</p>';
    if (data.date)
      html += '<p>Cita: ' + data.date + '</p>';

    obj.html(html);
    if (screen.width < 768) {
      var styles = {
        top: "auto",
        left: "auto",
        bottom: "-9px",
        right: "-9px"
      };
    } else {
      var top = e.clientY+5;  
      var left = e.clientX-5;  
      if (left<0) left = 0;
      var styles = {
        top: top,
        left: left,
      }
    }
    obj.css(styles).show();
  }
  $('.events').on('mouseleave', function () {
    $('toltip').hide();
  });
  $('.events').on('mouseenter', function (e) {
    var ID = $(this).data('id');
    for (d in details) {
      if (ID == d) {
        showInfo(e, details[d]);
      }
    }
  });
  $('.label').on('mouseleave', function () {
    $('toltip').hide();
  });
  $('.label').on('mouseenter', function () {
    var ID = $(this).data('id');
    for (d in details) {
      if (ID == d) {
        showInfo($(this).find('toltip'), details[d]);
      }
    }
  });
});