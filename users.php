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
    $where = "where (1=1)";
     $name="";
     $lname="";
     $user = "";  
    $company = "";

     if(isset($_POST['name']) && $_POST['name'] != "")
     {  
        $where.=" and  users.Name LIKE '%".$_POST['name']."%'";
        $name = $_POST['name'];
     }
     if(isset($_POST['lname']) && $_POST['lname'] != "")
     {  
        $where.=" and  users.Last_name LIKE '%".$_POST['lname']."%'";
        $lname = $_POST['lname'];
     }
     if(isset($_POST['company']) && $_POST['company'] != "")
     {  
        $where.=" and  company.name LIKE '%".$_POST['company']."%'";
        $company = $_POST['company'];
     }
     if(isset($_POST['user']) && $_POST['user'] != "")
     {  
        $where.=" and  users.user LIKE '%".$_POST['user']."%'";
        $user = $_POST['user'];
     }


      $arrUser = GetRecords("SELECT users.*, type_user.name as Role, company.name as CName
                             from users
                             inner join type_user on type_user.id = users.id_roll_user 
                             inner join company on company.id = users.id_company  
                             $where
                             order by Name");
     
?>
	   <?php 
      $bcName = User_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo User_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper">
                          <div class="col-sm-2 pull-left">
                            <span class="input-group-btn ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register.php'"?><?php echo Add_User?></button>
                            </span>
                          </div>
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $name?>" name="name" placeholder="<?php echo Name?>">
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $lname?>" name="lname" placeholder="<?php echo Last_Name?>">
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $company?>" name="company" placeholder="<?php echo Company?>">
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $user?>" name="user" placeholder="<?php echo User?>">
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                            </div>  
                          </div>
                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo Name?></th>
                                  <th><?php echo Last_Name?></th>
                                  <th><?php echo Email?></th>
                                  <th><?php echo Company_Name?></th>
                                  <th><?php echo Role?></th>
                                  <th><?php echo Status?></th>
                                  <th><?php echo Action?></th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP  
                                $i=1;
                                foreach ($arrUser as $key => $value) {
                                  
                                  $status = ($value['stat'] == 1) ? 'Active' : 'In Active';
                                ?> 
                              <tr> 
                                  <td class="tbdata"> <?php echo $value['Name']?> </td>
                                  <td class="tbdata"> <?php echo $value['Last_name']?> </td>
                                  <td class="tbdata"> <?php echo $value['Email']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName']?> </td>
                                  <td class="tbdata"> <?php echo $value['Role']?> </td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td> <button type="button" onclick="window.location='edit-user.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button> 
                                  </td>
                              </tr>
                              <?php
                                $i++;
                              }
                              ?>
                              </tbody>
                            </table>
                        </div>
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