<?php 

    ob_start();
    $companyclass="class='active'";
    $registerCompanyclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    echo $loggdUType;
    include("header.php"); 

    if(!isset($_SESSION['USER_ID']) || $loggdUType != 'Master') 
     {
          header("Location: index.php");
          exit;
     }
     $message="";

    if(isset($_POST['submitUser']))
     {       

          $arrVal = array(
                        "name" => $cname,
                        "email" => $email,
                        "costbyair" => $costbyair,
                        "costbysea" => $costbysea,
                        "volume" => $volume,
                        "address" => $address,
                        "phone" => $phone,
                        "ruc" => $ruc,
                        "dv" => $dv,
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
                       );

          // echo "<pre>";
          // print_r($arrVal);
          // exit;  
          $nId = InsertRec("company", $arrVal);    

          if($nId > 0)
          {

              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Company created successfully</strong>
                    </div>';
          }
          else
          {
            

            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Company not created</strong>
                  </div>';
          }
        
          
        
     }
?>
  <?php 
      $bcName = Register_Company;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Company?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Company_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="Company Name" name="cname" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Company_Email?></label>
                            <div class="col-lg-4">
                              <input type="email" class="form-control" required="" placeholder="Email" name="email" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Cost_by_air?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="Cost by air" name="costbyair" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Cost_by_sea?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="Cost by sea" name="costbysea" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Volume?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="volume" name="volume" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Address?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="Address" name="address" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Phone?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="Phone" name="phone" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo RUC?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="RUC" name="ruc" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo DV?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="DV" name="dv" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Save?></button>
                                <button class="btn btn-white" type="button" onclick="window.location='home.php'"><?php echo Button_Cancel?></button>
                            </div>
                          </div>
                    
                      </form>
                        </div>
                      </div>      
            </div>
            
        </div>    

    </div>
    
<?php    
  include("footer.php"); 
?> 