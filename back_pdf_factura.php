<?php
    ob_start();
    include("include/config.php");
    include("include/defs.php");
     require_once('mpdf/mpdf.php');
     $mpdf = new mPDF('c', 'A4-L');
     $message="";?>
  <?php
      $arrQuote = GetRecord("quote", "id = ".$_REQUEST['id']);
      $arrCust = GetRecord("customer", "id = ".$arrQuote['id_customer']);
      $status = ($arrQuote['stat'] == 1) ? 'checked' : '';
      $arrCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
     $html = '
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class=" float-e-margins">
                    <div class="">
                          <table width="100%">
                            <tr>
                              <td width="50%">
                                <h3>'.$arrCompanyInfo['name'].'</h3>
                                <BR>
                                '.$arrCompanyInfo['address'].'
                                <br>
                                Tel. '.$arrCompanyInfo['phone'].'
                                <br>
                                Ruc. '.$arrCompanyInfo['ruc'].' D.V. '.$arrCompanyInfo['dv'].'
                                </b>
                              </td>
                              <td width="50%" align="right" valign="top">
                                  <p><span style="border-bottom: 1px solid #000000; ">'.date("Y-m-d").'</span></p>';
                                    $html.='<p><b><br>Client:</b> '.$arrCust["name"].'<br></p>';
                                  $html.='<p><b><br>Fecha:</b> '.$arrQuote["date"].'</p>';
                              $html.='
                              </td>
                            </tr>
                          </table>
                          <br><br><br>
                          <table width="100%">
                            <tr>
                              <td width="100%" align="center">
                                <h3>FACTURA</h3>
                              </td>
                            </tr>
                          </table>


                          <div class="table-responsive">
                            <table width="100%" border="1">
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
                              <tbody>';
                                $arrOppDetail = GetRecords("select package.*, quote_detail.pieces as tpieces, quote_detail.price as tprice  from quote_detail
                                                            inner join package on package.id = quote_detail.id_package
                                                             where id_quote = ".$id);
                                $ptotal=0;
                                $wbtotal=0;
                                $subtotal=0;
                                $othtotal=0;
                                $gtotal = 0;
                                foreach ($arrOppDetail as $key => $value) {
                                $html.='
                                    <tr>
                                      <td style="text-align:center;">';
                                      $html.=$value["tpieces"];
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=$value["trackingno"];
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["custompricerate"],2);
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["length"], 2);
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["width"],2);
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["height"],2);
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["widthlb"],2);
                                      $html.='</td>
                                      <td style="text-align:center;">';
                                      $html.=number_format($value["tprice"],2);
                                      $html.='</td>
                                    </tr>';
                                  $ptotal+= $value["tpieces"];
                                  $wbtotal+= number_format($value["widthlb"], 2);
                                  $subtotal+= number_format($value["tprice"], 2);
                                  $othtotal+= number_format($value["custompricerate"], 2);
                                  $gtotal+= number_format($value["custompricerate"] + $value["tprice"],2);
                                }
                              $html.='
                              </tbody>
                           </table>
                          </div>
                          <br><br>
                         <div class="table-responsive">
                            <table style="margin-left:380px;" border="1">
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
                                  <td id="ptotal" style="text-align:center;">';
                                  $html.=$ptotal;
                                  $html.='</td>
                                  <td id="wbtotal" style="text-align:center;">';
                                  $html.=number_format($wbtotal,2);
                                  $html.='</td>
                                  <td id="subtotal" style="text-align:center;">';
                                  $html.=number_format($subtotal,2);
                                  $html.='</td>
                                  <td id="othtotal" style="text-align:center;">';
                                  $html.=number_format($arrQuote['othervalue'],2);
                                  $html.='</td>
                                  <td id="gtotal" style="text-align:center;">';
                                  $html.=number_format($subtotal + $arrQuote['othervalue'],2);
                                  $html.='</td>
                                </tr>
                              </tfoot>
                            </table>
                          </div>
                        </div>
                      </div>
            </div>
        </div>
    </div>';
    $mpdf -> writeHTML($html);
    //echo $html;
    //include("footer.php");

    $mpdf -> Output('factura.pdf', 'I');
    ?>
