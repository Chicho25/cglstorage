<?php 
    ob_start();
    $receiptclass="class='active'";
    $editReceiptclass="class='active'";
    
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
      $where.=" and receipt.id_user = ".$_SESSION['USER_ID']." and receipt.id_company = ".$_SESSION['USER_COMPANY'];
      $name = "";
      if(isset($_POST['cname']) && $_POST['cname'] != "")
      {
        $where.=" and  (customer.name LIKE '%".$_POST['cname']."%' OR receipt.receive_date LIKE '%".$_POST['cname']."%' OR receipt.ship LIKE '%".$_POST['cname']."%' OR receipt.receive_by LIKE '%".$_POST['cname']."%'  OR receipt.id LIKE '%".$_POST['cname']."%' )";
        $name = $_POST['cname'];
      }
      if(isset($_POST['status']) && $_POST['status'] != "")
      {
        $where.=" and  receipt.stat =  ".$_POST['status'];
        $status = $_POST['status'];
      }
      else
      {
        $where.=" and  receipt.stat =  1";
        $status = 1;
      }
      $arrUser = GetRecords("SELECT receipt.*, customer.name as CName
                            from receipt
                            inner join customer on customer.id = receipt.id_customer
                              $where                              
                             ");
     
?>
     <?php 
      $bcName = Receipt_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Receipt_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">
                          <div class="col-sm-3 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register-receipt.php'"?><?php echo Receipt_Add?></button>
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
                              <input type="radio" name="status" value="0" <?php echo $c=(isset($status) && $status == 0) ? 'checked' : ''?> > <?php echo Archived?>
                            </div>
                          </div>
                          
                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo Receipt_no?></th>
                                  <th><?php echo Receipt_Customer_Name?></th>
                                  <th><?php echo Receipt_Date?></th>
                                  <th><?php echo Receipt_Received_By?></th>
                                  <th><?php echo Receipt_Ship?></th>
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
                                  <td class="tbdata"> <?php echo $value['receipt_no']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName']?> </td>
                                  <td class="tbdata"> <?php echo $value['receive_date']?> </td>
                                  <td class="tbdata"> <?php echo $value['receive_by']?> </td>
                                  <td class="tbdata"> <?php echo $value['ship']?> </td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td> <button type="button" onclick="window.location='edit-receipt.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button> 
                                    <button type="button" onclick="window.location='print-receipt.php?id=<?php echo $value['id']?>';" class="btn green btn-purple"><?php echo Button_Report?></button> 
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