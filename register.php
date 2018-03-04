<?php 
    ob_start();
    $userclass="class='active'";
    $registerclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    
    include("header.php"); 

    if(!isset($_SESSION['USER_ID'])  || $loggdUType != "Master") 
     {
          header("Location: index.php");
          exit;
     }
     $message="";

    if(isset($_POST['submitUser']))
     {
        $USERNAME = $_POST['username'];
        $FIRSTNAME = $_POST['name'];
        $LASTNAME = $_POST['lastname'];
        $password = encryptIt($_POST['password']);
        $usertype = $_POST['usertype'];
        $email = $_POST['email'];
        
        $ifUserExist = RecCount("users", "id_company = ".$distributor." and user = '".$USERNAME."' or Email = '".$email."'");
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
                          "create_date" => date("Y-m-d H:i:s"),
                          "stat" => 1
                         );
            
          $nId = InsertRec("users", $arrVal);    

          if($nId > 0)
          {
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>User created successfully</strong>
                    </div>';
          }
        }
        
        
          
        
     }
?>
	<?php 
      $bcName = Register_User;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_User?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" method="post"   enctype="multipart/form-data">
                        <?php 
                                if($message !="")
                                    echo $message;
                          ?>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo User_Name?></label>
                              <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="User Name" name="username" required>                        
                              </div>  
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Name?></label>
                              <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Name" name="name" required>                        
                              </div>  
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Last_Name?></label>
                              <div class="col-lg-4">
                                <input type="text" class="form-control" placeholder="Last Name" name="lastname" required>                        
                              </div>  
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Password?></label>
                              <div class="col-lg-4">
                                <input type="password" class="form-control" placeholder="Password" name="password" required>                        
                              </div>  
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Email?></label>
                              <div class="col-lg-4">
                                <input type="email" class="form-control" placeholder="Email" name="email" required>                        
                              </div>  
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Role?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="usertype" id="usertype" required="required" onChange="checkCompany();">
                                    <?PHP
                                    $arrKindMeetings = GetRecords("Select * from type_user");
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
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Distributor?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="distributor" id="distributor" required="required" onChange="checkCompany();">
                                    <option value="">--------------</option>
                                    <?PHP
                                    //if($loggdUType != "Master")
                                      $arrKindMeetings = GetRecords("Select * from company where stat = 1");
                                    // else
                                    //   $arrKindMeetings = GetRecords("Select * from company where stat = 1 and name = 'CGL Storage'");

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
                        
                          <div class="form-group">
                                    <div class="col-sm-4 col-sm-offset-4">  
                                        <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Create_Account?></button>
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