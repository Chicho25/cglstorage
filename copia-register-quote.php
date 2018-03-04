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

          $pricline = $_POST['h1'];
          $arrVal = array(
                        "id_customer" => $customer,
                        "date" => $date,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "othervalue" => $otherval,
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
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
                        "id_package" => $expVal[0],
                        "pieces" => $_POST['pieces'.$expVal[0]],
                        "price" => $_POST['price'.$expVal[0]]
                       );
                  InsertRec("quote_detail", $arrVal);

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
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">

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
                                        $kinDesc = $value['membernumber']."-".$value['name'];
                                      else
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
                                <label class="col-lg-4 text-right control-label font-bold"><?php echo Quote_Date?></label>
                                <div class="col-lg-4" id="data_1">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" required="" class="form-control" name="date" id="date" value="<?php echo date("Y-m-d") ?>">
                                    </div>

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
                                  <td id="ptotal"></td>
                                  <td id="wbtotal"></td>
                                  <td id="subtotal"></td>
                                  <td>
                                    <input type='text' size='5' class='form-control' id='otherval' name='otherval' onblur='updateTotal()'>
                                  </td>
                                  <td id="gtotal"></td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Save?></button>
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
