<?php

    ob_start();
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";

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

          $stval = (isset($_POST['status'])) ? 1 : 0;
          $pricline = $_POST['h1'];
          $arrVal = array(
                        "id_customer" => $customer,
                        "date" => $date,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "othervalue" => $otherval,
                        "stat" => $stval
                       );


          UpdateRec("quote", "id=".$_REQUEST['id'], $arrVal);
          $nId=$_REQUEST['id'];

          if($nId > 0)
          {
              if(count($pricline) > 0)
              {
                DeleteRec("quote_detail", "id_quote=".$nId);
                foreach ($pricline as $key => $value) {
                  $expVal = explode("::::", $value);
                  //$getPackage = GetRecord("package", "id = ".$expVal[0]);
                  $arrVal = array(
                        "id_quote" => $nId,
                        "id_package" => $expVal[0],
                        "pieces" => $_POST['pieces'.$expVal[0]],
                        "price" => $_POST['price'.$expVal[0]]
                       );
                  InsertRec("quote_detail", $arrVal);
                }
              }
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Quote updated successfully</strong>
                    </div>';
          }




     }
?>
  <?php
      $arrQuote = GetRecord("quote", "id = ".$_REQUEST['id']);
      $status = ($arrQuote['stat'] == 1) ? 'checked' : '';
      $bcName = Quote_Edit;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Quote_Edit?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                          <input type="hidden" value="<?php echo $arrQuote['id']?>" name="id">
                          <?php
                                if($message !="")
                                    echo $message;
                          ?>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Quote_Customer_Name?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="customer" id="customer" required="required">
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
                                    $selRoll = (isset($arrQuote['id_customer']) && $arrQuote['id_customer'] == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>
                           <div class="form-group">
                                <label class="col-lg-4 text-right control-label font-bold"><?php echo Quote_Date?></label>
                                <div class="col-lg-4" id="data_1">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" required="" class="form-control" name="date" id="date" value="<?php echo $arrQuote['date'] ?>">
                                    </div>

                                </div>
                            </div>
                            <div class="form-group required">
                              <label class="col-lg-4 font-bold control-label"><?php echo Active_Deactive?></label>
                              <div class="col-lg-4">
                                  <input type="checkbox" class="js-switch" name="status" <?php echo $status?>>

                              </div>

                            </div>
                          <div class="form-group">
                            <label class="col-lg-4 text-right control-label font-bold"></label>
                            <div class="col-lg-4">
                              <a data-toggle="modal" class="btn btn-primary" onclick="getPackageList()"  data-target="#myModal"><?php echo Quote_NewLine?></a>
                            </div>
                            <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                            <h4 class="modal-title"><?php echo Quote_AddLine?></h4>

                                        </div>
                                        <div class="modal-body" id="packagedata">

                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th><?php echo Quote_Pieces?></th>
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
                                  <th></th>
                                  <th>L ×</th>
                                  <th>W ×</th>
                                  <th>H </th>
                                  <th></th>
                                  <th></th>
                                  <th></th>
                                </tr>
                              </thead>
                              <tbody>
                                <?php
                                $arrOppDetail = GetRecords("select package.*, quote_detail.pieces as tpieces, quote_detail.price as tprice  from quote_detail
                                                            inner join package on package.id = quote_detail.id_package
                                                             where id_quote = ".$arrQuote['id']);
                                $ptotal=0;
                                $wbtotal=0;
                                $subtotal=0;
                                $othtotal=0;
                                $gtotal = 0;
                                foreach ($arrOppDetail as $key => $value) {
                                  $hdata = $value['id']."::::".$value['tpieces']."::::".$value['widthlb'];

                                ?>

                                    <tr>
                                      <input type='hidden' name='h1[]' value='<?php echo $hdata?>'>
                                      <td><input type='text' size='5' class='form-control' name='pieces<?php echo $value['id']?>' id='pieces<?php echo $value['id']?>' value='<?php echo $value['tpieces']?>' readonly></td>
                                      <td><?php echo $value['trackingno']?></td>
                                      <td><?php echo number_format($value['custompricerate'], 2)?></td>
                                      <td><?php echo number_format($value['length'], 2)?></td>
                                      <td><?php echo number_format($value['width'], 2)?></td>
                                      <td><?php echo number_format($value['height'], 2)?></td>
                                      <td><?php echo number_format($value['weighttocollect'], 2)?></td>
                                      <td><input type='text' size='5' class='form-control' name='price<?php echo $value['id']?>' id='price<?php echo $value['id']?>' value='<?php echo number_format($value['tprice'], 2)?>'></td>
                                      <td><i onclick='rm()' class='glyphicon glyphicon-remove'></i></td>
                                    </tr>
                                <?php
                                  $ptotal+= $value['tpieces'];
                                  $wbtotal+= number_format($value['weighttocollect'], 2);
                                  $subtotal+= number_format($value['tprice'], 2);
                                  $othtotal+= number_format($value['custompricerate'], 2);
                                  $gtotal+= number_format($value['tprice'], 2);
                                }
                                ?>
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
                                  <td><?php echo Quote_Pieces?></td>
                                  <td><?php echo Quote_Weight?></td>
                                  <td><?php echo Quote_Sub_Total?></td>
                                  <td><?php echo Quote_Others?></td>
                                  <td><?php echo Quote_GTotal?></td>
                                </tr>
                                <tr>
                                  <td id="ptotal"><?php echo $ptotal?></td>
                                  <td id="wbtotal"><?php echo number_format($wbtotal, 2)?></td>
                                  <td id="subtotal"><?php echo number_format($subtotal, 2)?></td>
                                  <td id="othtotal">
                                    <input type='text' size='5' class='form-control' id='otherval' name='otherval' onblur='updateTotal()' value="<?php echo $arrQuote['othervalue']?>"></td>
                                  <td id="gtotal"><?php echo number_format($gtotal + $arrQuote['othervalue'], 2)?></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Update?></button>
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
