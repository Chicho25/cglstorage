<?php
    include("translation/constants.php");
?>
<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>CRM</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/plugins/blueimp/css/blueimp-gallery.min.css" rel="stylesheet">
    <!-- FooTable -->
    <link href="css/plugins/footable/footable.core.css" rel="stylesheet">
    <link href="css/plugins/switchery/switchery.css" rel="stylesheet">
    <link href="css/plugins/datapicker/datepicker3.css" rel="stylesheet">
    <link href="css/plugins/chosen/bootstrap-chosen.css" rel="stylesheet">
    <link href="css/plugins/jasny/jasny-bootstrap.min.css" rel="stylesheet">
    <link href="css/plugins/dataTables/datatables.min.css" rel="stylesheet">
    <link href="css/plugins/clockpicker/clockpicker.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <link href="css/custom.css" rel="stylesheet">
    <style type="text/css">
        .chosen-select .search-field input[type="text"]
        {
            width: 400px !important;
        }
    </style>
    <style media="screen">
    
          .invoice{
          width:970px !important;
          margin:50px auto;
          .invoice-header{
            padding:25px 25px 15px;
            h1{
              margin:0
            }
            .media{
              .media-body{
                font-size:.9em;
                margin:0;
              }
            }
          }
          .invoice-body{
            border-radius:10px;
            padding:25px;
            background:#FFF;
          }
          .invoice-footer{
            padding:15px;
            font-size:0.9em;
            text-align:center;
            color:#999;
          }
          }
          .logo{
          max-height:70px;
          border-radius:10px;
          }
          .dl-horizontal{
          margin:0;
          dt{
                float: left;
            width: 80px;
            overflow: hidden;
            clear: left;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
          }
          dd{
            margin-left:90px;
          }
          }
          .rowamount{
          padding-top:15px !important;
          }
          .rowtotal{
          font-size:1.3em;
          }
          .colfix{
          width:12%;
          }
          .mono{
          font-family:monospace;
          }
          .button
          {
          width: 100%;
          border: 1px solid #DBE1EB;
          font-size: 18px;
          font-family: Arial, Verdana;
          padding-left: 7px;
          padding-right: 7px;
          padding-top: 5px;
          padding-bottom: 5px;
          border-radius: 4px;
          -moz-border-radius: 4px;
          -webkit-border-radius: 4px;
          -o-border-radius: 4px;
          background: #4972B5;
          background: linear-gradient(left, #4972B5, #618ACB);
          background: -moz-linear-gradient(left, #4972B5, #618ACB);
          background: -webkit-linear-gradient(left, #4972B5, #618ACB);
          background: -o-linear-gradient(left, #4972B5, #618ACB);
          color: #FFFFFF;
          }

          .button:hover
          {
          background: #365D9D;
          background: linear-gradient(left, #365D9D, #436CAD);
          background: -moz-linear-gradient(left, #365D9D, #436CAD);
          background: -webkit-linear-gradient(left, #365D9D, #436CAD);
          background: -o-linear-gradient(left, #365D9D, #436CAD);
          color: #FFFFFF;
          border-color: #FBFFAD;
          }
          .caja {
           width: 200px;
           margin: 0 auto;
          }
    </style>
</head>

<body>

    <div id="wrapper">
        <?php
        if(isset($_SESSION['USER_ID']))
          include("left-nav.php"); ?>
        <div id="page-wrapper" class="gray-bg">
            <div class="row border-bottom">
                <nav class="navbar navbar-static-top" role="navigation" style="margin-bottom: 0">
                <div class="navbar-header">
                     <a class="navbar-minimalize minimalize-styl-2 btn btn-primary " href="#"><i class="fa fa-bars"></i> </a>
                     <?php if($_SESSION['USER_COMPANY'] == 1){ ?>
                     <a class="" href="home.php"><img width="100" src="img/logo1.png"></a>
                     <?php } ?>
                    <!--<form role="search" class="navbar-form-custom" action="search_results.html">
                        <div class="form-group">
                            <input type="text" placeholder="Search for something..." class="form-control" name="top-search" id="top-search">
                        </div>
                    </form> -->
                </div>
                <ul class="nav navbar-top-links navbar-right">
                <li>
                    <a href="logout.php">
                        <i class="fa fa-sign-out"></i> <?php echo Log_out?>
                    </a>
                </li>
            </ul>
                </nav>
                </div>
