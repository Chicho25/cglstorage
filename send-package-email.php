<?php

    $arrQuote = GetRecord("package", "id = ".$id);
    $arrCust = GetRecord("customer", "id = ".$arrQuote['id_customer']);
    $getUserEmail = GetRecord("users", "id = ".$_SESSION['USER_ID']);
    $uEmail = $getUserEmail['Email'];
    $arrCompany = GetRecord("company", "id = ".$arrQuote['id_company']);

    include("pdf/mpdf.php");

 ?>
<?php

    $html = '

  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title" align="center">
                        <h3>Package</h3>
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

                            </tr>
                          </table>

                          <br><br><br>
                          <div class="table-responsive">

                            <table class="table table-striped b-t b-light tableline">
                              <thead>
                                <tr>
                                  <th>Tracking No</th>
                                  <th>Price Per Pound</th>
                                  <th>Length</th>
                                  <th>Width</th>
                                  <th>Height</th>
                                  <th>Weight</th>
                                </tr>
                              </thead>
                              <tbody>
                                ';
                                $arrOppDetail = GetRecords("select * from package
                                                             where id = ".$id);
                                $ptotal=0;
                                $wbtotal=0;
                                $subtotal=0;
                                $othtotal=0;
                                $gtotal = 0;
                                foreach ($arrOppDetail as $key => $value) {

                                $html.='

                                    <tr><td>';

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

                                    </tr>';


                                  $wbtotal+= $value["widthlb"];
                                  //$subtotal+= 0;
                                  $othtotal+= $value["custompricerate"];
                                  $gtotal+= $value["custompricerate"] ;
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
                                  <td colspan="4" align="center">
                                    <span class="font-bold">Totals</span><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Weight Lb</td>
                                  <td>Sub Total</td>
                                  <td>Others</td>
                                  <td>Total</td>
                                </tr>
                                <tr>

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

$subject = 'Package Registered';
$fileName = 'package-'.$id.'.pdf';

$to = $uEmail;

$repEmail = (isset($arrCompany['email']) && $arrCompany['email'] != "") ? $arrCompany['email'] : '';
/*luis.hernandez@kuroisoft.com*/

$repName = (isset($arrCompany['name']) && $arrCompany['name'] != "") ? $arrCompany['name'] : 'Admin';

$fileatt = $mpdf->Output($fileName, 'S');


$attachment = chunk_split(base64_encode($fileatt));
$eol = PHP_EOL;
$separator = md5(time());

$headers = 'From: '.$repName.' <'.$repEmail.'>'.$eol;
$headers .= 'MIME-Version: 1.0' .$eol;
$headers .= "Content-Type: multipart/mixed; boundary=\"".$separator."\"";

$message = "--".$separator.$eol;
$message .= "Content-Transfer-Encoding: 7bit".$eol.$eol;
$message .= " New Package Resistered." .$eol;

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
