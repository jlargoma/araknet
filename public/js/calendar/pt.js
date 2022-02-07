$(document).ready(function () {

    var ww = $(window).width();
    var isM = (ww < 780);

    var dateForm = null;
    var timeForm = null;
    $('.addDate').click(function (event) {
        event.preventDefault();
        dateForm = $(this).data('date');
        timeForm = $(this).data('time');
        if (isM){
            window.location.href = '/admin/citas-pt/create/'+dateForm+'/'+timeForm;
        } else {
            $('#ifrModal').attr('src', '/admin/citas-pt/create/' + dateForm + '/' + timeForm);
            $('#modalIfrm').modal();
        }
    });
    $('.editDate').on('click', '.events', function (event) {
        event.preventDefault();
        var id = $(this).data('id');
        if (isM){
            window.location.href = '/admin/citas-pt/edit/'+id;
        } else {
            $('#ifrModal').attr('src', '/admin/citas-pt/edit/' + id);
            $('#modalIfrm').modal();
        }
    });
    $('.editDate').on('mouseover', function (event) {
        var obj = $(this).find('.detail');

        obj.css('top', (event.screenY - 110));
        obj.css('left', (event.pageX - 100));

    });
    $('.selectDate').on('click', 'li', function (event) {
        event.preventDefault();
        var val = $(this).data('val');
        var type = $('#servSelect').val();
        var coach = $('#coachsFilter').val();
        location.assign("/admin/citas-pt/" + val + "/" + coach + "/" + type);
    });
    $('.coachsFilter').on('click', 'li', function (event) {
        event.preventDefault();
        var coach = $(this).data('val');
        var month = $('#selectMonth').val();
        var type = $('#servSelect').val();
        location.assign("/admin/citas-pt/" + month + "/" + coach + "/" + type);
    });
    $('#servSelect').on('change', function (event) {
        event.preventDefault();
        var type = $('#servSelect').val();
        var month = $('#selectMonth').val();
        var coach = $('#coachsFilter').val();
        location.assign("/admin/citas-pt/" + month + "/" + coach + "/" + type);
    });

    $('#modal_newUser').on('submit', '#form-new', function (event) {
        event.preventDefault();
        // Get some values from elements on the page:
        var $form = $(this);
        var url = $form.attr("action");
        // Send the data using post
        var posting = $.post(url, $form.serialize()).done(function (data) {
            if (data == 'OK') {
                $('#content-add-date').load('/admin/citas-pt/create/' + dateForm + '/' + timeForm);
                $('#modal_newUser').modal('hide');
                $('#modal-add-date').modal();
            } else {
                alert(data);
            }
        });
        //    
    });

    $('.btn-horarios').click(function (e) {
        e.preventDefault();
        $('#ifrModal').attr('src', '/admin/horariosEntrenador/');
    });

    $('.btn-bloqueo').click(function (e) {
        e.preventDefault();
        $('#ifrModal').attr('src', '/admin/citas/bloqueo-horarios/pt');
    });

    $('#search_cust').on('keyup', function () {
        var s = $(this).val();
        if (s != '') {
            s = s.toLowerCase();
            $('.events').each(function (index) {
                if ($(this).data('name').includes(s)) {
                    $(this).show();
                } else {
                    $(this).hide();
                }
            });
        } else {
            $('.events').show();
        }
    });

    $('.btnAvails').on('click', function () {
        if ($('.availDate').css('display') == 'none') {
            $('.editDate').find('.lst_events').hide();
            $('.editDate').find('.availDate').show();
        } else {
            $('.editDate').find('.lst_events').show();
            $('.editDate').find('.availDate').hide();
        }

    });
    if (typeCalend != 'week'){
        setTimeout(function(){
          $([document.documentElement, document.body]).animate({
            scrollTop: $("#cweek").offset().top-80
          }, 200);
        },250);
    }
    
    
    
    $('.prevWeek').on('click', function (event) {
        event.preventDefault();
        var week = $('#selectWeek').val();
        week--;
        if (week>0) goToWeek(week)
    });
    
    $('.nextWeek').on('click', function (event) {
        event.preventDefault();
        var week = $('#selectWeek').val();
        week++;
        if (week<53) goToWeek(week)
    });
    $('.currentWeek').on('click', function (event) {
        event.preventDefault();
        var week = $('#currentWeek').val();
        goToWeek(week)
    });
    
    function goToWeek(week) {
        var type = $('#servSelect').val();
        var month = $('#selectMonth').val();
        var coach = $('#coachsFilter').val();
        location.assign("/admin/citas-pt-week/" + week + "/" + coach + "/" + type);
    }
    
        
    if (countByCoah){
        for (var key in countByCoah){
            $('.select_'+key).find('.counter').text(countByCoah[key])
        }
        console.log(countByCoah)
    }
});