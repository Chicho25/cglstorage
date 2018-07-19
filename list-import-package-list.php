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
    $where = "where package.id_import_cvs =".$_GET['id_import_cvs'];

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

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo Package_Id?></th>
                                  <th><?php echo Package_Customer_Name?></th>
                                  <th><?php echo Package_Tracking_number?></th>
                                  <th><?php echo 'Sub Cliente'?></th>
                                  <th><?php echo Package_Date?></th>
                                  <th><?php echo Status?></th>
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

    document.getElementById("trakin").focus();
  </script>
<?php
  include("footer.php");
?>
