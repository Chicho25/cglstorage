<?php 

    ob_start();
    $countryclass="class='active'";
    $registerCstmclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    
    include("header.php"); 

    if(!isset($_SESSION['USER_ID'])) 
     {
          header("Location: index.php");
          exit;
     }
     $message="";

    if(isset($_POST['submitUser']))
     {       
        
          $arrVal = array(
                        "name" => $cname,
                        "cellno" => $cellno,
                        "address" => $address,
                        "email" => $email,
                        "contact" => $contact,
                        "membernumber" => $membernumber,
                        "id_membership" => $membership,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
                       );

          $nId = InsertRec("customer", $arrVal);    

          if($nId > 0)
          {
              
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Customer created successfully</strong>
                    </div>';
          }
          else
          {
            

            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Customer not created</strong>
                  </div>';
          }
        
          
        
     }
?>
  <?php 
      $bcName = Register_Customer;
      include("breadcrumb.php") ;
    ?>
	<div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Customer?></h5>
                    </div>
                    <div class="ibox-content">
                	<form class="form-horizontal" data-validate="parsley" method="post"   enctype="multipart/form-data">
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Name?>" name="cname" data-required="true">                        
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
                                    ?>
                                    <option value="<?php echo $kinId?>"><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Address?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Address?>" name="address" data-required="true">                        
                            </div>  
                          </div>
                          <?php /*
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Phone?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Phone?>" name="phone" data-required="true">                        
                            </div>  
                          </div>
                          */ ?>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Mobile?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Mobile?>" name="cellno" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Email?></label>
                            <div class="col-lg-4">
                              <input type="email" class="form-control" placeholder="<?php echo Customer_Email?>" name="email">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Contact_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Contact_Name?>" name="contact" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Customer_Member_Number?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Customer_Member_Number?>" name="membernumber" data-required="true">                        
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