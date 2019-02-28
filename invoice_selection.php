<?php
    ob_start();
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    function conti($array_seleccion, $id_seleccion){
      foreach ($array_seleccion as $key => $value2) {
        if($value2 == $id_seleccion){
          return 1;
        }
      }
    }

    if(isset($_POST['select_pay'])){

      // master pay

      $master_pay = array("stat" => 1);
      $id_master_pay = InsertRec("master_pay", $master_pay);
      // nuemracion de la factura

      $array_num_invo = array("stat" => 1,
                              "id_invoice" => $id_master_pay,
                              "tipo" => 2);

      $numero_factura = InsertRec("number_invoice", $array_num_invo);

      foreach ($_POST['fact'] as $key => $value) {
        if($value!=0){
          UpdateRec("quote", "id = ".$value, array("stat" => 2,
                                                   "id_master_pay" => $id_master_pay));

          UpdateRec("package", "id in (select id_package from quote_detail
                    where id_quote = ".$value.") ", array("stat" => 3,
                                                          "id_master_pay" => $id_master_pay));

        $array_detail = array("id_invoice" => $value,
                              "id_method" => $_POST['method'],
                              "descriptions" => $_POST['descriptions'],
                              "id_user" => $_SESSION['USER_ID'],
                              "date_time" => date("Y-m-d H:i:s"),
                              "stat" => 1,
                              "id_master_pay" => $id_master_pay);

        $id_pay_detail = InsertRec("pay_datail_invoice",$array_detail);

        if(isset($_FILES['attach']) && $_FILES['attach']['tmp_name'] != "")
              {
                  $target_dir = "attched/";
                  $target_file = $target_dir . basename($_FILES["attach"]["name"]);
                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                  $filename = $target_dir . $id_pay_detail.".".$imageFileType;
                  $filenameThumb = $target_dir . $id_pay_detail."_thumb.".$imageFileType;
                  if (move_uploaded_file($_FILES["attach"]["tmp_name"], $filename))
                  {
                      //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $id_pay_detail, 200, 200);

                      UpdateRec("pay_datail_invoice", "id = ".$id_pay_detail, array("attched" => $filenameThumb));
                  }
              }
            }
        }
          echo "<script>alert('Quote invoiced successfully');
          window.location='quote.php';</script>";
    }

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";

      $arrUser = GetRecords("SELECT
                              package.*,
                              quote.othervalue,
                              quote.stat,
                              quote.id,
                              quote.date,
                              customer.name as CName,
                              sum(quote_detail.price) as total,
                              quote_detail.pieces
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                            inner join package on quote_detail.id_package = package.id
                              $where
                              group by quote_detail.id_quote
                             ");

?>
     <?php
      $bcName = Quote_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo 'Lista Seleccionada';?></h5>
                    </div>
                    <div class="ibox-content">
                      <div class="container invoice" id="contenido">
                      <div class="invoice-header">
                        <div class="row">
                          <div class="col-xs-8">
                            <h1>Factura <small></small></h1>
                            <h4 class="text-muted">Fecha: <?php echo date("Y-m-d"); ?></h4>
                          </div>
                          <div class="col-xs-4">
                            <div class="media">
                              <div class="media-left">
                                <img class="media-object logo" src="img/logo1.png" />
                              </div>
                              <ul class="media-body list-unstyled">
                                <li><strong>Ave. de la Amistad, Albrook</strong></li>
                                <li>Ciudad de Panama</li>
                                <li>silvana@dtcexpress.net</li>
                                <li>Tel: +507-60620511</li>
                                <li>RUT 8-758-1606  D.V.81</li>
                              </ul>
                            </div>
                          </div>
                        </div>
                      </div>
                      <div class="invoice-body">
                        <div class="row">
                          <div class="col-xs-5">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h3 class="panel-title">Recibo de</h3>
                              </div>
                              <div class="panel-body">
                                <dl class="dl-horizontal">
                                <br>
                                <br>
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-7">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h3 class="panel-title">Cliente</h3>
                              </div>
                              <div class="panel-body">
                                <dl class="dl-horizontal">
                                  <dt>Nombre</dt>
                                  <?php foreach ($arrUser as $key => $value) {
                                  if(conti($_POST['fact'], $value['id']) != 1){
                                    continue;
                                  }
                                  $customer = $value["CName"];
                                  break;
                                  } ?>

                                  <dd><?php echo $customer; ?></dd>
                              </div>
                            </div>
                          </div>
                        </div>
                        <div class="panel panel-default">
                          <div class="panel-heading">
                            <h3 class="panel-title">Descripcion</h3>
                          </div>
                          <table class="table table-bordered table-condensed">
                            <thead>
                              <tr>
                                <td class="text-center col-xs-1">Piezas</td>
                                <td class="text-center col-xs-1">No. Tracking</td>
                                <td class="text-center col-xs-1">Precio por Libra(lb)</td>
                                <td class="text-center col-xs-1">Dimension(Inch)</td>
                                <td class="text-center col-xs-1">Peso a Cobrar(lb)</td>
                                <td class="text-center col-xs-1">Precio($)</td>
                              </tr>
                            </thead>
                            <tbody>
                              <?php
                              /*$arrOppDetail = GetRecords("select
                                                          package.*,
                                                          quote_detail.pieces as tpieces,
                                                          quote_detail.price as tprice
                                                          from
                                                          quote_detail inner join package on package.id = quote_detail.id_package");
                              $ptotal=0;
                              $wbtotal=0;
                              $subtotal=0;
                              $othtotal=0;
                              $gtotal = 0;
                              $suma_total = 0;*/
                              $total_g =0;
                              $cantidad = 0;
                              foreach ($arrUser as $key => $value) {
                              if(conti($_POST['fact'], $value['id']) != 1){
                                continue;
                              }
                              ?>
                              <tr>
                                <th class="text-center rowtotal mono"><?php echo $value["pieces"]; ?></th>
                                <th class="text-center rowtotal mono"><?php echo $value["trackingno"]; ?></th>
                                <th class="text-center rowtotal mono"><?php echo number_format($value["custompricerate"],2); ?></th>
                                <th class="text-center rowtotal mono">
                                  L <?php echo number_format($value["width"],2); ?> |
                                  W <?php echo number_format($value["height"],2); ?> |
                                  H <?php echo number_format($value["widthlb"],2); ?>
                                </th>
                                <th class="text-center rowtotal mono"><?php echo number_format($value["widthlb"],2); ?></th>
                                <th class="text-center rowtotal mono"><?php echo number_format($value["total"],2); ?></th>
                              </tr>
                              <?php
                              /*$ptotal+= $value["tpieces"];
                              $wbtotal+= number_format($value["widthlb"], 2);
                              $subtotal+= number_format($value["tprice"], 2);
                              $othtotal+= number_format($value["custompricerate"], 2);
                              $gtotal+= number_format($value["custompricerate"] + $value["tprice"],2);*/
                              $total_g += $value['total'];
                              $cantidad++;
                            } ?>
                            </tbody>
                          </table>
                        </div>
                        <div class="row">
                          <div class="col-xs-7">
                            <div class="panel panel-default">
                              <div class="panel-body">
                                <i>Gracias por confiar en nosotros</i>
                                <hr style="margin:3px 0 5px" /> Tu apoyo continuo es apreciado y esperamos hacer negocios contigo nuevamente en el futuro
                              </div>
                            </div>
                          </div>
                          <div class="col-xs-5">
                            <div class="panel panel-default">
                              <div class="panel-heading">
                                <h3 class="panel-title">Total</h3>
                              </div>
                              <div class="panel-body">
                                <div class="panel-body">
                                  <dl class="dl-horizontal">
                                    <dt>Piezas:</dt>
                                    <dd><?php echo $cantidad; ?></dd>
                                    <dt>Total:</dt>
                                    <dd><?php echo number_format($total_g, 2); ?> $</dd>
                                </div>
                              </div>
                            </div>
                          </div>
                        </div>

                      </div>
                      <div class="invoice-footer">
                        <br/>
                        <strong></strong>
                      </div>
                    </div>

                    <div class="caja">
                      <button id="crearimagen" class="button">Generar Imagen</button>
                    </div>
                    <div class="row">
                      <div class="col-md-12" id="img-out" align="center">
                        <h5 style="font-weight:bold; color:purple;"></h5>
                        <span style="font-size:11px;">-----------------------------------------------------------------------------------------</span>
                      </div>
                    </div>
                    <div id="camvas"></div>

                  <?php  /*  <h3>Cliente:</h3>
                        <div class="row wrapper">
                        </div>
                        <div class="table-responsive">
                           <table class="table table-striped table-bordered table-hover">
                             <thead>
                                <tr>
                                  <th><?php echo 'Piezas';?></th>
                                  <th><?php echo 'No. Tracking';?></th>
                                  <th><?php echo 'Precio por Libra';?></th>
                                  <th><?php echo 'Peso a Cobrar';?></th>
                                  <th><?php echo 'Precio';?></th>
                                </tr>
                              </thead>
                              <tbody>
                              <?php
                                    $suma_total = 0;
                                    foreach ($arrUser as $key => $value) {
                                    if(conti($_POST['fact'], $value['id']) != 1){
                                      continue;
                                    }
                                ?>
                              <tr>
                                  <th><?php echo $value['id'];?></th>
                                  <th><?php echo $value['CName'];?></th>
                                  <th><?php echo $value['date'];?></th>
                                  <th><?php echo round($value['total'], 2).' $';?></th>
                                  <th><?php echo $value['stat'];?></th>
                              </tr>
                              <?php $suma_total += round($value['total'] + $value['othervalue'] , 2); ?>
                              <?php } ?>
                            </tbody>
                            <tr>
                              <td></td>
                              <td></td>
                              <td><b style="color:red;"> Total: </b></td>
                              <td><b style="color:red;"><?php echo round($suma_total, 2).' $';?></b></td>
                              <td></td>
                            </tr>
                          </table>
                        </div> */ ?>
                     <div class="content">
                      <form action="" method="post" enctype="multipart/form-data">
                          <div class="row">
                            <div class="col-lg-6">
                              <div class="form-group">
                                  <label class="col-lg-4 control-label">Metodo</label>
                                    <select name="method" class="form-control">
                                        <option value="">Seleccionar</option>
                                        <option value="1">Efectivo</option>
                                        <option value="2">Cheque</option>
                                        <option value="3">ACH(Transferencia)</option>
                                        <option value="4">Tarjeta</option>
                                    </select>
                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Descripcion</label>
                                    <textarea class="form-control" name="descriptions" id="" cols="20" rows="6"></textarea>

                                </div>
                                <div class="form-group">
                                    <label class="col-lg-4 control-label">Adjunto</label>
                                    <input type="file" name="attach" class="form-control">
                                </div>
                                <div class="form-group">
                                      <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                      <button type="submit" class="btn btn-primary" name="select_pay"><?php echo 'Pagar Seleccion';?></button>

                                </div>
                                <?php
                                  foreach ($_POST['fact'] as $key => $value) { ?>
                                  <input type="hidden" value="<?php echo $value; ?>" name="fact[]">
                                <?php } ?>
                            </div>
                          </div>
                      </form>
                    </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
    <script src="js_ima/filesaver.js" type="text/javascript"></script>
    <script src="js_ima/html2canvas.js" type="text/javascript"></script>
    <script type="text/javascript">
      $(function() {
          $("#crearimagen").click(function() {
              html2canvas($("#contenido"), {
                  onrendered: function(canvas) {
                      theCanvas = canvas;
                      document.querySelector("#camvas").appendChild(canvas);

                      /*
                      canvas.toBlob(function(blob) {
                        saveAs(blob, "Dashboard.png");
                      });
                      */
                  }
              });
          });
      });
    </script>
<?php
  include("footer.php");
?>
