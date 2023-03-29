<html lang="en">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel="shortcut icon" type="image/x-icon" href="http://localhost:8000/admin_asset/img/favicon.png">
    <title>:: Inventory Management System ::</title>
    <link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.5.0/css/bootstrap.min.css">
    <style>
    .footer {
        position: fixed;
        left: 0;
        bottom: 0;
        width: 100%;
        text-align: center;
    }
    </style>
</head>

<body>
    <div class="container mt-5">
        <h4 class="text-center mb-3">Consumption Note # <?php echo $ConsumptionNoteList['code'] ?></h4>
        <div class="row" style="margin-bottom: 25px;">
            <div class="col-md-4">
                <ul class="list-unstyled mb-0">
                    <li>Consumption Date :</li>
                    <li><?php echo ShowNewDateFormat($ConsumptionNoteList['created_at']) ?> </li>
                </ul>
            </div>
        </div>
        <table class="table table-bordered table-sm mb-5">
            <thead>
                <tr>
                    <th scope="col">#</th>
                    <th scope="col">Name</th>
                    <th scope="col">Code</th>
                    <th scope="col">Qty</th>
                </tr>
            </thead>
            <tbody>
                <?php 
            // print_r($ConsumptionNoteList->code); 
            if(!empty($ConsumptionNoteList['consumptionnote'])) {
                $tmpNote = json_decode($ConsumptionNoteList['consumptionnote'],true); $i = 1;
                if(count($tmpNote) > 0) { 
                    foreach($tmpNote as $nt1)  { ?>
                <tr>
                    <td><?php echo $i ?></td>
                    <td><?php echo RawMaterialName($nt1['RawId'])->name ?></td>
                    <td><?php echo RawMaterialName($nt1['RawId'])->code ?></td>
                    <td><?php echo $nt1['ConsumeQun'] ?></td>
                </tr>
                <?php $i++; } 
                }
            } ?>
            </tbody>
        </table>
        <div class="row footer">
            <div class="col-md-12 copy">
                <p class="text-center"> © Copyright 2022 - Sanfinity Creative Solution Pvt. Ltd.</p>
            </div>
        </div>
    </div>
</body>

</html>