<?php

    ob_start();
    $packageclass="class='active'";
    $registerPackageclass="class='active'";

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
     $getUserEmail =GetRecord("users", "id = ".$_SESSION['USER_ID']);
    if(isset($_POST['submitUser']))
     {

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
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
                       );

          $nId = InsertRec("package", $arrVal);

          if($nId > 0)
          {
              $id = $nId;
              //include("send-package-email.php");
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Package created successfully</strong>
                    </div>';
          }
          else
          {


            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Package not created</strong>
                  </div>';
          }



     }
     $getCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);

     if(isset($_POST['submitCustomers']))
      {

           $arrVal = array(
                         "name" => $cname,
                         "phone" => $phone,
                         "cellno" => $phone,
                         "email" => $email,
                         "id_membership" => $membership,
                         "id_user" => $_SESSION['USER_ID'],
                         "id_company" => $_SESSION['USER_COMPANY'],
                         "stat" => 1,
                         "created_on" => date("Y-m-d h:i::s")
                        );

           $nId = InsertRec("customer", $arrVal);

           if($nId > 0)
           {

               $message = '<div class="alert alert-success">
                     <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                       <strong>Cliente Creado</strong>
                     </div>';
           }
         }
?>
  <?php
      $bcName = Register_Package;
      include("breadcrumb.php") ;
    ?>
	<div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Package?></h5>
                    </div>
                    <div class="ibox-content">
                	<form class="form-horizontal" data-validate="parsley" method="post"   enctype="multipart/form-data">
                          <?php
                                if($message !="")
                                    echo $message;
                          ?>
                          <div class="form-group required">
                              <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Customer_Name?></label>
                              <div class="col-sm-4">
                                  <select class="chosen-select form-control" name="customer" id="customer" required="required">
                                    <option value="">---------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from customer where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      if($value['membernumber'] != "")
                                        $kinDesc = $value['name']." - ".$value['membernumber'];
                                      else
                                        $kinDesc = $value['name'];
                                    ?>
                                    <option value="<?php echo $kinId?>"><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>

                              </div>
                              <div class="col-sm-2 no-padding">
                                <div class="col-sm-10">
                                  <a data-toggle="modal" class="btn btn-primary" onclick="emptyLine()"  data-target="#myModal">Agregar Cliente</a>
                                </div>
                              </div>
                          </div>

                          <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"></label>
                            <div class="col-sm-2 ">
                              <div class="col-sm-12 text-left no-padding  font-bold"><?php echo Package_Length?></div>

                            </div>
                            <div class="col-sm-2 ">
                              <div class="col-sm-12 text-left no-padding  font-bold"><?php echo Package_Width?></div>
                            </div>
                            <div class="col-sm-2 ">
                              <div class="col-sm-12 text-left no-padding  font-bold"><?php echo Package_Height?></div>
                            </div>
                            <div class="col-sm-2 ">
                              <div class="col-sm-12 text-left no-padding  font-bold"><?php echo Package_Variable?></div>
                            </div>
                            <div class="col-sm-2 ">
                              <div class="col-sm-12 text-left no-padding   font-bold"><?php echo Package_Volume?></div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Dimension?></label>
                            <div class="col-sm-2 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" required=""  name="length" onblur="getVolume()" id="length" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">×</span>
                              </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" required=""  name="width" onblur="getVolume()" id="width" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">×</span>
                              </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" required=""  name="height" onblur="getVolume()" id="height" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">/</span>
                              </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" readonly="" value="<?php echo $getCompanyInfo['volume']?>"  name="variable" id="variable" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">=</span>
                              </div>
                            </div>
                            <div class="col-sm-2 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" readonly=""  name="volume" id="volume" data-required="true">
                              </div>
                            </div>
                          </div>


                         <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Weight?></label>
                            <div class="col-sm-3 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" required="" onblur="getWeightToCollect()"  name="widthlb" id="widthlb" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">LB</span>
                              </div>
                            </div>
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Weight_To_Collect?></label>
                            <div class="col-sm-3 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" required="" readonly=""  name="weighttocollect" id="weighttocollect" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">LB</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Customer_Price_Rate?></label>
                            <div class="col-sm-3 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" readonly="" required=""  name="custompricerate" id="custompricerate" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">$ × LB</span>
                              </div>
                            </div>
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Total_To_Pay?></label>
                            <div class="col-sm-3 no-padding">
                              <div class="col-sm-10">
                                <input type="text" class="form-control" readonly=""  name="totaltopay" id="totaltopay" data-required="true">
                              </div>
                              <div class="col-sm-2 no-padding">
                                <span class="font-bold">$</span>
                              </div>
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo Package_Tracking_number?></label>
                            <div class="col-sm-3 ">
                                <input type="text" class="form-control" required=""  name="trackingno" data-required="true">
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-sm-2 text-right control-label font-bold"><?php echo 'Cliente';?></label>
                            <div class="col-sm-3 ">
                                <input type="text" class="form-control" required=""  name="shipper" data-required="true">
                            </div>
                          </div>
                          <div class="form-group">
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-primary" name="submitUser" id="btnPackEdit" type="submit"><?php echo Button_Save?></button>
                                <button class="btn btn-white" type="button" onclick="window.location='home.php'"><?php echo Button_Cancel?></button>
                            </div>
                          </div>
                    </form>

                      <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                          <div class="modal-dialog">
                          <div class="modal-content animated bounceInRight">
                                  <div class="modal-header">
                                      <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                      <h4 class="modal-title"><?php echo Membership_AddLine?></h4>

                                  </div>
                                  <form class="form-horizontal" action="" method="post">
                                  <div class="modal-body">
                                      <div class="row">
                                        <div class="form-group">
                                          <label class="col-lg-3 text-right control-label">Nombre</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control"  name="cname" id="pricperpound"  data-required="true" autocomplete="off">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 text-right control-label">Telefono</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control"  name="phone" id="initialrange"  data-required="true" autocomplete="off">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 text-right control-label">Correo</label>
                                          <div class="col-lg-7">
                                            <input type="text" class="form-control" name="email" id="lastrange"  data-required="true" autocomplete="off">
                                          </div>
                                        </div>
                                        <div class="form-group">
                                          <label class="col-lg-3 text-right control-label">Membrecia</label>
                                          <div class="col-lg-7">
                                            <select class="chosen-select form-control" name="membership" id="customer" required="required">
                                              <option value="">---------</option>
                                              <?PHP
                                              $arrKindMeetings = GetRecords("Select * from membership where stat=1");
                                              foreach ($arrKindMeetings as $key => $value) { ?>
                                              <option value="<?php echo $value['id']; ?>"><?php echo $value['description'];?></option>
                                              <?php
                                              }
                                              ?>
                                            </select>
                                          </div>
                                        </div>
                                      </div>
                                  </div>
                                  <div class="modal-footer">
                                      <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                      <button type="submit" class="btn btn-primary" name="submitCustomers"><?php echo Button_Save_Changes?></button>
                                  </div>
                                </form>
                              </div>
                          </div>
                      </div>

                  </div>
                </div>
            </div>
        </div>
    </div>

<?php
	include("footer.php");
?>
