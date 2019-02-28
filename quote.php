<?php
    ob_start();
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();

    include("header.php");

    $message = "";

    if (isset($_POST['deleteInvoice'])) {

      DeleteRec("quote","id =".$_POST['id_delete']);
      DeleteRec("quote_detail","id_quote =".$_POST['id_delete']);

      $message = '<div class="alert alert-danger">
                    <a href="#" class="close" data-dismiss="alert" aria-label="close">&times;</a>
                      <strong>Factura Eliminada</strong>
                    </div>';
    }

    if (isset($_POST['payInvoice'])) {

        $array_detail = array("id_invoice" => $_POST['id_invoice'],
                              "id_method" => $_POST['method'],
                              "descriptions" => $_POST['descriptions'],
                              "id_user" => $_SESSION['USER_ID'],
                              "date_time" => date("Y-m-d H:i:s"),
                              "stat" => 1);

        $id_pay_detail = InsertRec("pay_datail_invoice",$array_detail);

        if(isset($_FILES['attach']) && $_FILES['attach']['tmp_name'] != "")
              {
                  $target_dir = "attched/";
                  $target_file = $target_dir . basename($_FILES["attach"]["name"]);
                  $imageFileType = pathinfo($target_file,PATHINFO_EXTENSION);
                  $filename = $target_dir . $id_pay_detail.".".$imageFileType;
                  $filenameThumb = $target_dir . $id_pay_detail."_thumb.".$imageFileType;
                  if (move_uploaded_file($_FILES["attach"]["tmp_name"], $filename))
                  {
                      //makeThumbnailsWithGivenWidthHeight($target_dir, $imageFileType, $id_pay_detail, 200, 200);

                      UpdateRec("pay_datail_invoice", "id = ".$id_pay_detail, array("attched" => $filenameThumb));
                  }
              }
          UpdateRec("quote", "id = ".$_POST['id_invoice'], array("stat" => 2));
          UpdateRec("package", "id in (select
                                        id_package
                                        from
                                        quote_detail
                                        where
                                        id_quote = ".$_POST['id_invoice'].") ", array("stat" => 3));

          // nuemracion de la factura

          $array_num_invo = array("stat" => 1,
                                  "id_invoice" => $_POST['id_invoice'],
                                  "tipo" => 1);

          $numero_factura = InsertRec("number_invoice", $array_num_invo);

    }

    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }
    $where = "where (1=1)";
    $company = "";
    if($loggdUType != 'Master')
      $where.=" and quote.id_user = ".$_SESSION['USER_ID']." and quote.id_company = ".$_SESSION['USER_COMPANY'];
      $name = "";
      if(isset($_POST['cname']) && $_POST['cname'] != "")
      {
        $where.=" and  (customer.name LIKE '%".$_POST['cname']."%' OR quote.date LIKE '%".$_POST['cname']."%'  OR quote.id LIKE '%".$_POST['cname']."%' )";
        $name = $_POST['cname'];
      }
      if(isset($_POST['status']) && $_POST['status'] != "")
      {
        $where.=" and  quote.stat =  ".$_POST['status'];
        $status = $_POST['status'];
      }
      else
      {
        $where.=" and  quote.stat =  1";
        $status = 1;
      }
      if (isset($_POST['customer']) && $_POST['customer'] != '') {
         $where .= " and quote.id_customer =".$_POST['customer'];
      }
      $arrUser = GetRecords("SELECT quote.othervalue,
                                    quote.stat,
                                    quote.id,
                                    quote.date,
                                    customer.name as CName, sum(quote_detail.price) as total,
                                    quote.id_customer
                            from quote
                            inner join quote_detail on quote_detail.id_quote = quote.id
                            inner join customer on customer.id = quote.id_customer
                              $where
                              group by quote_detail.id_quote
                             ");

?>
     <?php
      $bcName = Quote_List;
      include("breadcrumb.php") ;
    ?>
    <div class="wrapper wrapper-content animated fadeInRight">
      <div class="row">
        <div class="col-lg-12">
                <div class="ibox float-e-margins">
                    <div class="ibox-title">
                        <?php echo $message; ?>
                        <h5><?php echo Quote_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form class="" action="" method="post">
                        <div class="col-sm-3 m-b-xs pull-right">
                          <div class="input-group">
                            <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                          </div>
                        </div>
                        <div class="col-lg-3 m-b-xs pull-right">
                            <select class="chosen-select form-control" name="customer">
                              <option value="">---------</option>
                              <?PHP
                              $arrKindMeetings = GetRecords("Select * from customer where stat=1");
                              foreach ($arrKindMeetings as $key => $value) {
                                $kinId = $value['id'];
                                if($value['membernumber'] != "")
                                  $kinDesc = $value['name']."-".$value['membernumber'];
                                else
                                  $kinDesc = $value['name'];
                              ?>
                              <option value="<?php echo $value['id']?>"><?php echo $kinDesc?></option>
                          <?php
                            }
                          ?>
                            </select>
                        </div>
                      </form>
                      <form method="post" method="post" action="invoice_selection.php">
                        <div class="row wrapper ">
                          <div class="col-sm-2 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register-quote.php'"?><?php echo Quote_Add?></button>
                            </span>
                          </div>
                          <div class="col-sm-2 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="submit" class="btn btn-success btn-rounded" name="pay_selection"><?php echo 'Pagar Seleccion';?></button>
                            </span>
                          </div>

                          <!--<div class="col-sm-3 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="radio" name="status" value="1" <?php echo $c=(isset($status) && $status == 1) ? 'checked' : ''?> > <?php echo Active?>
                              <input type="radio" name="status" value="2" <?php echo $c=(isset($status) && $status == 2) ? 'checked' : ''?> > <?php echo Invoice?>
                              <input type="radio" name="status" value="0" <?php echo $c=(isset($status) && $status == 0) ? 'checked' : ''?> > <?php echo Archived?>
                            </div>
                          </div>-->

                        </div>

                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
                                  <th><?php echo 'Check';?></th>
                                  <th><?php echo Quote_Id?></th>
                                  <th><?php echo Quote_Customer_Name?></th>
                                  <th><?php echo Quote_Date?></th>
                                  <th><?php echo Quote_GTotal?></th>
                                  <th><?php echo Status?></th>
                                  <th><?php echo Action?></th>
                                </tr>
                              </thead>
                              <tbody>
                              <?PHP
                                $i=1;
                                $suma_total = 0;
                                foreach ($arrUser as $key => $value) {

                                  $status = ($value['stat'] == 1) ? 'Active'  : (($value['stat'] == 2 ) ? 'Invoiced' : 'In Active');
                                ?>
                              <tr>
                                  <td class="tbdata"> <input type="checkbox" name="fact[]" value="<?php echo $value['id']?>" style="width:80px;"> </td>
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName'].' '.$value['id_customer']?> </td>
                                  <td class="tbdata"> <?php echo $value['date']?> </td>
                                  <td class="tbdata"> <?php echo round($value['total'] + $value['othervalue'] , 2)?> $</td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td>
                                    <?php if($value['stat'] != 2) : ?>
                                    <?php if($_SESSION['USER_ID'] == 30 || $_SESSION['USER_ID'] == 1){ ?>
                                    <button type="button" onclick="window.location='edit-quote.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button>
                                    <?php } ?>
                                    <?php endif; ?>
                                    <?php if($value['stat'] == 2) : ?>
                                      <button type="button" onclick="window.location='view-quote.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_View?></button>

                                    <?php endif; ?>
                                  <a href='print-quote.php?id=<?php echo $value['id']?>' target="_blank" class="btn green btn-info"><?php echo Button_Print?></a>
                                  <a href='pdf_factura.php?id=<?php echo $value['id']?>' target="_blank" class="btn green btn-info"><?php echo 'Ver';?></a>
                                  <?php if($value['stat'] != 2) : ?>
                                  <!--<a type="button" onclick="window.location='change-quote-status.php?id=<?php echo $value['id']?>';" class="btn green btn-warning"><?php /*echo Button_Invoice*/?></a>-->
                                  <a data-toggle="modal" data-target="#myModal3<?php echo $value['id']?>" class="btn btn-warning btn-info"><?php echo 'Pago';?></a>
                                      <div class="modal inmodal" id="myModal3<?php echo $value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                          <div class="modal-dialog">
                                          <div class="modal-content animated bounceInRight">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                              <h4 class="modal-title" style="color:red;"><?php echo 'Pago de Factura';?></h4>
                                            </div>
                                            <form></form>
                                            <form class="form-horizontal" action="" method="post" enctype="multipart/form-data">
                                            <div class="modal-body">
                                              <div class="row">
                                                <div class="form-group">
                                                  <label class="col-lg-4 text-right control-label">Metodo</label>
                                                  <div class="col-lg-6">
                                                    <select name="method" class="form-control">
                                                      <option value="">Seleccionar</option>
                                                      <option value="1">Efectivo</option>
                                                      <option value="2">Cheque</option>
                                                      <option value="3">ACH(Transferencia)</option>
                                                      <option value="4">Tarjeta</option>
                                                    </select>
                                                    <input type="hidden" name="id_invoice" value="<?php echo $value['id']?>">
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-lg-4 text-right control-label">Descripcion</label>
                                                  <div class="col-lg-6">
                                                    <textarea class="form-control" name="descriptions" id="" cols="20" rows="6"></textarea>
                                                  </div>
                                                </div>
                                                <div class="form-group">
                                                  <label class="col-lg-4 text-right control-label">Adjunto</label>
                                                  <div class="col-lg-6">
                                                    <input type="file" name="attach" class="form-control">
                                                  </div>
                                                </div>
                                              </div>
                                              </div>
                                              <div class="modal-footer">
                                              <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                              <button type="submit" class="btn btn-primary" name="payInvoice"><?php echo 'Pagar';?></button>
                                              </div>
                                            </form>
                                        </div>
                                      </div>
                                    </div>
                                  <?php endif; ?>
                                  <?php if($_SESSION['USER_ID'] == 30 || $_SESSION['USER_ID'] == 1){ ?>
                                  <a data-toggle="modal" data-target="#myModal2<?php echo $value['id']?>" class="btn btn-danger btn-info"><?php echo 'Eliminar';?></a>
                                  <?php } ?>
                                        <div class="modal inmodal" id="myModal2<?php echo $value['id']?>" tabindex="-1" role="dialog" aria-hidden="true">
                                          <div class="modal-dialog">
                                          <div class="modal-content animated bounceInRight">
                                            <div class="modal-header">
                                              <button type="button" class="close" data-dismiss="modal"><span aria-hidden="true">&times;</span><span class="sr-only"><?php echo Button_Close?></span></button>
                                              <h4 class="modal-title" style="color:red;"><?php echo 'Eliminar Factura';?></h4>
                                            </div>
                                            <form class="form-horizontal" action="" method="post">
                                            <div class="modal-body">
                                              <div class="row">
                                                <div class="form-group">
                                                  <label class="col-lg-4 text-right control-label" style="color:red;">Eliminar Factura</label>
                                                  <div class="col-lg-6" style="color:red;">
                                                    <p>
                                                      Esta seguro que desea borrar la factura?
                                                    </p>
                                                    <input type="hidden" name="id_delete" value="<?php echo $value['id']?>">
                                                  </div>
                                                </div>
                                              </div>
                                              </div>
                                              <div class="modal-footer">
                                              <button type="button" class="btn btn-white" data-dismiss="modal"><?php echo Button_Close?></button>
                                              <button type="submit" class="btn btn-primary" name="deleteInvoice"><?php echo 'Eliminar';?></button>
                                              </div>
                                            </form>
                                        </div>
                                      </div>
                                    </div>
                                  </td>
                              </tr>
                              <?php
                                $suma_total += round($value['total'] + $value['othervalue'] , 2);
                                $i++;
                              }
                              ?>
                              <tr>
                                  <td  colspan="3" class="tbdata" style="text-align:right;"> <b>Total: </b> </td>
                                  <td class="tbdata"> <?php echo $suma_total; ?> $</td>
                                  <td class="tbdata">  </td>
                                  <td>

                                  </td>
                              </tr>
                              </tbody>
                            </table>
                          </form>
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
