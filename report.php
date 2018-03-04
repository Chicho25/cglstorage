<?php
    ob_start();
    $reportclass="class='active'";
    $statementAccclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $whreQte = "where (1=1)";
    $company = "";
      // $name = "";
      // if(isset($_POST['cname']) && $_POST['cname'] != "")
      // {
      //   $where.=" and  (customer.name LIKE '%".$_POST['cname']."%' OR quote.trackingno LIKE '%".$_POST['cname']."%' OR quote.shipper LIKE '%".$_POST['cname']."%'  OR quote.id LIKE '%".$_POST['cname']."%' )";
      //   $name = $_POST['cname'];
      // }


      //$where.=" and  quote.stat =  2";
      $whreQte = "";
      $whreRcpt = "";
      if(isset($datefrom) && $datefrom != "")
      {
        $whreQte.= " and quote.date >= '".$datefrom."'";
        $crtDatFrom =  $datefrom;
      }
      else
        $crtDatFrom =  date("Y-m-d");
      if(isset($dateto) && $dateto != "")
      {
        $whreQte.= " and quote.date <= '".$dateto.' 23:59:59'."'";
        $crtDatTo = $dateto;
      }
      else
        $crtDatTo = date("Y-m-d");

      $arrUser = GetRecords("select
                              quote.date as fecha,
                              quote.othervalue,
                              customer.name as nombre_cliente,
                              quote_detail.id_package,
                              quote_detail.price,
                              package.trackingno as codigo,
                              package.totaltopay as total_pagar,
                              (quote.othervalue + quote_detail.price) as total_cobrar
                              from quote inner join customer on quote.id_customer = customer.id
                              		       inner join quote_detail on quote_detail.id_quote = quote.id
                                         inner join package on package.id = quote_detail.id_package
                                         $whreQte");

?>
     <?php
      $bcName = Statement_Account;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <h5><?php echo Statement_Account?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">
                          <div class="col-sm-2 " id="data_1">
                            <div class="input-group date">
                                <input type="text" required="" class="form-control" name="datefrom" id="datefrom" value="<?php if(isset($crtDatFrom)){ echo $crtDatFrom;} ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                          <div class="col-sm-2 " id="data_2">
                            <div class="input-group date">
                                <input type="text" required="" class="form-control" name="dateto" id="dateto" value="<?php if($crtDatTo){ echo $crtDatTo;} ?>">
                                <span class="input-group-addon"><i class="fa fa-calendar"></i></span>
                            </div>
                          </div>
                          <div class="col-sm-2 m-b-xs ph0" >
                            <?php /* ?><select class="chosen-select form-control" name="customer" id="customer">
                                    <option value="">-----------</option>
                                    <?PHP
                                    $where = ($loggdUType != 'Master') ? " and id_user = ".$_SESSION['USER_ID']." and id_company = ".$_SESSION['USER_COMPANY'] : '';
                                    $arrKindMeetings = GetRecords("Select * from customer where stat=1 $where");
                                    foreach ($arrKindMeetings as $key => $value) {
                                      $kinId = $value['id'];
                                      if($value['membernumber'] != "")
                                        $kinDesc = $value['membernumber']."-".$value['name'];
                                      else
                                        $kinDesc = $value['name'];
                                      $selRoll = (isset($customer) && $customer == $kinId) ? 'selected' : '';
                                    ?>
                                    <option value="<?php echo $kinId?>" <?php echo $selRoll?>><?php echo $kinDesc?></option>
                                    <?php
                                }
                                    ?>
                                  </select><?php */ ?>
                          </div>
                          <!-- <div class="col-sm-2 m-b-xs" >
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $memberno?>" name="memberno">
                            </div>
                          </div> -->
                          <div class="col-sm-2 m-b-xs">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                            </div>
                          </div>
                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover statement-acc">
                              <thead>
                                <tr>
                                  <th><?php echo Statement_Date?></th>
                                  <th>Codigo</th>
                                  <th>Cliente</th>
                                  <th>Costo de compra</th>
                                  <th>Costo de venta</th>
                                  <th>Ganancia</th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP

                                $cobrar = 0;
                                $pagar = 0;
                                foreach ($arrUser as $key => $value) {

                                  //$dstype = ($value['desctype'] == 1) ? 'PAGO'  : 'FACTURA';
                                  //$debit = ($value['desctype'] == 2) ? $value['total']  : 0;
                                  //$credit = ($value['desctype'] == 1) ? ($value['total'] + $value['othervalue'])  : 0;
                                  //if($debit > 0)
                                    //$balance = $balance - $debit;
                                  //else
                                    //$balance = $balance + $credit;
                                ?>
                              <tr>
                                <td class="tbdata"> <?php echo $value['fecha']?> </td>
                                <td class="tbdata"> <?php echo $value['codigo']?> </td>
                                <td class="tbdata"> <?php echo $value['nombre_cliente']?> </td>
                                <td class="tbdata"> <?php echo number_format($value['total_pagar'],2)?> </td>
                                <td class="tbdata"> <?php echo number_format($value['total_cobrar'],2)?> </td>
                                <td class="tbdata"> <?php echo number_format($value['total_cobrar']-$value['total_pagar'],2)?> </td>
                                <?php $cobrar += $value['total_cobrar']; ?>
                                <?php $pagar += $value['total_pagar']; ?>
                              </tr>
                              <?php
                              }
                              ?>
                              </tbody>
                              <tr>
                                  <td class="tbdata" colspan="3"> <b>Totales</b> </td>
                                  <td class="tbdata"><b> <?php echo number_format($pagar,2)?></b> </td>
                                  <td class="tbdata"><b> <?php echo number_format($cobrar,2)?> </b></td>
                                  <td class="tbdata"><b> <?php echo number_format($cobrar-$pagar,2)?> </b></td>
                              </tr>
                            </table>
                            <br>
                            <span class="input-group-btn padder ">
                              <?php /* ?><button class="btn btn-success " onclick="window.location='statement-account-rep-print.php?crtDatFrom=<?php echo $crtDatFrom?>&crtDatTo=<?php echo $crtDatTo?>&customer=<?php echo $customer?>'"><?php echo Button_Print?></button>*/ ?>
                            </span>

<?php /*
    $currentDate = date("Y-m-d");
    $days30 = date('Y-m-d', strtotime("+30 days"));
    $days31 = date('Y-m-d', strtotime("+31 days"));
    $days60 = date('Y-m-d', strtotime("+60 days"));
    $days61 = date('Y-m-d', strtotime("+61 days"));
    $days90 = date('Y-m-d', strtotime("+90 days"));
    $days91 = date('Y-m-d', strtotime("+91 days"));

    if(isset($customer) && $customer > 0 )
    {
      $whreQte.= " and quote.id_customer = ".$customer."";
    }
    $getdays30 = GetRecords("
                            SELECT sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              where quote.date >= '".$currentDate."' and quote.date <= '".$days30."'
                               and quote.stat =  2 $whreQte and quote.id NOT IN (Select id_quote from receipt)
                              group by quote_detail.id_quote


                             ");

    $getdays31 = GetRecords("
                            SELECT sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              where quote.date >= '".$days31."' and quote.date <= '".$days60."'
                               and quote.stat =  2 $whreQte and quote.id NOT IN (Select id_quote from receipt)
                              group by quote_detail.id_quote


                             ");

    $getdays61 = GetRecords("
                            SELECT sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              where quote.date >= '".$days61."' and quote.date <= '".$days90."'
                               and quote.stat =  2 $whreQte and quote.id NOT IN (Select id_quote from receipt)
                              group by quote_detail.id_quote


                             ");

    $getdays91 = GetRecords("
                            SELECT sum(quote_detail.price) as total
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              where quote.date >= '".$days91."'
                               and quote.stat =  2 $whreQte and quote.id NOT IN (Select id_quote from receipt)
                              group by quote_detail.id_quote


                             ");

    $valdays30 = (isset($getdays30[0]) && $getdays30[0]['total']) ? $getdays30[0]['total'] : 0;
    $valdays31 = (isset($getdays31[0]) && $getdays31[0]['total']) ? $getdays31[0]['total'] : 0;
    $valdays61 = (isset($getdays61[0]) && $getdays61[0]['total']) ? $getdays61[0]['total'] : 0;
    $valdays90 = (isset($getdays91[0]) && $getdays91[0]['total']) ? $getdays91[0]['total'] : 0;

?>
                            <div class="table-responsive">
                              <table class="table table-striped table-bordered table-hover">
                                <tr>
                                  <td colspan="4" align="center"><h2><b><?php echo Statement_Total_to_Pay?> : <?php echo number_format(str_replace("-", "", $balance), 2)?></b></h2></td>
                                </tr>
                                <!-- <tr>
                                  <td align="center"><b>0-30 Days</b></td>
                                  <td align="center"><b>31-60 Days</b></td>
                                  <td align="center"><b>62-90 Days</b></td>
                                  <td align="center"><b>91 + Days</b></td>
                                </tr>
                                <tr>
                                  <td align="center"><?php echo round($valdays30,2)?></td>
                                  <td align="center"><?php echo round($valdays31,2)?></td>
                                  <td align="center"><?php echo round($valdays61,2)?></td>
                                  <td align="center"><?php echo round($valdays90,2)?></td>
                                </tr> -->
                              </table>
                            </div> <?php */ ?>
                        </div>
                  </div>
                </div>
            </div>
        </div>
    </div>
    <script type="text/javascript">
    function readURL(input) {

        if (input.files && input.files[0]) {
            var reader = new FileReader();

            reader.onload = function (e) {
                $('#img').show().attr('src', e.target.result);
            }

            reader.readAsDataURL(input.files[0]);
        }
    }
  </script>
<?php
  include("footer.php");
?>
