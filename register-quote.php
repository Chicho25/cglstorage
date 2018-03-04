<?php

    ob_start();
    $quoteclass="class='active'";
    $registerQuoteclass="class='active'";

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

          $pricline = $_POST['precio'];
          $arrVal = array(
                        "id_customer" => $customer,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "othervalue" => $otherval,
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s"),
                        "date" => date("Y-m-d h:i::s")
                       );


          $nId = InsertRec("quote", $arrVal);

          if($nId > 0)
          {
              if(count($pricline) > 0)
              {
                foreach ($pricline as $key => $value) {
                  $expVal = explode("::::", $value);
                  //$getPackage = GetRecord("package", "id = ".$expVal[0]);
                  $arrVal = array(
                        "id_quote" => $nId,
                        "price" => $value,
                        "pieces" => 1,
                        "id_package" => $_POST['id'][$key]
                       );
                   InsertRec("quote_detail", $arrVal);

                }
              }

              $arrVal2 = array(
                            "id_customer" => $customer,
                            "id_quote" => $nId,
                            "id_user" => $_SESSION['USER_ID'],
                            "id_company" => $_SESSION['USER_COMPANY'],
                            "stat" => 1,
                            "created_on" => date("Y-m-d h:i::s"),
                            "receive_date" => date("Y-m-d h:i::s")
                           );

              $nId2 = InsertRec("receipt", $arrVal2);

                  if(count($pricline) > 0)
                  {
                    foreach ($pricline as $key => $value) {
                      $expVal = explode("::::", $value);
                      //$getPackage = GetRecord("package", "id = ".$expVal[0]);
                      $arrVal = array(
                            "id_receipt" => $nId2,
                            "amount" => $value
                           );
                       InsertRec("receipt_detail", $arrVal);

                    }
                  }

              $id = $nId;
              include("sendemail.php");
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Quote created successfully</strong>
                    </div>';
          }
          else
          {
            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Quote not created</strong>
                  </div>';
          }

     }
?>
  <?php
      $bcName = Register_Quote;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Quote?></h5>
                    </div>
                    <div class="ibox-content">
                          <?php
                                if($message !="")
                                    echo $message;
                          ?>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Quote_Customer_Name?></label>
                              <div class="col-lg-4">
                                <form  data-validate="parsley" method="post" enctype="multipart/form-data">
                                  <select class="chosen-select form-control" name="customer" id="customer" onchange="this.form.submit()" required="required">
                                    <option value="">-----------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from customer where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      if($value['membernumber'] != "")
                                        $kinDesc = $value['name']."-".$value['membernumber'];
                                      else
                                        $kinDesc = $value['name'];
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php if(isset($_REQUEST['customer'])){ if($kinId == $_REQUEST['customer']){ echo 'selected';}} ?>><?php echo $kinDesc?></option>
                                    <?php } ?>
                                  </select>
                                </form>
                              </div>
                          </div>
                          <div>
                            <form data-validate="parsley" method="post" enctype="multipart/form-data">
                              <?php if(isset($_REQUEST['customer'])){ ?>
                              <input type="hidden" name="customer" value="<?php echo $_REQUEST['customer'];?>">
                              <?php } ?>
                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th><?php echo Quote_Tracking_No?></th>
                                  <th><?php echo Quote_Price_Per_Pound?></th>
                                  <th colspan="3" align="center"><?php echo Quote_Dimension?></th>
                                  <th><?php echo Quote_Weight_to_Collect?></th>
                                  <th><?php echo Quote_Price?></th>
                                  <th><?php echo Action?></th>
                                </tr>
                                <tr>
                                  <th></th>
                                  <th></th>
                                  <th>L ×</th>
                                  <th>W ×</th>
                                  <th>H </th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                </tr>
                                <?php $total_pago = 0; $peso_libra = 0; ?>
                                <?php if(isset($_REQUEST['customer'])){
                                      $registro_paquete = GetRecords("Select * from package where id_customer = '".$_REQUEST['customer']."' and stat = 1");
                                      $i=0;
                                      foreach ($registro_paquete as $key => $value) { ?>
                                <tr>
                                  <th><?php echo $value['trackingno']; ?></th>
                                  <th><?php echo number_format($value['custompricerate'],2); ?></th>
                                  <th><?php echo number_format($value['length'],2); ?></th>
                                  <th><?php echo number_format($value['width'],2); ?></th>
                                  <th><?php echo number_format($value['height'],2); ?></th>
                                  <th><?php echo number_format($value['widthlb'],2); ?></th>
                                  <th><input type="text" size="5" name="precio[]" id="precio<?php echo $value['id']; ?>" onkeyup="obtenerSumaPrice();" value="<?php echo number_format($value['totaltopay'],2); ?>">
                                      <input type="hidden" size="5" name="id[]" id="precio" value="<?php echo $value['id']; ?>">
                                  </th>
                                  <th><a href="borrar_detalle.php?id=<?php echo $value['id']; ?>&id_customer=<?php echo $_REQUEST['customer']; ?>"><li class="glyphicon glyphicon-remove"></li></a></th>

                                </tr>
                              <?php $i++;
                                $total_pago += $value['totaltopay'];
                                $peso_libra += $value['widthlb'];
                                }
                              } ?>
                              </thead>
                              <tbody>
                              </tbody>
                           </table>
                         </div>
                         <div class="table-responsive">
                            <table class="table table-striped b-t b-light ">
                              <tfoot>
                                <tr>
                                  <td colspan="5" align="center">
                                    <?php echo Quote_Totals?>
                                  </td>
                                </tr>
                                <tr>
                                  <td><?php echo Quote_Weight?></td>
                                  <td><?php echo Quote_Sub_Total?></td>
                                  <td><?php echo Quote_Others?></td>
                                  <td><?php echo Quote_GTotal?></td>
                                </tr>
                                <tr>
                                  <td><?php echo number_format($peso_libra, 2); ?></td>
                                  <td>
                                    <input type='text' readonly size='5' value="<?php echo number_format($total_pago,2);?>" class='form-control'>
                                  </td>
                                  <td>
                                    <input type='text' size='5' class='form-control' name='otherval' id="otros" onkeyup="obtenerSumaPrice();">
                                  </td>
                                  <td>
                                     <input type='text' readonly size='5' value="<?php echo number_format($total_pago,2);?>" class='form-control' id='resultadoPrecio'>
                                  </td>
                                </tr>
                              </tfoot>
                            </table>
                  <script>
                      function obtenerSumaPrice()
                      {
                          document.getElementById('resultadoPrecio').value=(
                          <?php if($i==1){
                          foreach ($registro_paquete as $key => $value) {  ?>
                          parseFloat(document.getElementById('precio<?php echo $value['id']; ?>').value)
                          <?php }
                        }else{
                          foreach ($registro_paquete as $key => $value) {
                          ?>
                          +parseFloat(document.getElementById('precio<?php echo $value['id']; ?>').value)

                        <?php }
                        } ?>
                        +parseFloat(document.getElementById('otros').value)).toFixed(2);
                      }
                  </script>
                  </div>
                  <div class="form-group">
                    <div class="col-sm-4 col-sm-offset-4">
                        <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Save?></button>
                        <button class="btn btn-white" type="button" onclick="window.location='home.php'"><?php echo Button_Cancel?></button>
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
