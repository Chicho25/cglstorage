<?php 
  ob_start();
  session_start();
  $hideLeft = true;
  //include("include/config.php"); 
  //include("include/defs.php"); 
  //$loggdUType = current_user_type();

  include("header.php");

  // if(!isset($_SESSION['USER_ID'])) 
  //    {
  //         header("Location: index.php");
  //         exit;
  //    }
 ?>     
        
    <div class="row wrapper border-bottom white-bg page-heading">
        <div class="col-lg-10">
            <h2>E-commerce product list</h2>
            <ol class="breadcrumb">
                <li>
                    <a href="index.html">Home</a>
                </li>
                <li>
                    <a>E-commerce</a>
                </li>
                <li class="active">
                    <strong>Product list</strong>
                </li>
            </ol>
        </div>
        <div class="col-lg-2">

        </div>
    </div>
    <div class="wrapper wrapper-content animated fadeInRight ecommerce">
        <div class="row">
            <div class="col-lg-12">
                <div class="ibox">
                    <div class="ibox-content">


                    </div>
                </div>
            </div>
        </div>
    </div>
<?php include("footer.php"); ?>    