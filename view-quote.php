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
      
     $html = '
  
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title" align="center">
                        <h3>Quote</h3>
                    </div>
                    <div class="ibox-content">
                      
                          
                          <table class="table table-striped b-t b-light tableline">
                            <tr>  
                              <td>Customer</td>
                              <td>
                                    ';
                                    $html.= $arrCust["name"];
                                    
                             $html.='       
                              </td>
                              <td>Date</td>
                                  <td>
                                  ';
                                  $html.=$arrQuote["date"];
                                  $html.='
                                  
                                  </td>
                            </tr>
                          </table>
                           
                          <br><br><br>
                          <div class="table-responsive">
                            
                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th>Pieces</th>
                                  <th>Tracking No</th>
                                  <th>Price Per Pound(lb)</th>
                                  <th colspan="3" align="center">Dimension</th>
                                  <th>Weight to Collect</th>
                                  <th>Price to collect(lb)</th>
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
                                    <span class="font-bold">Totals</span><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Pieces</td>
                                  <td>Weight (Lb)</td>
                                  <td>Sub Total</td>
                                  <td>Others</td>
                                  <td>Total</td>
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

      // $(document).ready(function (){  

      //           // $('.printpreview').click(function()
      //           // {
      //              // $('.printpreview').hide()
      //               window.print();

      //       //     });


      //        });
    </script>