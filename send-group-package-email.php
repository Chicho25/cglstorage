<?php


    $arrCust = GetRecord("customer", "id = ".$customer);
    $getUserEmail = GetRecord("users", "id = ".$_SESSION['USER_ID']);
    $uEmail = $arrCust['email'];
    $arrCompany = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
    include("pdf/mpdf.php");

 ?>
<?php

    $html = '<h3>Paquete</h3>
              <img width="100" src="http://ofertadeviaje.com/cglstorage/img/logo1.png">

              <p>Estimado cliente</p>
              <p>Reciban un cordial saludo.</p>
              <p>Por medio de la presente le confirmamos que hemor recibido un paquete a su nombre.</p>
              <p>Nota: Por favor indicar en nuestras oficinas el codigo que aparece a continuacion.</p>

                            <table border="1">
                              <thead>
                                <tr>
                                  <th>Tracking No</th>
                                  <th>Weight</th>
                                  <th>Length</th>
                                  <th>Height</th>
                                  <th>Width</th>
                                  <th>Volume</th>
                                  <th>Price</th>
                                </tr>
                              </thead>
                              <tbody>
                                ';
                                $arrOppDetail = GetRecords("select * from package
                                                             where id_customer = ".$customer."
                                                             and isEmail != 1");
                                $ptotal=0;
                                $wbtotal=0;
                                $subtotal=0;
                                $othtotal=0;
                                $gtotal = 0;
                                foreach ($arrOppDetail as $key => $value) {

                                  UpdateRec("package", "id=".$value['id'], array("isEmail" => 1));
                                $html.='

                                    <tr><td>';

                                      $html.=$value["trackingno"];
                                      $html.='</td>
                                      <td>';
                                      $html.=round($value["widthlb"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=round($value["length"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=round($value["height"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=round($value["width"],2);
                                      $html.='</td>
                                      <td>';
                                      $html.=$value["volume"];
                                      $html.='</td>
                                      <td>';
                                      $html.=round($value["totaltopay"],2);
                                      $html.='</td>

                                    </tr>';


                                  $wbtotal+= $value["widthlb"];
                                  //$subtotal+= 0;
                                  $othtotal+= $value["custompricerate"];
                                  $gtotal+= $value["totaltopay"] ;
                                }

                              $html.='
                              </tbody>
                           </table>
                          <br><br>
                            <table border="1">
                              <tfoot>
                                <tr>
                                  <td colspan="4" align="center">
                                    <span class="font-bold">Totals</span><br>
                                  </td>
                                </tr>
                                <tr>
                                  <td>Pieces</td>
                                  <td>';
                                  $html.=count($arrOppDetail);
                                  $html.= '</td>
                                </tr>
                                <tr>

                                  <td id="wbtotal">Total</td>
                                  <td>
                                  ';
                                  $html.=round($gtotal,2);
                                  $html.='</td>
                                </tr>
                              </tfoot>
                            </table>';




$mpdf=new mPDF();
$stylesheet = file_get_contents('css/bootstrap.css');

$mpdf->WriteHTML($stylesheet, 1);
$mpdf->WriteHTML($html);
//$content= $mpdf->Output('test.pdf', 'F'); // Saving pdf to attach to email

$subject = 'Paquete Registrado';
$fileName = 'package-'.$id.'.pdf';

$to = $uEmail;

$repEmail = (isset($arrCompany['email']) && $arrCompany['email'] != "") ? $arrCompany['email'] : '';
/* luis.hernandez@kuroisoft.com */

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
$message .= " Nuevo Paquete Registrado." .$eol;

$message .= "--".$separator.$eol;
$message .= "Content-Type: text/html; charset=\"iso-8859-1\"".$eol;
$message .= $html;
/*
$message .= "Content-Transfer-Encoding: 8bit".$eol.$eol;

$message .= "--".$separator.$eol;
$message .= "Content-Type: application/pdf; name=\"".$fileName."\"".$eol;
$message .= "Content-Transfer-Encoding: base64".$eol;
$message .= "Content-Disposition: attachment".$eol.$eol;
$message .= $attachment.$eol;
$message .= "--".$separator."--";
*/

if (mail($to, $subject, $message, $headers))
{
echo "Email sent";
}

else
{
echo "Email failed";
}


?>
