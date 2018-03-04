<?php

    ob_start();
    $packageclass="class='active'";
    $editPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    if(!isset($_SESSION['USER_ID'])  || $loggdUType != 'Master')
     {
          header("Location: index.php");
          exit;
     }
     $message="";

    if(isset($_POST['submitUser']))
     {
          $stval = (isset($_POST['status'])) ? 2 : 0;
          $arrVal = array(
                        "length" => $length,
                        "width" => $width,
                        "height" => $height,
                        "variable" => $variable,
                        "volume" => $volume,
                        "widthlb" => $widthlb,
                        "weighttocollect" => ceil($weighttocollect),
                        "custompricerate" => $custompricerate,
                        "totaltopay" => $totaltopay,
                        "trackingno" => $trackingno,
                        "shipper" => $shipper,
                        "id_customer" => $customer,
                        "stat" => $stval,
                       );

          UpdateRec("package", "id=".$_REQUEST['id'], $arrVal);
          $nId=$_REQUEST['id'];

          if($nId > 0)
          {


              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Package updated successfully</strong>
                    </div>';
          }

///////////////// agregado ///////////////////

      $pricline = $_POST['precio'];
      $otherval = 0;

      $arrVal = array(
                    "id_customer" => $customer,
                    "id_user" => $_SESSION['USER_ID'],
                    "id_company" => $_SESSION['USER_COMPANY'],
                    "othervalue" => $otherval,
                    "stat" => 1,
                    "created_on" => date("Y-m-d h:i::s"),
                    "date" => date("Y-m-d h:i::s")
                   );

      $nIdQuote = InsertRec("quote", $arrVal);

      if($nIdQuote > 0)
      {
          if(count($totaltopay) > 0)
          {
              $arrVal = array(
                    "id_quote" => $nIdQuote,
                    "price" => $totaltopay,
                    "pieces" => 1,
                    "id_package" => $nId
                   );
               InsertRec("quote_detail", $arrVal);

          }

          $arrVal2 = array(
                        "id_customer" => $customer,
                        "id_quote" => $nIdQuote,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s"),
                        "receive_date" => date("Y-m-d h:i::s")
                       );

          $nId2 = InsertRec("receipt", $arrVal2);

              if(count($totaltopay) > 0)
              {
                  $arrVal = array(
                        "id_receipt" => $nId2,
                        "amount" => $totaltopay
                       );
                   InsertRec("receipt_detail", $arrVal);
              }

          //$id = $nId;
          //include("sendemail.php");
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
      $arrCompany = GetRecord("package", "id = ".$_REQUEST['id']);
      $getCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
      $compid = $arrCompany['id'];
      $status = ($arrCompany['stat'] == 1) ? 'checked' : '';
      $bcName = Package_Edit;
      $volumnVal = (isset($arrCompany['variable']) && $arrCompany['variable'] > 0) ? $arrCompany['variable'] : $getCompanyInfo['volume'];
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Package_Edit?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $arrCompany['id']?>" name="id">
                        <?php
                                if($message !="")
                                    echo $message;
                          ?>
                        <div class="form-group required">
                              <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Customer_Name?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="customer" id="customer" required="required">
                                    <option value="">---------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from customer where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      if($value['membernumber'] != "")
                                        $kinDesc = $value['name']."-".$value['membernumber'];
                                      else
                                        $kinDesc = $value['name'];
                                    $selRoll = (isset($arrCompany['id_customer']) && $arrCompany['id_customer'] == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>

                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"></label>
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold"><?php echo Package_Length?></div>

                            </div>
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold"><?php echo Package_Width?></div>
                            </div>
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold"><?php echo Package_Height?></div>
                            </div>
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold"><?php echo Package_Variable?></div>
                            </div>
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding   font-bold"><?php echo Package_Volume?></div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Dimension?></label>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['length']?>"  name="length" id="length" onblur="getVolume()" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">×</span>
                              </div>
                            </div>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['width']?>"  name="width" id="width" onblur="getVolume()" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">×</span>
                              </div>
                            </div>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['height']?>"  name="height" id="height" onblur="getVolume()" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">/</span>
                              </div>
                            </div>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" value="<?php echo $volumnVal?>"  name="variable" id="variable" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">=</span>
                              </div>
                            </div>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" value="<?php echo $arrCompany['volume']?>"  name="volume" id="volume" data-required="true">
                              </div>
                            </div>
                          </div>


                         <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Weight?></label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" onblur="getWeightToCollect()"  name="widthlb" id="widthlb" value="<?php echo $arrCompany['widthlb']?>" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">LB</span>
                              </div>
                            </div>
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Weight_To_Collect?></label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly=""  required="" name="weighttocollect" id="weighttocollect" value="<?php echo $arrCompany['weighttocollect']?>" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">LB</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Customer_Price_Rate?></label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" required="" value="<?php echo $arrCompany['custompricerate']?>"  name="custompricerate" id="custompricerate" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">$ × LB</span>
                              </div>
                            </div>
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Total_To_Pay?></label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" required="" value="<?php echo $arrCompany['totaltopay']?>"  name="totaltopay" id="totaltopay" data-required="true">
                              </div>
                              <div class="col-lg-2 no-padding">
                                <span class="font-bold">$</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Tracking_number?></label>
                            <div class="col-lg-3 ">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['trackingno']?>"  name="trackingno" data-required="true">
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Shipper?></label>
                            <div class="col-lg-3 ">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['shipper']?>"  name="shipper" data-required="true">
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 font-bold control-label"><?php echo Active_Deactive?></label>
                            <div class="col-lg-4">
                                <input type="checkbox" class="js-switch" name="status" <?php echo $status?>>

                            </div>

                          </div>
                            <div class="form-group">
                              <div class="col-sm-4 m-t-sm col-sm-offset-4">
                                  <button class="btn btn-primary" name="submitUser" id="btnPackEdit" type="submit"><?php echo Button_Update?></button>
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
