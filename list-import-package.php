<?php
    ob_start();
    $packageclass="class='active'";
    $listimportPackageclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");
    $message = "";

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     // Delete Record
     if (isset($_POST['deleteList'])) {
       DeleteRec("package","id_import_cvs =".$_POST['id_delete']);
       DeleteRec("importacion_cvs", "id=".$_POST['id_delete']);

       $message = '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Lista de paquetes borrada con exito</strong>
                    </div>';
     }

    if (isset($_POST['amount_pay'], $_POST['id_import'])) {
    
      $arrVal = array("id_list" =>$_POST['id_import'], 
                      "amount" =>$_POST['amount_pay'], 
                      "stat" => 1);

      $insert = InsertRec("pay_provider", $arrVal);

      if(isset($insert)){
        $message = '<div class="alert alert-success">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Pago realizado</strong>
                    </div>';
      }
      
      $obtener_amount = GetRecords("select * from importacion_cvs where id='".$_POST['id_import']."'");
      $get_amount = GetRecords("select sum(amount) as total from pay_provider where id_list='".$_POST['id_import']."'");
      
      if ($get_amount[0]['total'] >= $obtener_amount[0]['amount']) {
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
                                       (select sum(amount) from pay_provider where id_list = importacion_cvs.id) as suma
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
        <?php echo $message; ?>
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
                                  <th>Restante</th>
                                  <th>Status</th>
                                  <th>Ver Paquetes</th>
                                  <th>Pagar</th>
                                  <th>Eliminar</th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP
                                $i=1;
                                $monto_pagar = 0;
                                $monto_pagado = 0;
                                $monto_restante = 0;
                                $contar = 0;
                                foreach ($arrUser as $key => $value) {
                                ?>
                              <tr>
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['name']?> </td>
                                  <td class="tbdata"> <?php echo $value['date']?> </td>
                                  <td class="tbdata"> <?php echo $value['contar']?> </td>
                                  <td class="tbdata"> <?php echo number_format($value['amount'], 2);?> $</td>
                                  <td class="tbdata"> <?php echo number_format($value['suma'], 2);?> $</td>
                                  <td class="tbdata"> <?php echo number_format(($value['amount'] - $value['suma']), 2);?> $</td>
                                  <td class="tbdata"> <?php if ($value['stat']==1){ echo 'Pendiente por pagar'; }else{ echo 'Pagado'; } ?> </td>
                                  <td class="tbdata"><a href="list-import-package-list.php?id_import_cvs=<?php echo $value['id']?>" class="btn btn-success btn-rounded"><?php echo 'Ver';?></a></td>
                                  <td class="tbdata"><button data-toggle="modal" data-target="#myModal<?php echo $value['id']?>" class="btn btn-success btn-rounded"><?php echo 'Pagar';?></button>
                                  <?php $maximo = $value['amount'] - $value['suma'];?>

                                    <div class="modal inmodal" id="myModal<?php echo $value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
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
                                                        <label class="col-lg-4 text-right control-label">Monto a pagar</label>
                                                        <div class="col-lg-6">
                                                          <input type="number" class="form-control" max="<?php echo $maximo; ?>" name="amount_pay" require autocomplete="off">
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
                                  <td class="tbdata">
                                    <?php if($_SESSION['USER_ID'] == 30 || $_SESSION['USER_ID'] == 1){ ?>
                                    <button data-toggle="modal" data-target="#myModal2<?php echo $value['id']?>" class="btn btn-danger btn-rounded"><?php echo 'Eliminar';?></button>
                                    <?php } ?>
                                    <div class="modal inmodal" id="myModal2<?php echo $value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                        <div class="modal-dialog">
                                        <div class="modal-content animated bounceInRight">
                                                <div class="modal-header">
                                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                                    <h4 class="modal-title" style="color:red;"><?php echo 'Eliminar lista';?></h4>
                                                </div>
                                                <form class="form-horizontal" action="" method="post">
                                                <div class="modal-body">
                                                    <div class="row">
                                                      <div class="form-group">
                                                        <label class="col-lg-4 text-right control-label" style="color:red;">Eliminar lista</label>
                                                        <div class="col-lg-6" style="color:red;">
                                                          <p>
                                                            Recuerde que, se borraran todos los paquetes que pertenecen a esta lista
                                                          </p>
                                                          <input type="hidden" name="id_delete" value="<?php echo $value['id']?>">
                                                        </div>
                                                      </div>
                                                    </div>
                                                </div>
                                                <div class="modal-footer">
                                                    <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                                    <button type="submit" class="btn btn-primary" name="deleteList"><?php echo 'Eliminar';?></button>
                                                </div>
                                              </form>
                                            </div>
                                        </div>
                                    </div>
                                  </td>
                              </tr>
                              <?php
                                $i++;
                                 $monto_pagar += number_format($value['amount'], 2);
                                 $monto_pagado += number_format($value['suma'], 2);
                                 $monto_restante += number_format(($value['amount'] - $value['suma']), 2);
                                 $contar += $value['contar'];
                              }
                              ?>
                             
                             </tbody>
                             <tr>
                                <td></td>
                                <td></td>
                                <td><b>Totales: </b></td>
                                <td><b><?php echo $contar; ?></b></td>
                                <td><b><?php echo number_format($monto_pagar, 2); ?> $</b></td>
                                <td><b><?php echo number_format($monto_pagado, 2); ?> $</b></td>
                                <td><b><?php echo number_format($monto_restante, 2); ?> $</b></td>

                                <td></td>
                                <td></td>
                             </tr> 
                            </table>
                        </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
<?php
  include("footer.php");
?>
