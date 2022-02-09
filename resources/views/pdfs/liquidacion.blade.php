<!DOCTYPE html>
<html>
    <head>
        <meta charset="UTF-8">
        <title>Liquidación de <?php echo $mes ?> de {{ $customer->name }}</title>
        <!-- Latest compiled and minified CSS & JS -->
        <style type="text/css">
            body{max-width: 800px; margin-top: 25px;}
            .table-borderless {
                border: none;
            }
            .table {
                width: 100%;
                max-width: 100%;
                margin-bottom: 20px;
            }


            .table > thead > tr > th, .table > tfoot > tr > th {
                padding: 16px 10px 12px;
                font-family: "Source Sans Pro", "Open Sans", "Helvetica Neue", Helvetica, Arial, sans-serif;
                font-size: 15px;
                font-weight: 600;
                text-transform: uppercase;
            }



            .table>tbody>tr>td, .table>tbody>tr>th, .table>tfoot>tr>td, .table>tfoot>tr>th, .table>thead>tr>td, .table>thead>tr>th {
                padding: 8px;
                line-height: 1.42857143;
                vertical-align: top;
                border-top: 1px solid #ddd;
            }

            .text-center {
                text-align: center;
            }
            .text-right {
                text-align: right;
            }
            th {
                text-align: left;
            }

            .turnos small{
                margin-left: 1em;
                display: block;
                font-size: 12px;
            }
            h1 {color: #7b7b7b;}
            h1 b {color: #000000;}
            thead{background-color: #cfddef;}
            tfoot{background-color: #f0f0f0;}
        </style>
    </head>
    <body>
        <h1 >Liquidación <?php echo $mes ?></h1>
        <h2 ><?php echo strtoupper($customer->name) ?></h2>
            <table class="table">
                <thead>
                    <tr>
                        <th style="text-align: center;">#</th>
                        <th style="text-align: left;">Concepto</th>
                        <th><span class="span-right">Total</span></th>
                    </tr>
                </thead>
                <tbody>
                    <?php
                    $i = 1;
                    $total = $salary;
                    ?>
                    <tr>
                        <td class="text-center"><strong><?php echo $i ?></strong></td>
                        <td class="text-left font-s20" style="text-transform: uppercase;">Salario Base</td>
                        <td class="text-center font-s20"><strong><?php echo $salary; ?>€</strong></td>
                    </tr>
                    <?php foreach ($classLst as $key => $pago): ?>
                        <?php $i++ ?>
                        <tr>
                            <td class="text-center"><strong><?php echo $i ?></strong></td>
                            <td class="text-left font-s18 turnos">

                                <?php echo $classLst[$key] ?>
                                <?php
                                if (isset($pagosClase[$key])):
                                    foreach ($pagosClase[$key] as $turno) {
                                        echo '<small>' . $turno . '</small>';
                                    }
                                endif;
                                ?>
                            </td>
                            <td class="text-center  font-s20"><strong><?php echo $totalClase[$key] ?>€</strong></td>
                        </tr>
                        <?php $total += $totalClase[$key]; ?>
                    <?php endforeach ?>
                        <?php foreach ($totalExtr as $key => $pago): ?>
                <?php $i++ ?>
                <tr>
                    <td class="text-center"><strong><?php echo $i ?></strong></td>
                    <td class="text-left font-s18 turnos">{{$nExtr[$key]}}</td>
                    <td class="text-right font-s20"><strong><?php echo $pago ?>€</strong></td>
                </tr>
                <?php $total += $pago; ?>
            <?php endforeach ?>
                </tbody>
                <tfoot>
                    <tr>
                        <td colspan="2" class="text-left"><strong>TOTAL:</strong></td>
                        <td style="text-align: right;font-size: 18px;"><strong><?php echo $total ?>€</strong></td>
                    </tr>
                </tfoot>
            </table>
            <div class="text-center">
                <small >Araknet <?php echo date('Y');?></small>
            </div>
    </body>
</html>
