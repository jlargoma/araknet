<script type="text/javascript">

    $(document).ready(function () {
        $('#type_payment').change(function (e) {
            var value = $("#type_payment option:selected").val();
            if (value == "card") {
                var operation = $('input[type=radio][name=operation]:checked').val();
                if (typeof operation == 'undefined'|| operation != 'stripe') {
                    $('#stripeBox').find('.disabled').hide();
                    $('.form-toPayment').attr('id', 'paymentForm');
                    $(".new_cc").prop('required', false);
                } else {
                    $(".new_cc").prop('required', true);
                }
            } else {
                $('#stripeBox').find('.disabled').show();
                $('.form-toPayment').removeAttr('id');
                $(".new_cc").prop('required', false);
            }

        });

        $('input[type=radio][name=operation]').change(function () {
            if (this.value == 'stripe') {
                $(".new_cc").prop('required', false);
                $('#stripeBox').find('.disabled').show();
            } else {
                if ($("#type_payment option:selected").val() == 'card') {
                    $(".new_cc").prop('required', true);
                    $('#stripeBox').find('.disabled').hide();
                } else {
                    $(".new_cc").prop('required', false);
                }
            }
        });

<?php if ($card): ?>
            $('#card-element').hide();
            $(".new_cc").prop('required', false);
            $('#changeCreditCard').on('click', function () {
                $('#card-element').show();
                $('#cardExists').hide();
                $('#cardLoaded').val(0);
                $(".new_cc").prop('required', true);
            });
<?php endif; ?>


        function format(input, type) {
            var num = input.replace(/[^\d\.]*/g, '');
            var result = '';
            if (!isNaN(num)) {
                var aux = '';
                var i = 0;
                num = num.toString().split('');
                var length = num.length;
                /*************************************************/
                if (type == 'date') {
                    var cc_expide_mm = '';
                    var cc_expide_yy = '';
                    for (i; i < length; i++) {
                        if (i<2) cc_expide_mm += num[i];
                        if (i>1 && i<4) cc_expide_yy += num[i];
                        if (i == 2)
                            result += '/';
                        if (i > 3)
                            continue;
                        result += num[i];
                    }
                    $('#cc_expide_mm').val(cc_expide_mm);
                    $('#cc_expide_yy').val(cc_expide_yy);
                    return result;
                }
                /*************************************************/
                if (type == 'cvc') {
                    for (i; i < length; i++) {
                        if (i > 3) continue;
                        result += num[i];
                    }
                    return result;
                }
                /*************************************************/
                if (type == 'card') {
                    var first = num[0];
                    if (length > 1)
                        first += num[1];
                    if (first == '37' || first == '36') {
                        for (i; i < length; i++) {
                            if (i > 14)
                                continue;
                            if (i == 4 || i == 10)
                                result += ' ';
                            result += num[i];
                        }
                    } else {
                        for (i; i < length; i++) {
                            if (i > 15)
                                continue;
                            if (i > 0 && i % 4 == 0)
                                result += ' ';
                            result += num[i];
                        }
                    }
                    return result;
                }
                /*************************************************/
                return result;
            } else {
                alert('Solo se permiten numeros');
                return input.replace(/[^\d\.]*/g, '');
            }
        }
        
        $('.formated').on('keyup', function () {
            $(this).val(format($(this).val(), $(this).data('t')));
        });
        $('.formated').on('change', function () {
            $(this).val(format($(this).val(), $(this).data('t')));
        });
    });
</script>