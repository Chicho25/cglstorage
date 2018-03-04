<?php
    include("include/config.php");
    include("include/defs.php");
    include("header.php");?>

	<section id="content">
          <section class="vbox">
            <section class="scrollable padder">
              <div class="row">
                <div class="col-sm-12">
                	<form class="form-horizontal" action="" data-validate="parsley" method="post" enctype="multipart/form-data" novalidate>
                      <section class="panel panel-default">
                        <header class="panel-heading">
                          <span class="h4">Registro de clientes</span>
                        </header>
                        <div class="panel-body">

                          <div class="form-group required">
                            <label class="col-lg-4 text-right control-label font-bold">Lista de Clientes</label>
                            <div class="col-lg-4">
                                <label class="btn yellow btn-default">
                                <input type="file" name="list_susp" required="required" accept=".xlsx">
                                </label>
                            </div>
                          </div>
                        <footer class="panel-footer text-right bg-light lter">
                          <button type="submit" onclick="document.getElementById('llamado').style.display='block'" name="submitCampaign" class="btn btn-primary btn-s-xs">Crear</button>
                        </footer>
                      </section>
                    </form>
                  </div>
              </div>
              <?php if(isset($_POST['submitCampaign'])){  ?>
              <table border="1" width="100%">
              	<thead>
                	  <tr>
                    	<th><center><strong>A</strong></center></th>
                        <th><center><strong>B</strong></center></th>
                        <th><center><strong>C</strong></center></th>
                        <th><center><strong>D</strong></center></th>
                  			<th><center><strong>E</strong></center></th>
                  			<th><center><strong>F</strong></center></th>
                        <th><center><strong>G</strong></center></th>
                  			<th><center><strong>H</strong></center></th>
                  			<th><center><strong>I</strong></center></th>
                    </tr>
                  	<tr>
                    	  <th>Numero Cliente</th>
                        <th>Cliente</th>
                        <th>Telefono</th>
                        <th>Direccion</th>
                  			<th>Correo Eectronico</th>
                  			<th>HBD</th>
                        <th>Tarifa especial</th>
                        <th>Referido por</th>
                        <th>CGL/DTC</th>
                    </tr>
              	</thead>
                  <tbody>
              <?php require_once 'PHPExcel/Classes/PHPExcel/IOFactory.php';

                    $objPHPExcel = PHPExcel_IOFactory::load('bd.xls');
              			$objHoja=$objPHPExcel->getActiveSheet()->toArray(true,true,true,true,true,true,true,true,true);
                    $numero_contar = 0;
                    foreach ($objHoja as $iIndice=>$objCelda) {
                      $numero_contar++;
                      if($numero_contar == 1 || $numero_contar == 2){
                         continue;
                      }
                      echo '
                        <tr>
                          <td>'.$objCelda['A'].'</td>
                          <td>'.$objCelda['B'].'</td>
                          <td>'.$objCelda['C'].'</td>
                          <td>'.$objCelda['D'].'</td>
                          <td>'.$objCelda['E'].'</td>
                          <td>'.$objCelda['F'].'</td>
                          <td>'.$objCelda['G'].'</td>
                          <td>'.$objCelda['H'].'</td>
                          <td>'.$objCelda['I'].'</td>
                        </tr>';

                      $numero_cliente=$objCelda['A'];
                      $cliente=$objCelda['B'];
                      $telefono=$objCelda['C'];
                      $direcion=$objCelda['D'];
                      $email=$objCelda['E'];
                      $hbd=$objCelda['F'];
                      $tarifa_especial=$objCelda['G'];
                      $referido_por=$objCelda['H'];
                      $cgl=$objCelda['I'];

                      $arrVal = array(
                                    "membernumber" => $numero_cliente,
                                    "name" => $cliente,
                                    "phone" => $telefono,
                                    "address" => $direcion,
                                    "email" => $email,
                                    "contact" => $referido_por,
                                    "hbd" => date("Y-m-d H:i:s"),
                                    "created_on" => date("Y-m-d H:i:s"),
                                    "stat" => 1,
                                    "tarifa_especial" => $tarifa_especial
                                   );
                            InsertRec("customer", $arrVal);
                      }
                  } ?>
                </tbody>
              </table>
              <?php if(isset($numero_contar)){
                       echo "Se han registrado ".$numero_contar." Sospechosos";
                       UpdateRec("campaign", "id = ".$nId, array("total_listado" => $numero_contar)); } ?>
            </section>
        </section>
    </section>
<?php include("footer.php"); ?>
