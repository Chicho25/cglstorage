<?php

    ob_start();
    $quoteclass="class='active'";
    $registerQuoteclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
     $message="";


?>
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
                                <img src="img/logo1.png" width="150">
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

                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th>'.Quote_Pieces.'</th>
                                  <th>'.Quote_Tracking_No.'</th>
                                  <th>'.Quote_Price_Per_Pound.'</th>
                                  <th colspan="3" align="center">'.Quote_Dimension.'</th>
                                  <th>'.Quote_Weight_to_Collect.'</th>
                                  <th>'.Quote_Price.'</th>
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
                                ';
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
                                      <td>';
                                      $html.=$value["tpieces"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["trackingno"];
                                      $html.='</td>
                                      <td>';
                                      $html.=number_format($value["custompricerate"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=number_format($value["length"], 2);
                                      $html.='</td>
                                      <td>';
                                      $html.=number_format($value["width"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=number_format($value["height"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=number_format($value["widthlb"],2);
                                      $html.='</td>
                                      <td>';
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
                            <table class="table table-striped b-t b-light ">
                              <tfoot>
                                <tr>
                                  <td colspan="5" align="center">
                                    <span class="font-bold">'.Quote_Totals.'</span><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td>'.Quote_Pieces.'</td>
                                  <td>'.Quote_Weight.'</td>
                                  <td>'.Quote_Sub_Total.'</td>
                                  <td>'.Quote_Others.'</td>
                                  <td>'.Quote_GTotal.'</td>
                                </tr>
                                <tr>
                                  <td id="ptotal">';
                                  $html.=$ptotal;
                                  $html.='</td>
                                  <td id="wbtotal">';
                                  $html.=number_format($wbtotal,2);
                                  $html.='</td>
                                  <td id="subtotal">';
                                  $html.=number_format($subtotal,2);
                                  $html.='</td>
                                  <td id="othtotal">';
                                  $html.=number_format($arrQuote['othervalue'],2);
                                  $html.='</td>
                                  <td id="gtotal">';
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

    echo $html;
    //include("footer.php");
    ?>

    <script type="text/javascript">
      setTimeout(function(){ window.print(); window.close()}, 500);

      // $(document).ready(function (){

      //           // $('.printpreview').click(function()
      //           // {
      //              // $('.printpreview').hide()
      //               window.print();

      //       //     });


      //        });
    </script>
