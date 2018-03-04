<?php 

    ob_start();
    $companyclass="class='active'";
    $editCompanyclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    
    include("header.php"); 

    if(!isset($_SESSION['USER_ID'])  || $loggdUType != 'Master') 
     {
          header("Location: index.php");
          exit;
     }
     $message="";

    if(isset($_POST['submitUser']))
     {       
          $stval = (isset($_POST['status'])) ? 1 : 0;
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
                        "stat" => $stval,
                       );

          // echo "<pre>";
          // print_r($arrVal);
          // exit;  
          UpdateRec("company", "id=".$_REQUEST['id'], $arrVal);    
          $nId=$_REQUEST['id'];  

          if($nId > 0)
          {
              

              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Company updated successfully</strong>
                    </div>';
          }
        
          
        
     }
?>
  <?php 
      $arrCompany = GetRecord("company", "id = ".$_REQUEST['id']);
      $compid = $arrCompany['id'];
      $status = ($arrCompany['stat'] == 1) ? 'checked' : '';
      $bcName = Edit_Company;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Edit_Company?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $arrCompany['id']?>" name="id">
                        <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                        <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Company_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['name']?>" placeholder="Company Name" name="cname" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Company_Email?></label>
                            <div class="col-lg-4">
                              <input type="email" class="form-control" required="" value="<?php echo $arrCompany['email']?>" placeholder="Email" name="email" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Cost_by_air?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['costbyair']?>" placeholder="Cost by air" name="costbyair" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Cost_by_sea?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['costbysea']?>" placeholder="Cost by sea" name="costbysea" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Volumn?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['volume']?>" placeholder="volume" name="volume" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Address?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['address']?>" placeholder="Address" name="address" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Phone?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['phone']?>" placeholder="Phone" name="phone" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo RUC?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['ruc']?>" placeholder="RUC" name="ruc" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo DV?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrCompany['dv']?>" placeholder="DV" name="dv" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 font-bold control-label"><?php echo Active_Deactive?></label>
                            <div class="col-lg-4">
                                <input type="checkbox" class="js-switch" name="status" <?php echo $status?>>
                                
                            </div>

                          </div>  
                            <div class="form-group">
                              <div class="col-sm-4 m-t-sm col-sm-offset-4">
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