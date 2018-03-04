<?php 
    
    include("include/config.php"); 
    include("include/defs.php"); 

    $loggdUType = current_user_type();

    $arrReceipt = GetRecord("receipt", "id = ".$id);
    $arrCust = GetRecord("customer", "id = ".$arrReceipt['id_customer']);
    $arrCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);
    
    $getSumOfDetail = GetRecords("select sum(amount) as totalAmount from receipt_detail
                                                             where id_receipt = ".$id."
                                                             ");

    $amountTotal = convertNum($getSumOfDetail[0]['totalAmount']);
 ?>

<?php 
    
    $html = '
  
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title" align="center">
                     <table width="100%">
                        <tr>
                          <td width="80%">
                            <h3>'.$arrCompanyInfo['name'].'</h3>
                            <BR>
                            '.$arrCompanyInfo['address'].'
                            <br>
                            Tel. '.$arrCompanyInfo['phone'].'
                            <br>
                            Ruc. '.$arrCompanyInfo['ruc'].' D.V. '.$arrCompanyInfo['dv'].'
                            </b>
                          </td>
                          <td width="20%" align="left">
                              <p>Fecha : <span style="border-bottom: 1px solid #000000; ">'.$arrReceipt['receive_date'].'</span></p>
                              <br>
                              <p>Barco : <span style="border-bottom: 1px solid #000000; ">'.$arrReceipt['ship'].'</span></p>
                          </td>
                        </tr>
                      </table>    
                    </div>
                    <br>
                    <div class="ibox-content">
                        
                          <table width="100%">
                            <tr>
                              <td width="50%">
                                  <b>He recibido de : '.$arrCust['name'].'</b>
                              </td>
                            </tr>
                            <tr>
                              <td width="50%">
                                  <br>La suma de : <span style="border-bottom: 1px solid #000000; ">'.$amountTotal.'</span>
                              </td>
                            </tr>  
                          </table>
                            <br>
                            <table align="left" width="100%"  cellpadding="5" cellspacing="5">
                              <thead>
                                
                              </thead>
                              <tbody>
                                ';
                                $arrOppDetail = GetRecords("select * from receipt_detail
                                                             where id_receipt = ".$id."
                                                             ");
                                $ptotal=0;
                                $wbtotal=0;
                                $subtotal=0;
                                $othtotal=0;
                                $gtotal = 0;
                                foreach ($arrOppDetail as $key => $value) {

                                $html.='
                                    
                                    <tr >';
                                      if($key == 0)
                                        $html.='<td width="25%">en concepto de:</td><td  width="75%" style="border-bottom: 1px solid #000000; ">';
                                      else
                                        $html.='<td width="25%">&nbsp;</td><td style="border-bottom: 1px solid #000000; " width="75%" >';
                                      
                                      $html.="<table width='100%'><tr><td width='90%'>";
                                        $html.="".$value["description"]."</td>";
                                      
                                        $html.="<td align='right' width='10%'>$".number_format($value["amount"],2)."</td>";
                                      $html.='</tr></table>';  
                                      $html.='</td>
                                      
                                      
                                    </tr>';
                                  $gtotal+= $value["amount"] ;
                                }
                                
                              $html.='
                                  <tr>
                                  <td width="25%">&nbsp;</td>
                                  <td width="75%">
                                  ';
                                   $html.="<table width='100%'><tr><td width='50%'>&nbsp;</td>"; 
                                  $html.="<td align='right' width='50%'>$".number_format($gtotal,2)."</td>";
                                  $html.='</tr></table>';  
                                  $html.='</td>
                                </tr>


                              </tbody>
                           </table>
                          <BR>
                          <table width="100%">  
                            <tr>
                                <td width="30%" style="border-top: 1px solid #000000; " valign="top">
                                    Recibido por : '.$arrReceipt['receive_by'].'
                                </td>
                                <td width="70%"  align="right"  valign="top">
                                    No. de Recibo : '.$arrReceipt['receipt_no'].'
                                </td>
                              </tr>
                          </table>
                        </div>
                      </div>      
            </div>
            
        </div>    

    </div>';
    

  
include("pdf/mpdf.php");
  $mpdf=new mPDF(); 
$mpdf->WriteHTML($html);
$stylesheet = file_get_contents('css/bootstrap.css');
$mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->Output();




?>