<?php

    ob_start();
    $packageclass="class='active'";
    $importPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();


    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     $getCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
     $message="";

    $message="";
     $dataImported=array();

     if(isset($_FILES['myFile']) && $_FILES['myFile']['tmp_name'] != "")
     {
        $pathInfo = pathInfo($_FILES['myFile']['name']);

        $import_cvs = array("name" => $_FILES['myFile']['tmp_name'],
                            "date" => date("Y-m-d"),
                            "stat" => 1,
                            "id_user" => $_SESSION['USER_ID']);

        $ID_IMPORT = InsertRec("importacion_cvs", $import_cvs);

        if($pathInfo['extension'] == "csv")
        {
          $tmpName = $_FILES['myFile']['tmp_name'];
          $csvAsArray = array_map('str_getcsv', file($tmpName));
          if(count($csvAsArray) > 0)
          {
            $i=1;
              foreach ($csvAsArray as $key => $value) {

                if($i == 1)
                  {
                    $i = 2;
                    continue;
                  }
                    $arrVal = array(
                        "trackingno" => $value[0],
                        "widthlb" => $value[1],
                        "length" => $value[2],
                        "height" => $value[3],
                        "width" => $value[4],
                        "volume" => $value[5],
                        "totaltopay" => $value[6],
                        "id_user" => $_SESSION['USER_ID'],
                        "id_company" => $_SESSION['USER_COMPANY'],
                        "stat" => 1,
                        "created_on" => date("Y-m-d"),
                        "id_import_cvs" => $ID_IMPORT
                       );


                $NID = InsertRec("package", $arrVal);


                  if($NID > 0)
                  {
                    $value['ID'] = $NID;
                    $dataImported[] = $value;
                  }

              }

          }

        }
        else
        {
          $message = '<div class="alert alert-danger">
                  <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Only CSV File extention allowed</strong>
                  </div>';
        }

     }
?>
  <?php
      $bcName = Import_Package;
      include("breadcrumb.php") ;
    ?>
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Import_Package?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="form-horizontal" role="form" method="post"  enctype="multipart/form-data">
                                <?php
                                if($message !="")
                                    echo $message;
                          ?>
                                    <div class="form-body">
                                        <h3 class="form-section"><?php echo Package_Import_CSV?></h3>
                                        <div class="row">
                                            <div class="col-xs-12">
                                                <div class="form-group clearfix">
                                                   <div class="col-sm-9 ">
                                                      <div class="fileinput fileinput-new input-group" data-provides="fileinput">
                                                          <div class="form-control" data-trigger="fileinput">
                                                              <i class="glyphicon glyphicon-file fileinput-exists"></i>
                                                          <span class="fileinput-filename"></span>
                                                          </div>
                                                          <span class="input-group-addon btn btn-default btn-file">
                                                              <span class="fileinput-new"><?php echo Package_Select_file?></span>
                                                              <span class="fileinput-exists"><?php echo Button_Change?></span>
                                                              <input type="file" name="myFile"/>
                                                          </span>
                                                          <a href="#" class="input-group-addon btn btn-default fileinput-exists" data-dismiss="fileinput"><?php echo Button_Remove?></a>
                                                      </div>
                                                   </div>
                                                   <div class="col-sm-3 col-md-3 ph-sm pb-sm">
                                                      <button  class="btn btn-primary"><?php echo Button_Import?></button>
                                                   </div>
                                                   <!-- ngIf: showprogbar -->
                                                </div>
                                            </div>
                                        </div>
                                        <!--/row-->
                                    </div>

                                </form>

                                <?php if(count($dataImported) > 0) { ?>
                                <table class="table table-striped table-bordered table-hover" id="tblform">
                                          <thead>
                                              <tr>
                                                  <th><?php echo Package_Tracking_number?></th>
                                                  <th><?php echo Package_Weight?></th>
                                                  <th><?php echo Package_Length?></th>
                                                  <th><?php echo Package_Height?></th>
                                                  <th><?php echo Package_Width?></th>
                                                  <th><?php echo Package_Volume?></th>
                                                  <th><?php echo Package_Total_To_Pay?></th>


                                              </tr>
                                          </thead>
                                          <tbody>
                                              <?php

                                                foreach ($dataImported as $key => $value) {

                                                ?>
                                              <tr>
                                                  <td class="tbdata"> <?php echo $value[0]?> </td>
                                                  <td class="tbdata"> <?php echo $value[1]?> </td>
                                                  <td class="tbdata"> <?php echo $value[2]?> </td>
                                                  <td class="tbdata"> <?php echo $value[3]?> </td>
                                                  <td class="tbdata"> <?php echo $value[4]?> </td>
                                                  <td class="tbdata"> <?php echo $value[5]?> </td>
                                                  <td class="tbdata"> <?php echo $value[6]?> </td>

                                              </tr>
                                              <?php
                                              }
                                              ?>
                                          </tbody>
                                      </table>
                                   <?php } ?>
                        </div>
                      </div>
            </div>

        </div>

    </div>

<?php
  include("footer.php");
?>
