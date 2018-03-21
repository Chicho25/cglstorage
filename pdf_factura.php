<?php
    ob_start();
    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();
    $arrQuote = GetRecord("quote", "id = ".$_REQUEST['id']);
    $arrCust = GetRecord("customer", "id = ".$arrQuote['id_customer']);
    $status = ($arrQuote['stat'] == 1) ? 'checked' : '';
    $arrCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <style>
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
  <div class="container">
    <div class="row" id="contenido">
      <table width="100%">
        <tr>
          <td width="50%">
            <h3><?php echo $arrCompanyInfo['name']; ?></h3>
            <BR>
            <?php echo $arrCompanyInfo['address']; ?>
            <br>
            <?php echo $arrCompanyInfo['phone']; ?>
            <br>
            <?php echo $arrCompanyInfo['ruc'].' D.V. '.$arrCompanyInfo['dv']; ?>
            </b>
          </td>
          <td width="50%" align="right" valign="top">
            <p><span style="border-bottom: 1px solid #000000; "><?php echo date("Y-m-d"); ?></span></p>
            <p><b><br>Client:</b> <?php echo $arrCust["name"]; ?><br></p>
            <p><b><br>Fecha:</b> <?php echo $arrQuote["date"]; ?></p>
          </td>
        </tr>
      </table>
      <br>
      <br>
      <table width="100%">
        <tr>
        <td width="100%" align="center">
          <img src="img/logo1.png" width="150" alt="">
        <h3>FACTURA</h3>
        </td>
        </tr>
      </table>
      <div>
        <table  border="1" style="margin: 0 auto;">
          <thead>
            <tr>
              <th>Piezas</th>
              <th>No. Tracking</th>
              <th>Precio por Libra(lb)</th>
              <th colspan="3" align="center">Dimension(Inch)</th>
              <th>Peso a Cobrar(lb)</th>
              <th>Precio($)</th>
            </tr>
            <tr>
              <th></th>
              <th></th>
              <th></th>
              <th>L ×</th>
              <th>W ×</th>
              <th>H </th>
              <th></th>
              <th></th>
            </tr>
          </thead>
        <tbody>
        <?php
        $arrOppDetail = GetRecords("select package.*, quote_detail.pieces as tpieces, quote_detail.price as tprice  from quote_detail
        inner join package on package.id = quote_detail.id_package
        where id_quote = ".$id);
        $ptotal=0;
        $wbtotal=0;
        $subtotal=0;
        $othtotal=0;
        $gtotal = 0;
        foreach ($arrOppDetail as $key => $value) { ?>

          <tr>
            <td style="text-align:center;">
            <?php echo $value["tpieces"]; ?>
            </td>
            <td style="text-align:center;">
            <?php echo $value["trackingno"]; ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["custompricerate"],2); ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["length"], 2); ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["width"],2); ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["height"],2); ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["widthlb"],2); ?>
            </td>
            <td style="text-align:center;">
            <?php echo number_format($value["tprice"],2); ?>
            </td>
          </tr>
        <?php
        $ptotal+= $value["tpieces"];
        $wbtotal+= number_format($value["widthlb"], 2);
        $subtotal+= number_format($value["tprice"], 2);
        $othtotal+= number_format($value["custompricerate"], 2);
        $gtotal+= number_format($value["custompricerate"] + $value["tprice"],2);
        } ?>
        </tbody>
        </table>
      </div>
      <br>
      <br>
      <div>
        <table border="1" style="margin: 0 auto;">
          <tfoot>
            <tr>
              <td colspan="5" align="center">
              <span class="font-bold"><b>Total</b></span><br>
              </td>
            </tr>
            <tr>
              <td><b>Piezas</b></td>
              <td><b>Peso(Lb)</b></td>
              <td><b>Sub Total</b></td>
              <td><b>Otros</b></td>
              <td><b>Total</b></td>
            </tr>
            <tr>
              <td id="ptotal" style="text-align:center;">
              <?php echo $ptotal; ?>
              </td>
              <td id="wbtotal" style="text-align:center;">
              <?php echo number_format($wbtotal,2); ?>
              </td>
              <td id="subtotal" style="text-align:center;">
              <?php echo number_format($subtotal,2); ?>
              </td>
              <td id="othtotal" style="text-align:center;">
              <?php echo number_format($arrQuote['othervalue'],2); ?>
              </td>
              <td id="gtotal" style="text-align:center;">
              <?php echo number_format($subtotal + $arrQuote['othervalue'],2); ?>
              </td>
            </tr>
          </tfoot>
        </table>
      </div>
    </div>
</div>
<br>
<br>
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
  </body>
  </html>
