<?php 
    
    include("include/config.php"); 
    include("include/defs.php"); 
    include("translation/constants.php"); 
    
    $loggdUType = current_user_type();
    $arrCompanyInfo = GetRecord("company", "id = ".$_SESSION['USER_COMPANY']);

    $whreQte = "";
      $whreRcpt = "";
      if($datefrom != "")
      {
        $whreQte.= " and quote.date >= '".$datefrom."'";
        $whreRcpt.= " and receipt.receive_date >= '".$datefrom."'";
        $crtDatFrom =  $datefrom;
      }
      else
        $crtDatFrom =  date("Y-m-d");
      if($dateto != "")
      {
        $whreQte.= " and quote.date <= '".$dateto."'";
        $whreRcpt.= " and receipt.receive_date <= '".$dateto."'";
        $crtDatTo = $dateto;
      }
      else
        $crtDatTo = date("Y-m-d");
      
      if($customer > 0 )
      {
        $whreQte.= " and quote.id_customer = ".$customer."";
        $whreRcpt.= " and receipt.id_customer = ".$customer."";
        $getCustData = GetRecord("customer", "id = ".$customer);
        $customerName = $getCustData['name'];
        $contactName = $getCustData['contact'];
      }
      else
      {
        $customerName = "";
        $contactName = "";
      }

      if($memberno != "" )
      {
        $whreQte.= " and customer.membernumber = '".$memberno."'";
        $whreRcpt.= " and customer.membernumber = '".$memberno."'";
      }

      $arrUser = GetRecords("
                            Select * from (  
                            SELECT quote.stat, quote.id, quote.othervalue, quote.date, customer.name as CName, sum(quote_detail.price) as total, '1' as desctype
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              $where and quote.stat =  2 $whreQte
                              group by quote_detail.id_quote

                            union all

                            SELECT receipt.stat, receipt.id, 0 as othervalue, receipt.receive_date as date, customer.name as CName, sum(receipt_detail.amount) as total, '2' as desctype
                            from receipt
                            inner join quote on quote.id = receipt.id_quote
                            inner join receipt_detail on receipt_detail.id_receipt = receipt.id
                            inner join customer on customer.id = receipt.id_customer
                              $where and receipt.stat =  1 $whreRcpt
                              group by receipt_detail.id_receipt
                              ) tblMain order by tblMain.date
                             ");

    
    $html = '
  <body onload="showPrint()">
  <div class="wrapper wrapper-content animated fadeInRight ecommerce">
      <div class="row">
        <div class="col-lg-12">
          <div class="ibox float-e-margins">
                    <div class="ibox-title" align="center">
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
                              <p><b>Fecha: </b><span style="border-bottom: 1px solid #000000; ">'.date("Y-m-d").'</span></p>';
                              if($customerName != "")
                              {
                                $html.='<p><b><br>Cliente:</b> '.$customerName.'<br></p>';
                                $html.='<p><b><br>Nombre de Contacto:</b> '.$contactName.'</p>';
                              }
                          $html.='
                          </td>
                        </tr>
                      </table>  
                      <table width="100%">
                        <tr>
                          <td width="100%" align="center">
                            <h3>ESTADO DE CUENTA</h3>
                          </td>
                        </tr>

                          <tr>
                          <td  align="center">
                              <p><br><b>Rango de Fecha   Desde</b> <span style="border-bottom: 1px solid #000000; ">'.$crtDatFrom.'</span>   <b>Hasta</b> <span style="border-bottom: 1px solid #000000; ">'.$crtDatTo.'</span></p>
                          </td>
                        </tr>
                      </table>    
                    </div>
                    <br>
                    <div class="ibox-content">
                        
                            <table align="left" width="100%"  >
                              <thead>
                                <tr>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Date.'</th>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Reference.'</th>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Description.'</th>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Debit.'</th>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Credit.'</th>
                                  <th align="left" style="border-bottom: 1px solid #000000; ">'.Statement_Balance.'</th>
                                </tr>
                              </thead>
                              <tbody>
                                ';
                                $i=1;
                                $totaltopay = 0;
                                $balance = 0;
                                foreach ($arrUser as $key => $value) {

                                 $dstype = ($value['desctype'] == 1) ? 'INVOICE'  : 'PAYMENT';
                                  $debit = ($value['desctype'] == 2) ? $value['total']  : 0;
                                  $credit = ($value['desctype'] == 1) ? ($value['total'] + $value['othervalue'])  : 0;
                                  if($debit > 0)
                                    $balance = $balance - $debit;
                                  else
                                    $balance = $balance + $credit;
                                     
                                $html.='
                                    
                                    <tr>';
                                        $html.='<td >'.$value['date'].'</td>';
                                        $html.='<td>'.$value['id'].'</td>';
                                        $html.='<td>'.$dstype.'</td>';
                                        $html.='<td>'.number_format($debit,2).'</td>';
                                        $html.='<td>'.number_format($credit,2).'</td>';
                                        $html.='<td>'.number_format($balance,2).'</td>';
                                      
                                      $html.='                                    
                                      
                                    </tr>';
                                }
                                
                              $html.='


                              </tbody>
                              <tfoot>
                                <tr>
                                 <td align="right" width="100%" colspan="6" style="border-top: 1px solid #000000; "><b>'.Statement_Total_to_Pay.' : </b>'.str_replace("-", "", number_format($balance,2)).'</td>
                                </tr> 
                              </tfoot>
                           </table>
                        </div>
                      </div>      
            </div>
            
        </div>    

    </div>
    </body>';
    

  
include("pdf/mpdf.php");
  $mpdf=new mPDF(); 
$mpdf->WriteHTML($html);
$stylesheet = file_get_contents('css/bootstrap.css');
$mpdf->WriteHTML($stylesheet,1);  // The parameter 1 tells that this is css/style only and no body/html/text

$mpdf->Output();




?>
<script type="text/javascript">
    function showPrint() {

        window.print();
    }
  </script>