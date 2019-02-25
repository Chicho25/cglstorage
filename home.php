<?php
  ob_start();
  session_start();
  $hideLeft = true;
  include("include/config.php");
  include("include/defs.php");
  $loggdUType = current_user_type();

  include("header.php");

  if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     /* Funciones */
     // costo del proveedor miami
    if (isset($_POST['cost_provider'])) {
        UpdateRec("provider_cost", "id = 1", array("cost" => $_POST['cost_provider']));
    }
    $cost_provider = GetRecords("SELECT cost FROM provider_cost");
    // Pago a Proveedor
     $arrPayp = GetRecords("SELECT *, 
                                (select count(*) from package where id_import_cvs = importacion_cvs.id) as contar,
                                (select sum(amount) from pay_provider where id_list = importacion_cvs.id) as suma
                              FROM  importacion_cvs");
     $monto_restante = 0;

     foreach ($arrPayp as $key => $value) {

          $monto_restante += number_format(($value['amount'] - $value['suma']), 2);
         
     }
     // Total ganancia
     $cobrar = 0;
     $pagar = 0;
     $arrgana = GetRecords("select
                              quote.id,
                              quote.date as fecha,
                              quote.othervalue,
                              customer.name as nombre_cliente,
                              quote_detail.id_package,
                              quote_detail.price,
                              package.trackingno as codigo,
                              package.totaltopay as total_pagar,
                              (quote.othervalue + quote_detail.price) as total_cobrar,
                              package.stat,
                              (package.weighttocollect * '".$cost_provider[0]['cost']."') as cost_house, 
                              pay_datail_invoice.id_method, 
                              pay_datail_invoice.descriptions, 
                              pay_datail_invoice.attched
                              from quote inner join customer on quote.id_customer = customer.id
                                    inner join quote_detail on quote_detail.id_quote = quote.id
                                    inner join package on package.id = quote_detail.id_package
                                    left join pay_datail_invoice on pay_datail_invoice.id_invoice = quote.id");
     foreach ($arrgana as $key => $value) {

        $cobrar += $value['total_cobrar'];
        $pagar += $value['cost_house'];
         
     }

     // Total no Facturado
     $suma_total = 0;
     $arrNoInv = GetRecords("SELECT quote.othervalue, quote.stat, quote.id, quote.date, customer.name as CName, sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                            group by quote_detail.id_quote
                             ");
     foreach ($arrNoInv as $key => $value) {
          
        $suma_total += round($value['total'] + $value['othervalue'] , 2);
     
    }

 ?>

 <div class="wrapper wrapper-content animated fadeInRight">
    <div class="col-lg-12">
            <div class="ibox float-e-margins">
                <!--<div class="ibox-title">
                    <h5>Bienvenido al Sistema</h5>
                </div>-->
                <div class="">
                <?php if($_SESSION['USER_ID'] == 30 || $_SESSION['USER_ID'] == 1){ ?>
                  <div class="col-lg-3">
                        <div class="ibox ">
                            <div class="ibox-title">
                                <span class="label label-success float-right"></span>
                                <h5>Precio Miami</h5>
                            </div>
                            <div class="ibox-content">
                                <h1 class="no-margins"><?php echo $cost_provider[0]['cost']; ?> $</h1>
                                <a class="stat-percent font-bold text-success" data-toggle="modal" data-target="#myModalProv">Cambiar <i class="fa fa-bolt"></i></a>
                                <small>Precio Proveedor</small>
                            </div>
                        </div>
                    </div>
                    <div class="modal inmodal" id="myModalProv" tabindex="-1" role="dialog" aria-hidden="true">
                        <div class="modal-dialog">
                            <div class="modal-content animated bounceInRight">
                                <div class="modal-header">
                                    <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                    <h4 class="modal-title" style="color:red;"><?php echo 'Costo del Proveeedor';?></h4>
                                </div>
                                <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                    <div class="modal-body">
                                        <div class="row">
                                            <div class="form-group">
                                                <label class="col-lg-4 text-right control-label">Costo</label>
                                                <div class="col-lg-6">
                                                    <input name="cost_provider" type="text" value="<?php echo number_format($cost_provider[0]['cost'], 2); ?>" class="form-control">
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    <div class="modal-footer">
                                        <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                        <button type="submit" class="btn btn-primary" name="payInvoice"><?php echo 'Actualizar';?></button>
                                    </div>
                                </form>
                            </div>
                        </div>
                    </div>  


                    <div class="col-lg-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <span class="label label-success float-right"></span>
                                  <h5>Pago a Proveedor</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?php echo $monto_restante; ?> $</h1>
                                  <a href="list-import-package.php" class="stat-percent font-bold text-success">Ver <i class="fa fa-bolt"></i></A>
                                  <small>Total a pagar a Proveedor</small>
                              </div>
                          </div>
                      </div>

                      <div class="col-lg-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <span class="label label-success float-right"></span>
                                  <h5>Total Ganancia</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?php echo $cobrar - $pagar; ?> $</h1>
                                  <a href="report_pakect.php" class="stat-percent font-bold text-success">Ver <i class="fa fa-bolt"></i></a>
                                  <small>Total Ganancia</small>
                              </div>
                          </div>
                      </div>

                      
                      <div class="col-lg-3">
                          <div class="ibox ">
                              <div class="ibox-title">
                                  <span class="label label-success float-right"></span>
                                  <h5>Total No Facturado</h5>
                              </div>
                              <div class="ibox-content">
                                  <h1 class="no-margins"><?php echo $suma_total; ?> $</h1>
                                  <a href="quote.php" class="stat-percent font-bold text-success">Ver <i class="fa fa-bolt"></i></a>
                                  <small>Total no facturado</small>
                              </div>
                          </div>
                      </div>

                <?php }else{ ?>
                  <a class="" href="home.php"><img src="img/logo1.png"></a>
                <?php } ?>
                
                </div>
            </div>
    </div>
  </div>

<?php include("footer.php"); ?>
