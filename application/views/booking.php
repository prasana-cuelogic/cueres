<?php
defined('BASEPATH') OR exit('No direct script access allowed');
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- The above 3 meta tags *must* come first in the head; any other head content must come *after* these tags -->
    <title>Bootstrap 101 Template</title>

    <!-- Bootstrap -->
    <link href="<?=$link;?>/application/views/css/bootstrap.min.css" rel="stylesheet">

    <!-- HTML5 shim and Respond.js for IE8 support of HTML5 elements and media queries -->
    <!-- WARNING: Respond.js doesn't work if you view the page via file:// -->
    <!--[if lt IE 9]>
    <script src="https://oss.maxcdn.com/html5shiv/3.7.2/html5shiv.min.js"></script>
    <script src="https://oss.maxcdn.com/respond/1.4.2/respond.min.js"></script>
    <![endif]-->
</head>
<body>

<div class="container">
    <div id="carousel-example-generic" class="carousel slide" data-ride="carousel">
        <!-- Indicators -->
        <ol class="carousel-indicators">
            <li data-target="#carousel-example-generic" data-slide-to="0" class="active">Conference Room</li>
            <li data-target="#carousel-example-generic" data-slide-to="1">Meeting Room</li>
            <!--li data-target="#carousel-example-generic" data-slide-to="2">Hardwares</li-->
            <li data-target="#carousel-example-generic" data-slide-to="2">TT Table</li>
        </ol>

        <!-- Wrapper for slides -->
        <div class="carousel-inner" role="listbox">
            <div class="item active">
                <div class="row">
                    <aside class="col-md-3">
                        <ul>
                           <li></li>
                        </ul>
                    </aside>
                    <section class="col-md-9">
                        <?php
                            if(isset($arrBookedSlots[1])){
                                foreach($arrBookedSlots[1] as $objRow){?>
                        <ul>
                            <li class=""><?=$objRow['name']?></li>
                            <li class="reserved"><?=$objRow['time']?></li>
                            <li class="available"><?=$objRow['purpose']?></li>
                        </ul>
                        <?php } }?>
                    </section>
                </div>
            </div>
            <div class="item">
                <div class="row">
                    <aside class="col-md-3">
                        <ul>
                            <li></li>
                        </ul>
                    </aside>
                    <section class="col-md-9">
                        <?php
                            if(isset($arrBookedSlots[2])){
                            foreach($arrBookedSlots[2] as $objRow){?>
                                <ul>
                                    <li class=""><?=$objRow['name']?></li>
                                    <li class="reserved"><?=$objRow['time']?></li>
                                    <li class="available"><?=$objRow['purpose']?></li>
                                </ul>
                            <?php } }?>
                    </section>
                </div>
            </div>
            <div class="item">
                <div class="row">
                    <aside class="col-md-3">
                        <ul>
                            <li></li>
                        </ul>
                    </aside>
                    <section class="col-md-9">
                        <?php
                            if(isset($arrBookedSlots[3])){
                                foreach($arrBookedSlots[3] as $objRow){?>
                                    <ul>
                                        <li class=""><?=$objRow['name']?></li>
                                        <li class="reserved"><?=$objRow['time']?></li>
                                    </ul>
                                <?php } }?>
                    </section>
                </div>
            </div>
        </div>
    </div>
</div>

<link href="<?=$link;?>/application/views/css/style.css" rel="stylesheet">

<!-- jQuery (necessary for Bootstrap's JavaScript plugins) -->
<script src="https://ajax.googleapis.com/ajax/libs/jquery/1.11.3/jquery.min.js"></script>
<!-- Include all compiled plugins (below), or include individual files as needed -->
<script src="<?=$link;?>/application/views/js/bootstrap.min.js"></script>
</body>
</html>