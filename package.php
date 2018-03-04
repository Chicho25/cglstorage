<?php
    ob_start();
    $packageclass="class='active'";
    $editPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";
    $company = "";
    if($loggdUType != 'Master')
      $where.=" and package.id_user = ".$_SESSION['USER_ID']." and package.id_company = ".$_SESSION['USER_COMPANY'];
      $name = "";
      if(isset($_POST['cname']) && $_POST['cname'] != "")
      {
        $where.=" and  (customer.name LIKE '%".$_POST['cname']."%' OR package.trackingno LIKE '%".$_POST['cname']."%' OR package.shipper LIKE '%".$_POST['cname']."%' OR package.created_on LIKE '%".$_POST['cname']."%'  OR package.id LIKE '%".$_POST['cname']."%' )";
        $name = $_POST['cname'];
      }
      if(isset($_POST['status']) && $_POST['status'] != "")
      {
        $where.=" and  package.stat =  ".$_POST['status'];
        $status = $_POST['status'];
      }
      else
      {
        $where.=" and  package.stat =  1";
        $status = 1;
      }
      $arrUser = GetRecords("SELECT package.*, customer.name as CName
                            from package
                            left join customer on customer.id = package.id_customer
                              $where
                             ");

?>
     <?php
      $bcName = Package_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Package_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">
                          <div class="col-sm-3 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register-package.php'"?><?php echo Package_Add?></button>
                            </span>
                          </div>
                          <div class="col-sm-3 m-b-xs pull-right">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $name?>" name="cname">
                            </div>
                          </div>
                          <div class="col-sm-3 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="radio" name="status" value="1" <?php echo $c=(isset($status) && $status == 1) ? 'checked' : ''?> > <?php echo Active?>
                              <input type="radio" name="status" value="2" <?php echo $c=(isset($status) && $status == 2) ? 'checked' : ''?> > <?php echo Invoice?>
                              <input type="radio" name="status" value="0" <?php echo $c=(isset($status) && $status == 0) ? 'checked' : ''?> > <?php echo Archived?>
                            </div>
                          </div>

                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo Package_Id?></th>
                                  <th><?php echo Package_Customer_Name?></th>
                                  <th><?php echo Package_Tracking_number?></th>
                                  <th><?php echo Package_Shipper?></th>
                                  <th><?php echo Package_Date?></th>
                                  <th><?php echo Status?></th>
                                  <th><?php echo Action?></th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP
                                $i=1;
                                foreach ($arrUser as $key => $value) {

                                  $status = ($value['stat'] == 1) ? 'Active'  : (($value['stat'] == 2 ) ? 'Invoiced' : 'In Active');
                                ?>
                              <tr>
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName']?> </td>
                                  <td class="tbdata"> <?php echo utf8_decode($value['trackingno'])?> </td>
                                  <td class="tbdata"> <?php echo $value['shipper']?> </td>
                                  <td class="tbdata"> <?php echo $value['created_on']?> </td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td>
                                  <?php if($value['stat'] != 2) : ?>
                                    <button type="button" onclick="window.location='edit-package.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button>
                                    <?php endif; ?>
                                  <?php if($value['stat'] == 2) : ?>
                                      <button type="button" onclick="window.location='view-package.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_View?></button>

                                    <?php endif; ?>
                                    <?php if($value['isEmail'] == 1) :?>
                                      <em class="m-l fa fa-envelope"></em>
                                    <?php endif; ?>
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
