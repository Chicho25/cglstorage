<?php 

    ob_start();
    $countryclass="class='active'";
    $editCstmclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    include("header.php"); 

    if(!isset($_SESSION['USER_ID']) || $loggdUType == "User") 
     {
          header("Location: index.php");
          exit;
     }
     $message="";
    if(isset($_POST['submitUser']) && $_REQUEST['id'] > 0)
     {
        
          $stval = (isset($_POST['status'])) ? 1 : 0;
          $arrVal = array(
                        "name" => $cname,
                        "phone" => $phone,
                        "cellno" => $cellno,
                        "address" => $address,
                        "email" => $email,
                        "membernumber" => $membernumber,
                        "contact" => $contact,
                        "id_membership" => $membership,
                        "stat" => $stval
                       );
          
          UpdateRec("customer", "id=".$_REQUEST['id'], $arrVal);    
          $nId=$_REQUEST['id'];
          if($nId > 0)
          {
              
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Customer updated successfully</strong>
                    </div>';
          }
          
          
        
     }

     $arrUser = GetRecord("customer", "id = ".$_REQUEST['id']);
     $status = ($arrUser['stat'] == 1) ? 'checked' : '';

?>
  <?php 
      $bcName = Edit_Customer;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Edit_Customer?></h5>
                    </div>
                    <div class="ibox-content">
                     <form class="form-horizontal" data-validate="parsley" method="post"   enctype="multipart/form-data">
                      <input type="hidden" value="<?php echo $arrUser['id']?>" name="id">
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" value="<?php echo $arrUser['name']?>" required="" placeholder="<?php echo Customer_Name?>" name="cname" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Membership?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="membership" required="required" onChange="mostrar(this.value);">
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from membership where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      $kinDesc = $value['name'];
                                      $selRoll = (isset($arrUser['id_membership']) && $arrUser['id_membership'] == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Address?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrUser['address']?>" placeholder="<?php echo Customer_Address?>" name="address" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Phone?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" value="<?php echo $arrUser['phone']?>" required="" placeholder="<?php echo Customer_Phone?>" name="phone" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Mobile?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrUser['cellno']?>" placeholder="<?php echo Customer_Mobile?>" name="cellno" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Email?></label>
                            <div class="col-lg-4">
                              <input type="email" class="form-control" required="" value="<?php echo $arrUser['email']?>" placeholder="<?php echo Customer_Email?>" name="email" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Contact_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrUser['contact']?>" placeholder="<?php echo Customer_Contact_Name?>" name="contact" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Member_Number?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" value="<?php echo $arrUser['membernumber']?>" placeholder="<?php echo Customer_Member_Number?>" name="membernumber" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 font-bold control-label"><?php echo Active_Deactive?></label>
                            <div class="col-lg-4">
                                <input type="checkbox" class="js-switch" name="status" <?php echo $status?>>
                                
                            </div>

                          </div>  

                          <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-4">  
                                        <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Update?></button>
                                        <button class="btn btn-white" type="button" onclick="window.location='home.php'"><?php echo Button_Cancel?></button>
                                    </div>
                          </div>
                    </form>
                  </div>
                </div>
            </div>
        </div>    
    </div>
    <script type="text/javascript">
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img').show().attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
  </script>
<?php    
  include("footer.php"); 
?> 