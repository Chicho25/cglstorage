<?php 
  ob_start();
  session_start();
  $hideLeft = true;
  include("include/config.php"); 
  include("include/defs.php"); 
  $loggdUType = current_user_type();

  include("header.php");

  if(!isset($_SESSION['USER_ID'])) 
     {
          header("Location: index.php");
          exit;
     }
 ?>  

 <div class="wrapper wrapper-content animated fadeInRight">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>Bienvenido al Sistema</h5>
                    </div>
                    <div class="ibox-content">
                      <?php if($_SESSION['USER_COMPANY'] == 1){ ?>
                      <a class="" href="home.php"><img src="img/logo1.png"></a>
                      <?php } ?>
                    </div>
                </div>
        </div>
  </div>                  

<?php include("footer.php"); ?>
