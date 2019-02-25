<?php
    ob_start();
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    function conti($array_seleccion, $id_seleccion){                              
      foreach ($array_seleccion as $key => $value2) {
        if($value2 == $id_seleccion){
          return 1;
        } 
      }                             
    }

    if(isset($_POST['select_pay'])){

      foreach ($_POST['fact'] as $key => $value) {
        if($value!=0){
          UpdateRec("quote", "id = ".$value, array("stat" => 2));
          UpdateRec("package", "id in (select id_package from quote_detail
                    where id_quote = ".$value.") ", array("stat" => 3));

        $array_detail = array("id_invoice" => $value, 
                              "id_method" => $_POST['method'], 
                              "descriptions" => $_POST['descriptions'],
                              "id_user" => $_SESSION['USER_ID'], 
                              "date_time" => date("Y-m-d H:i:s"), 
                              "stat" => 1);

        $id_pay_detail = InsertRec("pay_datail_invoice",$array_detail);

        if(isset($_FILES['attach']) && $_FILES['attach']['tmp_name'] != "")
              {
                  $target_dir = "attched/";
                  $target_file = $target_dir . basename($_FILES["attach"]["name"]);
                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                  $filename = $target_dir . $id_pay_detail.".".$imageFileType;
                  $filenameThumb = $target_dir . $id_pay_detail."_thumb.".$imageFileType;
                  if (move_uploaded_file($_FILES["attach"]["tmp_name"], $filename))
                  {
                      //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $id_pay_detail, 200, 200);

                      UpdateRec("pay_datail_invoice", "id = ".$id_pay_detail, array("attched" => $filenameThumb));
                  }
              }
            }
        }
          echo "<script>alert('Quote invoiced successfully');
          window.location='quote.php';</script>";
    }

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";

      $arrUser = GetRecords("SELECT 
                              quote.othervalue, 
                              quote.stat, 
                              quote.id, 
                              quote.date, 
                              customer.name as CName, 
                              sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              $where
                              group by quote_detail.id_quote
                             ");

?>
     <?php
      $bcName = Quote_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo 'Lista Seleccionada';?></h5>
                    </div>
                    <div class="ibox-content">
                    
                        <div class="row wrapper">
                        </div>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered table-hover dataTables-example">
                             <thead>
                                <tr>
                                  <th><?php echo Quote_Id?></th>
                                  <th><?php echo Quote_Customer_Name?></th>
                                  <th><?php echo Quote_Date?></th>
                                  <th><?php echo Quote_GTotal?></th>
                                  <th><?php echo Status?></th>
                                </tr>
                              </thead>
                              <tbody>
                              <?php 
                                    $suma_total = 0;
                                    foreach ($arrUser as $key => $value) {
                                    if(conti($_POST['fact'], $value['id']) != 1){
                                      continue;
                                    }
                                ?>
                              <tr>
                                  <th><?php echo $value['id'];?></th>
                                  <th><?php echo $value['CName'];?></th>
                                  <th><?php echo $value['date'];?></th>
                                  <th><?php echo round($value['total'], 2).' $';?></th>
                                  <th><?php echo $value['stat'];?></th>
                              </tr>
                              <?php $suma_total += round($value['total'] + $value['othervalue'] , 2); ?>
                              <?php } ?>
                            </tbody>
                            <tr>
                              <td></td>
                              <td></td>
                              <td><b style="color:red;"> Total: </b></td>
                              <td><b style="color:red;"><?php echo round($suma_total, 2).' $';?></b></td>
                              <td></td>
                            </tr>
                          </table>
                        </div>
                     <div class="content">
                      <form action="" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group">
                                  <label class="col-lg-4 control-label">Metodo</label>
                                    <select name="method" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Cheque</option>
                                        <option value="3">ACH(Transferencia)</option>
                                        <option value="4">Tarjeta</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Descripcion</label>  
                                    <textarea class="form-control" name="descriptions" id="" cols="20" rows="6"></textarea>
                                  
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Adjunto</label>
                                    <input type="file" name="attach" class="form-control">
                                </div>
                                <div class="form-group">
                                      <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                      <button type="submit" class="btn btn-primary" name="select_pay"><?php echo 'Pagar Seleccion';?></button>
                                  
                                </div>
                                <?php
                                  foreach ($_POST['fact'] as $key => $value) { ?>
                                  <input type="hidden" value="<?php echo $value; ?>" name="fact[]">
                                <?php } ?>
                            </div>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
<?php
  include("footer.php");
?>
