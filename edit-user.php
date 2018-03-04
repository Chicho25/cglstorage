<?php 

    ob_start();
    $userclass="class='active'";
    $userlistclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    include("header.php"); 

    if(!isset($_SESSION['USER_ID']) || $loggdUType != "Master") 
     {
          header("Location: index.php");
          exit;
     }
     $message="";
    if(isset($_POST['submitUser']) && $_REQUEST['id'] > 0)
     {
        $USERNAME = $_POST['username'];
        $FIRSTNAME = $_POST['name'];
        $LASTNAME = $_POST['lastname'];
        $password = encryptIt($_POST['password']);
        $usertype = $_POST['usertype'];
        $email = $_POST['email'];
        $stval = (isset($_POST['status'])) ? 1 : 0;
        $ifUserExist = RecCount("users", "(id_company = ".$distributor." and user = '".$USERNAME."' or Email = '".$email."') and id <> ".$_REQUEST['id']);
        if($ifUserExist > 0)
        {
          $message = '<div class="alert alert-danger">
            <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
              <strong>Username or email already exist!</strong>
            </div>';
        }
        else
        {
            $arrVal = array(
                          "user" => $USERNAME,
                          "Name" => $FIRSTNAME,
                          "Last_name" => $LASTNAME,
                          "password" => $password,
                          "Email" => $email,
                          "id_roll_user" => $usertype,
                          "id_company" => $distributor,
                           "stat" => $stval
                         );
            
          UpdateRec("users", "id=".$_REQUEST['id'], $arrVal);    
          $nId=$_REQUEST['id'];
          if($nId > 0)
          {

              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>User updated successfully</strong>
                    </div>';

          }
        }
        
        
          
        
     }

     $arrUser = GetRecord("users", "id = ".$_REQUEST['id']);
     $status = ($arrUser['stat'] == 1) ? 'checked' : '';

?>
	<?php 
      $bcName = Edit_User;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Edit_User?></h5>
                    </div>
                    <div class="ibox-content">
                	   <form class="form-horizontal" data-validate="parsley" method="post"   enctype="multipart/form-data">
                      <input type="hidden" value="<?php echo $arrUser['id']?>" name="id">
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo User_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" value="<?php echo $arrUser['user']?>" placeholder="User Name" name="username" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" placeholder="Name" value="<?php echo $arrUser['Name']?>" name="name" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Last_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" placeholder="Last Name" value="<?php echo $arrUser['Last_name']?>" name="lastname" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Password?></label>
                            <div class="col-lg-4">
                              <input type="password" class="form-control" placeholder="Password" value="<?php echo decryptIt($arrUser['password'])?>" name="password" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Email?></label>
                            <div class="col-lg-4">
                              <input type="email" class="form-control" placeholder="Email" value="<?php echo $arrUser['Email']?>" name="email" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Role?></label>
                              <div class="col-lg-4">
                                  <select class="form-control" name="usertype" id="usertype" required="required" onChange="checkCompany();"> 
                                    <?PHP
                                    $arrKindMeetings = GetRecords("Select * from type_user");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      $kinDesc = $value['name'];

                                      $selRoll = (isset($arrUser['id_roll_user']) && $arrUser['id_roll_user'] == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo  $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Distributor?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="distributor" id="distributor" required="required" onChange="checkCompany();">
                                    <?PHP
                                    $arrKindMeetings = GetRecords("Select * from company where stat = 1");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      $kinDesc = $value['name'];

                                      $selRoll = (isset($arrUser['id_company']) && $arrUser['id_company'] == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo  $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
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