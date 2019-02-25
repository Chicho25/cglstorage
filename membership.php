<?php 
    ob_start();
    $membershipclass="class='active'";
    $editMembershipclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    include("header.php"); 

     $message = "";

     if (isset($_POST['deleteUser'])) {

      DeleteRec("membership","id =".$_POST['id_delete']);
  
      $message = '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Membresia Eliminada</strong>
                    </div>';
    }


    if(!isset($_SESSION['USER_ID'])) 
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";
    $company = "";
    if($loggdUType != 'Master')
      $where.=" and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'];
      $name = "";
      if(isset($_POST['cname']) && $_POST['cname'] != "")
      {
        $where.=" and  (membership.name LIKE '%".$_POST['cname']."%' OR company.name LIKE '%".$_POST['cname']."%' OR membership.description LIKE '%".$_POST['cname']."%'  OR membership.id LIKE '%".$_POST['cname']."%' )";
        $name = $_POST['cname'];
      }
      if(isset($_POST['status']) && $_POST['status'] != "")
      {
        $where.=" and  membership.stat =  ".$_POST['status'];
        $status = $_POST['status'];
      }
      else
      {
        $where.=" and  membership.stat =  1";
        $status = 1;
      }
      $arrUser = GetRecords("SELECT membership.*, company.name as CName
                            from membership
                            inner join company on company.id = membership.id_company  
                              $where
                             order by membership.name");
     
?>
     <?php 
      $bcName = Membership_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                    <?php echo $message; ?>
                        <h5><?php echo Membership_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">
                          <div class="col-sm-3 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register-membership.php'"?><?php echo Add_Membership?></button>
                            </span>
                          </div>
                          <div class="col-sm-3 m-b-xs pull-right">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search ?></button></span>
                            </div>  
                          </div>
                          <div class="col-sm-2 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $name?>" name="cname">
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="radio" name="status" value="1" <?php echo $c=(isset($status) && $status == 1) ? 'checked' : ''?> > <?php echo Active ?>
                              <input type="radio" name="status" value="0" <?php echo $c=(isset($status) && $status == 0) ? 'checked' : ''?> > <?php echo Archived ?>
                            </div>
                          </div>
                          
                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo Membership_Id?></th>
                                  <th><?php echo Membership_Name?></th>
                                  <th><?php echo Membership_Description?></th>
                                  <th><?php echo Membership_Company_Name?></th>
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
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['name']?> </td>
                                  <td class="tbdata"> <?php echo $value['description']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName']?> </td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td> <button type="button" onclick="window.location='edit-membership.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button> 
                                      <?php if($_SESSION['USER_ID'] == 30 || $_SESSION['USER_ID'] == 1){ ?>
                                      <button data-toggle="modal" data-target="#myModal2<?php echo $value['id']?>" class="btn btn-danger btn-info"><?php echo 'Eliminar';?></button>
                                      <?php } ?>
                                        <div class="modal inmodal" id="myModal2<?php echo $value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                          <div class="modal-dialog">
                                          <div class="modal-content animated bounceInRight">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                              <h4 class="modal-title" style="color:red;"><?php echo 'Eliminar Membresia';?></h4>
                                            </div>
                                            <form class="form-horizontal" action="" method="post">
                                            <div class="modal-body">
                                              <div class="row">
                                                <div class="form-group">
                                                  <label class="col-lg-4 text-right control-label" style="color:red;">Eliminar Membresia</label>
                                                  <div class="col-lg-6" style="color:red;">
                                                    <p>
                                                      Se recomienda que se desactive, si borra la membresia, borrara todos los datos asosiados a la misma.
                                                    </p>
                                                    <input type="hidden" name="id_delete" value="<?php echo $value['id']?>">
                                                  </div>
                                                </div>
                                              </div>
                                              </div>
                                              <div class="modal-footer">
                                              <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                              <button type="submit" class="btn btn-primary" name="deleteUser"><?php echo 'Eliminar';?></button>
                                              </div>
                                            </form>
                                        </div>
                                      </div>
                                    </div>  
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