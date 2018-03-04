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
          $stval = (isset($_POST['status'])) ? 1 : 0;
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
        
          
        
     }
?>
  <?php 
      $arrCompany = GetRecord("package", "id = ".$_REQUEST['id']);
      $compid = $arrCompany['id'];
      $status = ($arrCompany['stat'] == 1) ? 'checked' : '';
      $bcName = "View Package";
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5>View Package</h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        <input type="hidden" value="<?php echo $arrCompany['id']?>" name="id">
                        <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                        <div class="form-group required">
                              <label class="col-lg-2 text-right control-label font-bold">Customer</label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="customer" id="customer" required="required" disabled="">
                                    <option value="">---------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from customer where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      if($value['membernumber'] != "")
                                        $kinDesc = $value['membernumber']."-".$value['name'];
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
                              <div class="col-lg-12 text-left no-padding  font-bold">Length (Inches)</div>
                              
                            </div> 
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold">Width (Inches)</div>
                            </div>  
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold">Height (Inches)</div>
                            </div> 
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding  font-bold">Variable</div>
                            </div>  
                            <div class="col-lg-2 ">
                              <div class="col-lg-12 text-left no-padding   font-bold">Volume</div>
                            </div> 
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold">Dimension</label>
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['length']?>"  name="length" id="length" onblur="getVolume()" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">×</span>
                              </div>  
                            </div> 
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['width']?>"  name="width" id="width" onblur="getVolume()" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">×</span>
                              </div>  
                            </div>  
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['height']?>"  name="height" id="height" onblur="getVolume()" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">/</span>
                              </div>  
                            </div> 
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" value="<?php echo $arrCompany['variable']?>"  name="variable" id="variable" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">=</span>
                              </div>  
                            </div>  
                            <div class="col-lg-2 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" value="<?php echo $arrCompany['volume']?>"  name="volume" id="volume" data-required="true" readonly="">
                              </div>
                            </div> 
                          </div>
                          
                          
                         <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold">Weight</label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" required="" onblur="getWeightToCollect()"  name="widthlb" id="widthlb" value="<?php echo $arrCompany['widthlb']?>" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">LB</span>
                              </div>  
                            </div>
                            <label class="col-lg-2 text-right control-label font-bold">Weight to Collect</label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly=""  required="" name="weighttocollect" id="weighttocollect" value="<?php echo $arrCompany['weighttocollect']?>" data-required="true" readonly="">
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">LB</span>
                              </div>  
                            </div> 
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold">Customer Price Rate</label>
                            <div class="col-lg-3 no-padding">
                              <div class="col-lg-10">
                                <input type="text" class="form-control" readonly="" required="" value="<?php echo $arrCompany['custompricerate']?>"  name="custompricerate" id="custompricerate" data-required="true" >
                              </div>
                              <div class="col-lg-2 no-padding">  
                                <span class="font-bold">$ × LB</span>
                              </div>  
                            </div>
                            <label class="col-lg-2 text-right control-label font-bold">Total to Pay</label>
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
                            <label class="col-lg-2 text-right control-label font-bold">Tracking number</label>
                            <div class="col-lg-3 ">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['trackingno']?>"  name="trackingno" data-required="true" readonly="">
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-2 text-right control-label font-bold">Shipper</label>
                            <div class="col-lg-3 ">
                                <input type="text" class="form-control" required="" value="<?php echo $arrCompany['shipper']?>"  name="shipper" data-required="true" readonly="">
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