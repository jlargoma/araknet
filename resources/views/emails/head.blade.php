<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <meta name="viewport" content="width=device-width, user-scalable=no">
        <title><?php echo $tit; ?></title>
        <!-- Latest compiled and minified CSS & JS -->
        <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/css/bootstrap.min.css" integrity="sha384-1q8mTJOASx8j1Au+a5WDVnPi2lkFfwwEAa8hDDdjZlpLegxhjVME1fgjWPGmkzs7" crossorigin="anonymous">
        <script src="//code.jquery.com/jquery.js"></script>
        <script src="https://maxcdn.bootstrapcdn.com/bootstrap/3.3.6/js/bootstrap.min.js" integrity="sha384-0mSbJDEHialfmuBBQP6A4Qrprq5OVfW37PRR3j5ELqxss1yVqOtnepnHVP9aJ7xS" crossorigin="anonymous"></script>
        <style>
            .t-content{
                max-width: 98%;
                margin: 1em auto;
                width: 720px;
            }

            img.img-logo {
                width: 250px;
                margin: auto;
            }
            tr.t-head,
            tr.t-footer {
                text-align: center;
                background-color: #e0f5e9;
            }
            .t-content td {padding: 7px;}
            .box-content {padding: 25px 15px; min-height: 220px;}
            .t-body {border-left: 1px double #cecece; border-right: 1px double #cecece;}
            .t-content tr.t-head{
                box-shadow: 0px 7px 4px #dedede;
            }
            .t-content tr.t-footer{
                box-shadow: 0px 7px 4px #dedede;
            }
            h1 {
                font-weight: bold;
                font-size: 22px;
            }
            <?php $style; ?>
        </style>
    </head>
    <body>
        <table class="t-content">
            <tr class="t-head">
                <td>
                    <img src="{{url('/assets/logo-retina.png')}}" class="img-logo">
                    <h1><?php echo $tit; ?></h1>
                </td>
            </tr>
            <tr class="t-body">
                <td>
                    <div class="box-content">

