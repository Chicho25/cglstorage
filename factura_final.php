  <?php
  ob_start();
  include("include/config.php");
  include("include/defs.php");

  function metodo_pago($id_metodo){
      if ($id_metodo == 1) {
          $metodo = "Efectivo";
      }elseif($id_metodo == 2){
          $metodo = "Cheque";
      }elseif($id_metodo == 3){
          $metodo = "ACH";
      }elseif($id_metodo == 4){
          $metodo = "Tarjeta";
      }
      return $metodo;
  }

  function number_invoice($tipo, $id_invoice){
    $id_invoice = GetRecords("SELECT id FROM number_invoice WHERE tipo = '".$tipo."' and id_invoice = '".$id_invoice."'");
    foreach($id_invoice as $value){
      $id_invoice = $value['id'];
    }
    return $id_invoice;
  }

  $arrQuote = GetRecords("select
                          master_pay.id,
                          master_pay.fecha,
                          '' as othervalue,
                          (select customer.name from customer where quote.id_customer = customer.id) as nombre_cliente,
                          quote.id_customer,
                          quote_detail.id_package,
                          quote_detail.price,
                          package.trackingno,
                          package.totaltopay as total_pagar,
                          quote_detail.price as total_cobrar,
                          quote_detail.pieces as tpieces,
                          package.stat,
                          package.width,
                          package.height,
                          package.widthlb,
                          package.custompricerate,
                          (package.weighttocollect * 1.50) as cost_house,
                          pay_datail_invoice.id_method,
                          pay_datail_invoice.descriptions,
                          pay_datail_invoice.attched
                          from package inner join master_pay on package.id_master_pay = master_pay.id
                          			       inner join quote_detail on quote_detail.id_package = package.id
                                       inner join pay_datail_invoice on pay_datail_invoice.id_invoice = quote_detail.id_quote
                                       inner join quote on quote.id = quote_detail.id_quote
                                       where
                                       package.id_master_pay = ".$_REQUEST['id']);

  foreach($arrQuote as $value){
      $id_cus = $value['id_customer'];
      $id_factura = $value["id"];
      $fecha_emision = $value["fecha"];
      $termino_pago = metodo_pago($value["id_method"]);
      $nombre_cliente = $value["nombre_cliente"];
      break;
  }
  $arrCust = GetRecords("select * from customer where id = '".$id_cus."'");
  $id_customer = $arrCust[0]['membernumber'];

  //$arrCust = GetRecord("customer", "id = ".$arrQuote['id_customer']);

  ?>
  <!DOCTYPE html>
  <html>
  <head>
    <meta charset="utf-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <title>Factura</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <style media="screen">
    body{
          background:#EEE;
          /* font-size:0.9em !important; */
          }
          .invoice{
          width:970px !important;
          margin:50px auto;
          .invoice-header{
            padding:25px 25px 15px;
            h1{
              margin:0
            }
            .media{
              .media-body{
                font-size:.9em;
                margin:0;
              }
            }
          }
          .invoice-body{
            border-radius:10px;
            padding:25px;
            background:#FFF;
          }
          .invoice-footer{
            padding:15px;
            font-size:0.9em;
            text-align:center;
            color:#999;
          }
          }
          .logo{
          max-height:70px;
          border-radius:10px;
          }
          .dl-horizontal{
          margin:0;
          dt{
                float: left;
            width: 80px;
            overflow: hidden;
            clear: left;
            text-align: right;
            text-overflow: ellipsis;
            white-space: nowrap;
          }
          dd{
            margin-left:90px;
          }
          }
          .rowamount{
          padding-top:15px !important;
          }
          .rowtotal{
          font-size:1.3em;
          }
          .colfix{
          width:12%;
          }
          .mono{
          font-family:monospace;
          }
          .button
          {
          width: 100%;
          border: 1px solid #DBE1EB;
          font-size: 18px;
          font-family: Arial, Verdana;
          padding-left: 7px;
          padding-right: 7px;
          padding-top: 5px;
          padding-bottom: 5px;
          border-radius: 4px;
          -moz-border-radius: 4px;
          -webkit-border-radius: 4px;
          -o-border-radius: 4px;
          background: #4972B5;
          background: linear-gradient(left, #4972B5, #618ACB);
          background: -moz-linear-gradient(left, #4972B5, #618ACB);
          background: -webkit-linear-gradient(left, #4972B5, #618ACB);
          background: -o-linear-gradient(left, #4972B5, #618ACB);
          color: #FFFFFF;
          }

          .button:hover
          {
          background: #365D9D;
          background: linear-gradient(left, #365D9D, #436CAD);
          background: -moz-linear-gradient(left, #365D9D, #436CAD);
          background: -webkit-linear-gradient(left, #365D9D, #436CAD);
          background: -o-linear-gradient(left, #365D9D, #436CAD);
          color: #FFFFFF;
          border-color: #FBFFAD;
          }
          .caja {
           width: 200px;
           margin: 0 auto;
          }
    </style>
  </head>
  <body>
    <div class="container invoice" id="contenido">
    <div class="invoice-header">
      <div class="row">
        <div class="col-xs-8">
          <h1>Factura <small></small></h1>
          <h4 class="text-muted">NO: ZF19-00<?php echo number_invoice(2, $_REQUEST['id']); ?> | Fecha: <?php echo date("Y-m-d"); ?></h4>
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
                <dd><?php echo $nombre_cliente; ?></dd>
                <dt>Fehca de emision</dt>
                <dd><?php echo $fecha_emision; ?></dd>
            </div>
          </div>
        </div>
      </div>
      <div class="panel panel-default">
        <div class="panel-heading">
          <h3 class="panel-title">General</h3>
        </div>
        <table class="table table-bordered table-condensed">
          <thead>
            <tr>
              <th>Customer ID</th>
              <th>Termino de Pago</th>
              <th>Fecha de Emision</th>
            </tr>
          </thead>
          <tbody>
            <tr>
              <td>
                <?php echo $id_customer; ?>
                <br>
                <small class="text-muted"></small>
              </td>
              <td class="text-right">
                <?php echo $termino_pago; ?>
                <span class="mono"></span>
                <br>
                <small class="text-muted"></small>
              </td>
              <td class="text-right">
                <?php echo $fecha_emision; ?>
                <span class="mono"></span>
                <br>
                <small class="text-muted"></small>
              </td>
            </tr>
          </tbody>
        </table>
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
            $ptotal=0;
            $wbtotal=0;
            $subtotal=0;
            $othtotal=0;
            $gtotal = 0;
            foreach ($arrQuote as $key => $value) { ?>
            <tr>
              <th class="text-center rowtotal mono"><?php echo $value["tpieces"]; ?></th>
              <th class="text-center rowtotal mono"><?php echo $value["trackingno"]; ?></th>
              <th class="text-center rowtotal mono"><?php echo number_format($value["custompricerate"],2); ?></th>
              <th class="text-center rowtotal mono">
                L <?php echo number_format($value["width"],2); ?> |
                W <?php echo number_format($value["height"],2); ?> |
                H <?php echo number_format($value["widthlb"],2); ?>
              </th>
              <th class="text-center rowtotal mono"><?php echo number_format($value["widthlb"],2); ?></th>
              <th class="text-center rowtotal mono"><?php echo number_format($value["total_cobrar"],2); ?></th>
            </tr>
            <?php
            $ptotal+= $value["tpieces"];
            $wbtotal+= number_format($value["widthlb"], 2);
            $subtotal+= number_format($value["total_cobrar"], 2);
            $othtotal+= number_format($value["custompricerate"], 2);
            $gtotal+= number_format($value["custompricerate"] + $value["total_cobrar"],2);
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
                  <dd><?php echo $ptotal; ?></dd>
                  <dt>Peso(Lb):</dt>
                  <dd><?php echo number_format($wbtotal, 2); ?></dd>
                  <dt>Sub Total:</dt>
                  <dd><?php echo number_format($subtotal, 2); ?> $</dd>
                  <dt>Otros:</dt>
                  <dd><?php echo number_format($othtotal, 2); ?> $</dd>
                  <dt>Total:</dt>
                  <dd><?php echo number_format($gtotal, 2); ?> $</dd>
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
                    document.body.appendChild(canvas);
                }
            });
        });
    });
  </script>
  </body>
  </html>
