<?php

    ob_start();
    $packageclass="class='active'";
    $groupPackageEmailclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();


    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     $where = ($loggdUType != 'Master') ? " and customer.id_user = ".$_SESSION['USER_ID']." and customer.id_company = ".$_SESSION['USER_COMPANY'] : '';
     $arrKindMeetings = GetRecords("Select customer.* from customer
                                    inner join package on package.id_customer = customer.id
                                     where customer.stat=1 and package.isEmail != 1 $where
                                     group by customer.name, customer.id");
     $message="";
     $getUserEmail =GetRecord("users", "id = ".$_SESSION['USER_ID']);
    if(isset($_POST['submitUser']))
     {


          if(count($customerlist) > 0)
          {

              if(count($customerlist) == 1 && $customerlist[0] == "")
              {
                foreach ($arrKindMeetings as $key => $value)
                {
                  $customer = $value['id'];

                  if(RecCount("package", "id_customer = ".$customer." and isEmail != 1") > 0)
                  {
                    include("send-group-package-email.php");
                  }
                }
              }
              else
              {
                foreach ($customerlist as $key => $value)
                {
                  $customer = $value;
                  if(RecCount("package", "id_customer = ".$customer." and isEmail != 1") > 0)
                  {
                    include("send-group-package-email.php");
                  }
                }
              }

              $message = '<div class="alert alert-success">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Email sent</strong>
                  </div>';

          }
          else
          {

            $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Select customer</strong>
                  </div>';
          }



     }
     $getCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
?>
  <?php
      $bcName = Package_Send_Mail;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Package_Send_Mail?></h5>
                    </div>
                    <div class="ibox-content">
                  <form class="form-horizontal" data-validate="parsley" method="post">
                          <?php
                                if($message !="")
                                    echo $message;
                          ?>
                          <div class="form-group required">
                              <label class="col-lg-2 text-right control-label font-bold"><?php echo Package_Customer_Name?></label>
                              <div class="col-lg-4">

                                  <select class="chosen-select"   tabindex="4" name="customerlist[]" multiple="multiple">
                                    <option value="">All</option>
                                    <?PHP
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
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
                            <div class="col-sm-4 col-sm-offset-4">
                                <button class="btn btn-primary" name="submitUser" type="submit"><?php echo Button_Send?></button>
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
