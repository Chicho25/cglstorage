<?php 

    ob_start();
    $membershipclass="class='active'";
    $registerMembershipclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    echo $loggdUType;
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
                        "name" => $cname,
                        "description" => $description,
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d h:i::s")
                       );

          
          $nId = InsertRec("membership", $arrVal);    

          if($nId > 0)
          {
              if(count($pricline) > 0)
              {
                foreach ($pricline as $key => $value) {
                  $expVal = explode("::::", $value);

                  $arrVal = array(
                        "id_membership" => $nId,
                        "perpund_price" => $expVal[0],
                        "initial_range" => $expVal[1],
                        "last_range" => $expVal[2]
                       );
                  InsertRec("membership_price", $arrVal);  
                }
              }
              $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Membership created successfully</strong>
                    </div>';
          }
          else
          {
            

            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Membership not created</strong>
                  </div>';
          }
        
          
        
     }
?>
  <?php 
      $bcName = Register_Membership;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Register_Membership?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" data-validate="parsley" id="frmEmployee" method="post"   enctype="multipart/form-data">
                        
                          <?php 
                                if($message !="")
                                    echo $message;
                          ?> 
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Membership_Name?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Membership_Name?>" name="cname" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold"><?php echo Membership_Description?></label>
                            <div class="col-lg-4">
                              <input type="text" class="form-control" required="" placeholder="<?php echo Membership_Description?>" name="description" data-required="true">                        
                            </div>  
                          </div>
                          <div class="form-group">
                            <label class="col-lg-4 text-right control-label font-bold"></label>
                            <div class="col-lg-4">
                              <a data-toggle="modal" class="btn btn-primary" onclick="emptyLine()"  data-target="#myModal"><?php echo Membership_NewLine?></a>
                            </div>
                            <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                <div class="modal-dialog">
                                <div class="modal-content animated bounceInRight">
                                        <div class="modal-header">
                                            <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                            <h4 class="modal-title"><?php echo Membership_AddLine?></h4>
                                            
                                        </div>
                                        <div class="modal-body">
                                            <div class="row">
                                              <div class="form-group">
                                                <label class="col-lg-3 text-right control-label"><?php echo Price_Per_Pound?></label>
                                                <div class="col-lg-7">
                                                  <input type="text" class="form-control"  name="pricperpound" id="pricperpound"  data-required="true" >
                                                </div>  
                                              </div>
                                              <div class="form-group">
                                                <label class="col-lg-3 text-right control-label"><?php echo Initial_Range?></label>
                                                <div class="col-lg-7">
                                                  <input type="text" class="form-control"  name="initialrange" id="initialrange"  data-required="true" >
                                                </div>  
                                              </div>
                                              <div class="form-group">
                                                <label class="col-lg-3 text-right control-label"><?php echo Last_Range?></label>
                                                <div class="col-lg-7">
                                                  <input type="text" class="form-control" name="lastrange" id="lastrange"  data-required="true" >
                                                </div>  
                                              </div>
                                            </div>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                            <button type="button" onclick="fillLine()" class="btn btn-primary"><?php echo Button_Save_Changes?></button>
                                        </div>
                                    </div>
                                </div>
                            </div>
                          </div>
                          <div class="table-responsive">
                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th><?php echo Price_Per_Pound?></th>
                                  <th><?php echo Initial_Range?></th>
                                  <th><?php echo Last_Range?></th>
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