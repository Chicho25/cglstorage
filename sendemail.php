<?php

    $arrQuote = GetRecord("quote", "id = ".$id);
    $arrCust = GetRecord("customer", "id = ".$arrQuote['id_customer']);
    $getUserEmail = GetRecord("users", "id = ".$_SESSION['USER_ID']);
    $uEmail = $arrCust['email'];
    //ob_start();
    include("pdf/mpdf.php");

 ?>
<?php

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
                                    if($arrCust["membernumber"] != "")
                                      $html.= $arrCust["membernumber"]."-".$arrCust["name"];
                                    else
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
                                  <th>Price Per Pound</th>
                                  <th colspan="3" align="center">Dimension</th>
                                  <th>Weight</th>
                                  <th>Price</th>
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
                                      $html.=$value["custompricerate"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["length"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["width"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["height"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["widthlb"];
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["tprice"];
                                      $html.='</td>
                                    </tr>';

                                  $ptotal+= $value["tpieces"];
                                  $wbtotal+= $value["widthlb"];
                                  $subtotal+= $value["tprice"];
                                  $othtotal+= $value["custompricerate"];
                                  $gtotal+= $value["custompricerate"] + $value["tprice"];
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
                                  <td>Weight Lb</td>
                                  <td>Sub Total</td>
                                  <td>Others</td>
                                  <td>Total</td>
                                </tr>
                                <tr>
                                  <td id="ptotal">';
                                  $html.=$ptotal;
                                  $html.='</td>
                                  <td id="wbtotal">';
                                  $html.=$wbtotal;
                                  $html.='</td>
                                  <td id="subtotal">';
                                  $html.=$subtotal;
                                  $html.='</td>
                                  <td id="othtotal">';
                                  $html.=$othtotal;
                                  $html.='</td>
                                  <td id="gtotal">';
                                  $html.=$gtotal;
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





$mpdf=new mPDF();
$stylesheet = file_get_contents('css/bootstrap.css');

$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
//$content= $mpdf->Output('test.pdf', 'F'); // Saving pdf to attach to email

$subject = 'Quote Registered';
$fileName = 'quote-'.$id.'.pdf';

$to = $uEmail;
$repEmail = '';
/* luis.hernandez@kuroisoft.com */


$fileatt = $mpdf->Output($fileName, 'S');
$attachment = chunk_split(base64_encode($fileatt));
$eol = PHP_EOL;
$separator = md5(time());

$headers = 'From: Luis <'.$repEmail.'>'.$eol;
$headers .= 'MIME-Version: 1.0' .$eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

$message = "--".$separator.$eol;
$message .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
$message .= " New Quote Resistered." .$eol;

$message .= "--".$separator.$eol;
$message .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
$message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;

$message .= "--".$separator.$eol;
$message .= "Content-Type: application/pdf; name=\"".$fileName."\"".$eol;
$message .= "Content-Transfer-Encoding: base64".$eol;
$message .= "Content-Disposition: attachment".$eol.$eol;
$message .= $attachment.$eol;
$message .= "--".$separator."--";

if (mail($to, $subject, $message, $headers))
{
echo "Email sent";
}

else
{
echo "Email failed";
}
?>
