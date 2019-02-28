<?php

    ob_start();
    $packageclass="class='active'";
    $importPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    function comprobar($numero_track){
        $comprbar = GetRecords("SELECT count(*) as contar, trackingno FROM package WHERE trackingno ='".$numero_track."'");
        foreach ($comprbar as $key => $value) {
          $contar = $value['contar'];
          $n_track = $value['trackingno'];

          return $track = array("contar" => $contar, "n_track" => $n_track);
        }
    }


    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     $getCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
     $message="";

    //$message="";
    // $dataImported=array();

     if(isset($_POST['n_invoice']) && $_POST['n_invoice'] != "")
     {
        //$pathInfo = pathInfo($_FILES['myFile']['name']);

        if (isset($_POST['n_traking'])) {
            $comprobar = 0;
        $cadena = $_POST['n_traking'];
        $explodiado = explode(" ", $cadena);
        $i = 0;
        foreach ($explodiado as $key => $value) {
              $array_validar = comprobar($value);
              if ($array_validar['contar'] != 0) {
                  $comprobar = 1;
                  $numero_track = $array_validar['n_track'];
                  break;

              }
          }
        }
        if ($comprobar == 1) {
          $message = '<div class="alert alert-danger">
                        <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                        <strong>Este tracking ya se encuentra en la base de datos '.$numero_track.'</strong>
                      </div>';
        }else{



        //$array_validar = comprobar($numero_track);

        $import_cvs = array("name" => $_POST['n_invoice'],
                            "date" => date("Y-m-d"),
                            "stat" => 1,
                            "id_user" => $_SESSION['USER_ID'],
                            "descrition" => $_POST['description'],
                            "peso" => $_POST['peso'],
                            "amount" => $_POST['amount']);

        $ID_IMPORT = InsertRec("importacion_cvs", $import_cvs);

        if (isset($_POST['n_traking'])) {

        $cadena = $_POST['n_traking'];
        $explodiado = explode(" ", $cadena);
        $i = 0;
        foreach ($explodiado as $key => $value) {
          $i++;

          $arrVal = array(
              "trackingno" => $value,
              "number" => $i,
              "widthlb" => 0,
              "length" => 0,
              "height" => 0,
              "width" => 0,
              "volume" => 0,
              "totaltopay" => 0,
              "id_user" => $_SESSION['USER_ID'],
              "id_company" => $_SESSION['USER_COMPANY'],
              "stat" => 1,
              "created_on" => date("Y-m-d H:i:s"),
              "id_import_cvs" => $ID_IMPORT
             );

             $NID = InsertRec("package", $arrVal);

          }
        }

    if (isset($NID)) {

      $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                    <strong>Registro Realizado con Exito</strong>
                  </div>';

    }

        /*if($pathInfo['extension'] == "csv")
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
                        "widthlb" => 0,
                        "length" => 0,
                        "height" => 0,
                        "width" => 0,
                        "volume" => 0,
                        "totaltopay" => 0,
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
        }*/
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
              <h5><?php echo 'Importar Paquetes';?></h5>
          </div>
          <div class="ibox-content">
            <form class="form-horizontal" role="form" method="post"  enctype="multipart/form-data">
                      <?php
                      if($message !="")
                          echo $message;
                ?>
                          <div class="form-body">
                              <h3 class="form-section"><?php echo 'Registrar';?></h3>
                              <div class="row">
                                  <div class="col-xs-12">
                                      <div class="form-group clearfix">
                                         <div class="col-sm-9 ">
                                            <div class="form-group">
                                              <input type="text" autocomplete="off" required placeholder="Numero de Factura" name="n_invoice" class="form-control">
                                            </div>
                                            <div class="form-group">
                                              <input type="text" autocomplete="off" placeholder="Peso" name="peso" class="form-control">
                                            </div>
                                            <div class="form-group">
                                              <input type="text" autocomplete="off" required placeholder="Monto" name="amount" class="form-control">
                                            </div>
                                            <div class="form-group">
                                              <textarea placeholder="Descripcion" name="description" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                              <textarea rows="8" cols="80" required placeholder="Traking" name="n_traking" class="form-control"></textarea>
                                            </div>
                                            <div class="form-group">
                                               <button  class="btn btn-primary">Registrar</button>
                                            </div>
                                         </div>
                                         <!-- ngIf: showprogbar -->
                                      </div>
                                  </div>
                              </div>
                              <!--/row-->
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
