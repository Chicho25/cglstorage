<?php 
    ob_start();
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";
    
    include("include/config.php"); 
    include("include/defs.php"); 
    $loggdUType = current_user_type();
    
    include("header.php"); 

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
      $arrUser = GetRecords("SELECT quote.othervalue, quote.stat, quote.id, quote.date, customer.name as CName, sum(quote_detail.price) as total
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
                        <h5><?php echo Quote_List?></h5>
                    </div>
                    <div class="ibox-content">
                      <form method="post">
                        <div class="row wrapper ">
                          <div class="col-sm-3 pull-left">
                            <span class="input-group-btn padder ">
                              <button type="button" class="btn btn-success btn-rounded" onclick="window.location='register-quote.php'"?><?php echo Quote_Add?></button>
                            </span>
                          </div>
                          <div class="col-sm-3 m-b-xs pull-right">
                            <div class="input-group">
                              <span class="input-group-btn padder "><button class="btn btn-success btn-rounded"><?php echo Search?></button></span>
                            </div>  
                          </div>
                          <div class="col-sm-2 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="text" class="input-s input-sm form-control" value="<?php echo $name?>" name="cname">
                            </div>
                          </div>
                          <div class="col-sm-3 m-b-xs ph0 pull-right" >
                            <div class="input-group">
                              <input type="radio" name="status" value="1" <?php echo $c=(isset($status) && $status == 1) ? 'checked' : ''?> > <?php echo Active?>
                              <input type="radio" name="status" value="2" <?php echo $c=(isset($status) && $status == 2) ? 'checked' : ''?> > <?php echo Invoice?>
                              <input type="radio" name="status" value="0" <?php echo $c=(isset($status) && $status == 0) ? 'checked' : ''?> > <?php echo Archived?>
                            </div>
                          </div>
                          
                        </div>
                      </form>
                        <div class="table-responsive">
                            <table class="table table-striped table-bordered table-hover dataTables-example">
                              <thead>
                                <tr>
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
                                foreach ($arrUser as $key => $value) {
                                  
                                  $status = ($value['stat'] == 1) ? 'Active'  : (($value['stat'] == 2 ) ? 'Invoiced' : 'In Active');
                                ?> 
                              <tr> 
                                  <td class="tbdata"> <?php echo $value['id']?> </td>
                                  <td class="tbdata"> <?php echo $value['CName']?> </td>
                                  <td class="tbdata"> <?php echo $value['date']?> </td>
                                  <td class="tbdata"> <?php echo round($value['total'] + $value['othervalue'] , 2)?> </td>
                                  <td class="tbdata"> <?php echo $status?> </td>
                                  <td> 
                                    <?php if($value['stat'] != 2) : ?>
                                    <button type="button" onclick="window.location='edit-quote.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_Edit?></button> 
                                  
                                    <?php endif; ?>
                                    <?php if($value['stat'] == 2) : ?>
                                      <button type="button" onclick="window.location='view-quote.php?id=<?php echo $value['id']?>';" class="btn green btn-info"><?php echo Button_View?></button> 
                                  
                                    <?php endif; ?>
                                  <a href='print-quote.php?id=<?php echo $value['id']?>' target="_blank" class="btn green btn-info"><?php echo Button_Print?></a>
                                  <a href='pdf_factura.php?id=<?php echo $value['id']?>' target="_blank" class="btn green btn-info"><?php echo 'Ver';?></a> 
                                  <?php if($value['stat'] != 2) : ?>
                                  <button type="button" onclick="window.location='change-quote-status.php?id=<?php echo $value['id']?>';" class="btn green btn-warning"><?php echo Button_Invoice?></button> 
                                <?php endif; ?>
                                  </td>
                              </tr>
                              <?php
                                $i++;
                              }
                              ?>
                              </tbody>
                            </table>
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
