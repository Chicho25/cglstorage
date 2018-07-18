<?php
    ob_start();
    $packageclass="class='active'";
    $listimportPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";

    if(isset($datefrom) && $datefrom != "")
    {
      $where.= " and importacion_cvs.date >= '".$datefrom."'";
      $crtDatFrom =  $datefrom;
    }
    else
      $crtDatFrom =  date("Y-m-d");
    if(isset($dateto) && $dateto != "")
    {
      $where.= " and importacion_cvs.date <= '".$dateto.' 23:59:59'."'";
      $crtDatTo = $dateto;
    }
    else
      $crtDatTo = date("Y-m-d");


      $arrUser = GetRecords("SELECT *, (select count(*) from package where id_import_cvs = importacion_cvs.id) as contar
                              FROM  importacion_cvs
                              $where
                             ");

?>
     <?php
      $bcName = 'Lista de paquetes importados';
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo 'Lista de paquetes importados';?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">

                          <div class="col-sm-3 m-b-xs pull-right">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                            </div>
                          </div>
                          <div class="col-sm-2 " id="data_1">
                            <div class="input-group date">
                                <input type="text" required="" class="form-control" name="datefrom" id="datefrom" value="<?php if(isset($crtDatFrom)){ echo $crtDatFrom;} ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                          <div class="col-sm-2 " id="data_2">
                            <div class="input-group date">
                                <input type="text" required="" class="form-control" name="dateto" id="dateto" value="<?php if($crtDatTo){ echo $crtDatTo;} ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>

                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th>ID</th>
                                  <th>Nombre</th>
                                  <th>Fecha</th>
                                  <th>Cantidad</th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP
                                $i=1;
                                foreach ($arrUser as $key => $value) {
                                ?>
                              <tr>
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['name']?> </td>
                                  <td class="tbdata"> <?php echo $value['date']?> </td>
                                  <td class="tbdata"> <?php echo $value['contar']?> </td>
                              </tr>
                              <?php
                                $i++;
                              }
                              ?>
                              </tbody>
                            </table>
                        </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img').show().attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }

    document.getElementById("trakin").focus();
  </script>
<?php
  include("footer.php");
?>
