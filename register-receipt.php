<?php 

    ob_start();
    $receiptclass="class='active'";
    $registerReceiptclass="class='active'";
    
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
                        /*"id_quote" => $quote, */
                        "receipt_no" => getMaxReceiptNo(), 
                        "receive_date" => $date,
                        "ship" => $ship,
                        "receive_by" => $receivedBy,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
                       );

          
          $nId = InsertRec("receipt", $arrVal);    

          if($nId > 0)
          {
              if(count($recptAmount) > 0)
              {
                foreach ($recptAmount as $key => $value) {
                  
                  $arrVal = array(
                        "id_receipt" => $nId,
                        "description" => $_POST['description'][$key],
                        "amount" => $value
                       );
                  InsertRec("receipt_detail", $arrVal);
                  
                }
              }

              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Receipt created successfully</strong>
                    </div>';
          }
          else
          {
            

            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Receipt not created</strong>
                  </div>';
          }
        
          
        
     }
?>
  <?php 
      $bcName = Register_Receipt;
      include("breadcrumb.php") ;
      $getReceiptNo = getMaxReceiptNo();
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Receipt?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_Customer_Name?></label>
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
                          <?php /* ?>
                          <div class="form-group required">
                              <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_Quote?></label>
                              <div class="col-lg-4">
                                  <select class="chosen-select form-control" name="quote" id="quote" >
                                    <option value="">-----------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    
                                    $arrKindMeetings = GetRecords("Select quote.id, quote.date, sum(quote_detail.price) as totprice from quote 
                                        inner join quote_detail on quote_detail.id_quote = quote.id
                                        where quote.stat=2 and quote.id NOT IN (Select id_quote from receipt) $where
                                        group by quote_detail.id_quote");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      $kinDesc = $value['id']." - ".$value['date']." - ".number_format($value['totprice'], 2);
                                    ?>
                                    <option value="<?php echo $kinId?>"><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select>
                              </div>
                          </div>
                          <?php */ ?>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_no?></label>
                            <div class="col-lg-4 ">
                                <input type="text" class="form-control" required="" readonly="" value="<?php echo $getReceiptNo?>"  name="receiptno">
                            </div>
                          </div>
                           <div class="form-group">
                                <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_Date?></label>
                                <div class="col-lg-4" id="data_1">
                                    <div class="input-group date">
                                        <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                                        <input type="text" required="" class="form-control" name="date" id="date" value="<?php echo date("Y-m-d") ?>">
                                    </div>
                                  
                                </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_Ship?></label>
                            <div class="col-lg-4 ">
                                <input type="text" class="form-control" name="ship" >
                            </div>
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Receipt_Received_By?></label>
                            <div class="col-lg-4 ">
                                <input type="text" class="form-control" name="receivedBy" >
                            </div>
                          </div>
                          <div class="form-group">
                            <label class="col-lg-4 text-right control-label font-bold"></label>
                            <div class="col-lg-4">
                              <a data-toggle="modal" class="btn btn-primary" onclick="addReceiptRow()"  ><?php echo Receipt_Add_Row?></a>
                            </div>
                            
                          </div>
                          <div class="table-responsive">
                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th><?php echo Receipt_Detail?></th>
                                  <th><?php echo Receipt_Amount?></th>
                                  <th><?php echo Action?></th>
                                </tr>
                              </thead>
                              <tbody>
                              </tbody>
                              
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
