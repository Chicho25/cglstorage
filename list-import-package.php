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

    if (isset($_POST['amount_pay'], $_POST['id_import'])) {
      $array_monto = array("amount" => ($_POST['amount_pay']+$_POST['amount_current']));
      UpdateRec("importacion_cvs", "id=".$_POST['id_import'], $array_monto);
      $obtener_amount = GetRecords("select * from importacion_cvs where id='".$_POST['id_import']."'");
      if ($obtener_amount[0]['amount'] >= $_POST['amount_current']) {
        $stat = array("stat"=>2);
        UpdateRec("importacion_cvs", "id=".$_POST['id_import'], $stat);
      }
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


      $arrUser = GetRecords("SELECT *, (select count(*) from package where id_import_cvs = importacion_cvs.id) as contar,
                                       (select sum(totaltopay) from package where id_import_cvs = importacion_cvs.id) as suma
                              FROM  importacion_cvs
                              $where");

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
                                  <th>Total a pagar</th>
                                  <th>Pagado</th>
                                  <th>Status</th>
                                  <th>Ver Paquetes</th>
                                  <th>Pagar</th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP
                                $i=1;
                                $total_deuda = 0;
                                $total_abonado = 0;
                                foreach ($arrUser as $key => $value) {
                                ?>
                              <tr>
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['name']?> </td>
                                  <td class="tbdata"> <?php echo $value['date']?> </td>
                                  <td class="tbdata"> <?php echo $value['contar']?> </td>
                                  <td class="tbdata"> <?php echo number_format($value['suma'], 2);?> </td>
                                  <td class="tbdata"> <?php echo $value['amount']?> </td>
                                  <td class="tbdata"> <?php if ($value['stat']==1){ echo 'Pendiente por pagar'; }else{ echo 'Pagado'; } ?> </td>
                                  <td class="tbdata"><a href="list-import-package-list.php?id_import_cvs=<?php echo $value['id']?>" class="btn btn-success btn-rounded"><?php echo 'Ver';?></a></td>
                                  <td class="tbdata"><!--<button data-toggle="modal" data-target="#myModal" class="btn btn-success btn-rounded"><?php echo 'Pagar';?></button>-->

                                    <div class="modal inmodal" id="myModal" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content animated bounceInRight">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                                    <h4 class="modal-title"><?php echo 'Pago a Proveedor';?></h4>
                                                </div>
                                                <form class="form-horizontal" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="row">
                                                      <div class="form-group">
                                                        <label class="col-lg-3 text-right control-label">Monto a pagar</label>
                                                        <div class="col-lg-7">
                                                          <input type="text" class="form-control" name="amount_pay" id="pricperpound" data-required="true" autocomplete="off">
                                                          <input type="hidden" name="id_import" value="<?php echo $value['id']?>">
                                                          <input type="hidden" name="amount_current" value="<?php echo number_format($value['suma'], 2)?>">
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

                                  </td>
                              </tr>
                              <?php
                                $i++;
                                $total_deuda += $value['suma'];
                                $total_abonado += $value['amount'];
                              }
                              ?>
                              <tr>
                                <td colspan="4" style="text-align:right;">Total a Pagar</td>
                                <td><?php echo number_format(($total_deuda - $total_abonado), 2); ?></td>
                              </tr>
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
