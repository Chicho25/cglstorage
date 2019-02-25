<?php /*

  $DB_Server = "localhost";
	$DB_Username ="cglstorage";
	$DB_Password = "r-o7DTkV.7Ft";
	$DB_DBName = "cglstorage";

 ?>
<?php $conexion = mysqli_connect($DB_Server, $DB_Username, $DB_Password, $DB_DBName); ?>
<?php //$conexion = mysqli_connect("localhost", "root", "", "cglstorage"); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>Excel Prueba</title>
  </head>
  <body>
    <p class="formulario">* Selecciona el archivo Excel a Importar (.xlsx): cambio </p><br/>

<img src="img/iconos/excel.png" width="50" height="50" /><p>

<form name="importa" method="post" action="" enctype="multipart/form-data" >
<input type="file" name="excel" />
<input type='submit' name='enviar' value="Importar" />
<input type="hidden" value="upload" name="action" />
</form>
<p>

<?php
extract($_POST);
if ($_POST['action'] == "upload") //si action tiene como valor UPLOAD haga algo (el value de este hidden es es UPLOAD iniciado desde el value
{
//cargamos el archivo al servidor con el mismo nombre(solo le agregue el sufijo bak_)
$archivo = $_FILES['excel']['name']; //captura el nombre del archivo
$tipo = $_FILES['excel']['type']; //captura el tipo de archivo (2003 o 2007)

$destino = "bak_".$archivo; //lugar donde se copiara el archivo

if (copy($_FILES['excel']['tmp_name'],$destino)) //si dese copiar la variable excel (archivo).nombreTemporal a destino (bak_.archivo) (si se ha dejado copiar)
{
echo "Archivo Cargado Con Exito";
}
else
{
echo "Error Al Cargar el Archivo";
}

////////////////////////////////////////////////////////
if (file_exists ("bak_".$archivo)) //validacion para saber si el archivo ya existe previamente
{
/*INVOCACION DE CLASES Y CONEXION A BASE DE DATOS
/** Invocacion de Clases necesarias 
require_once('PHPExcel/Classes/PHPExcel.php');
require_once('PHPExcel/Classes/PHPExcel/Reader/Excel2007.php');
// Cargando la hoja de calculo
$objReader = new PHPExcel_Reader_Excel2007(); //instancio un objeto como PHPExcelReader(objeto de captura de datos de excel)
$objPHPExcel = $objReader->load("bak_".$archivo); //carga en objphpExcel por medio de objReader,el nombre del archivo
$objFecha = new PHPExcel_Shared_Date();

// Asignar hoja de excel activa
$objPHPExcel->setActiveSheetIndex(0); //objPHPExcel tomara la posicion de hoja (en esta caso 0 o 1) con el setActiveSheetIndex(numeroHoja)

// Llenamos un arreglo con los datos del archivo xlsx
$i=1; //celda inicial en la cual empezara a realizar el barrido de la grilla de excel
$param=0;
$contador=0;
while($param==0) //mientras el parametro siga en 0 (iniciado antes) que quiere decir que no ha encontrado un NULL entonces siga metiendo datos
{

$id_user=$objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue();
$id_company=$objPHPExcel->getActiveSheet()->getCell('B'.$i)->getCalculatedValue();
$name=$objPHPExcel->getActiveSheet()->getCell('C'.$i)->getCalculatedValue();
$phone=$objPHPExcel->getActiveSheet()->getCell('D'.$i)->getCalculatedValue();
$cellno=$objPHPExcel->getActiveSheet()->getCell('E'.$i)->getCalculatedValue();
$address=$objPHPExcel->getActiveSheet()->getCell('F'.$i)->getCalculatedValue();
$email=$objPHPExcel->getActiveSheet()->getCell('G'.$i)->getCalculatedValue();
$membernumber=$objPHPExcel->getActiveSheet()->getCell('H'.$i)->getCalculatedValue();
$contact=$objPHPExcel->getActiveSheet()->getCell('I'.$i)->getCalculatedValue();
$id_membership=$objPHPExcel->getActiveSheet()->getCell('J'.$i)->getCalculatedValue();
$stat=$objPHPExcel->getActiveSheet()->getCell('K'.$i)->getCalculatedValue();
$created_on=$objPHPExcel->getActiveSheet()->getCell('L'.$i)->getCalculatedValue();
$tarifa_especial=$objPHPExcel->getActiveSheet()->getCell('M'.$i)->getCalculatedValue();
$hbd=$objPHPExcel->getActiveSheet()->getCell('N'.$i)->getCalculatedValue();

$c= $conexion -> query("insert into customer (id_user,
                            id_company,
                            name,
                            phone,
                            cellno,
                            address,
                            email,
                            membernumber,
                            contact,
                            id_membership,
                            stat,
                            created_on,
                            tarifa_especial,
                            hbd)values('$id_user',
                                       '$id_company',
                                       '$name',
                                       '$phone',
                                       '$cellno',
                                       '$address',
                                       '$email',
                                       '$membernumber',
                                       '$contact',
                                       '$id_membership',
                                       '$stat',
                                       '$created_on',
                                       '$tarifa_especial',
                                       '$hbd')");
//$conexion -> query($c);

if($objPHPExcel->getActiveSheet()->getCell('A'.$i)->getCalculatedValue()==NULL) //pregunto que si ha encontrado un valor null en una columna inicie un parametro en 1 que indicaria el fin del ciclo while
{
$param=1; //para detener el ciclo cuando haya encontrado un valor NULL
}
$i++;
$contador=$contador+1;
}
$totalIngresados=$contador-1; //(porque se se para con un NULL y le esta registrando como que tambien un dato)
echo "- Total elementos subidos: $totalIngresados ";
}
else//si no se ha cargado el bak
{
echo "Necesitas primero importar el archivo";}
unlink($destino); //desenlazar a destino el lugar donde salen los datos(archivo)
}

?>



</div>
  </body>
</html> */ ?>

<div class="modal fade bannerformmodal" tabindex="-1" role="dialog" aria-labelledby="bannerformmodal" aria-hidden="true" id="bannerformmodal">
  <div class="modal-dialog modal-sm">
    <div class="modal-content">
      <div class="modal-content">
        <div class="modal-header">
          <button type="button" class="close" data-dismiss="modal" aria-hidden="true">&times;</button>
          <h4 class="modal-title" id="myModalLabel">Contact Form</h4>
        </div>
        <div class="modal-body">
          <form id="requestacallform" method="POST" name="requestacallform">

            <div class="form-group">
              <div class="input-group">                               
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input id="first_name" type="text" class="form-control" placeholder="First Name" name="first_name"/>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">                               
                <span class="input-group-addon"><i class="fa fa-user"></i></span>
                <input id="last_name" type="text" class="form-control" placeholder="Last Name" name="last_name"/>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">                               
                <span class="input-group-addon"><i class="fa fa-envelope"></i></span>
                <input id="email1" type="text" class="form-control" placeholder="Email" name="email1" onchange="validateEmailAdd();"/>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">                               
                <span class="input-group-addon"><i class="fa fa-group"></i></span>
                <input id="company_name_c" type="text" class="form-control" placeholder="Company Name" name="company_name_c"/>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">                               
                <span class="input-group-addon"><i class="fa fa-phone"></i></span>
                <input id="phone_mobile" type="text" class="form-control" placeholder="Mobile" name="phone_mobile"/>
              </div>
            </div>
            <div class="form-group">
              <div class="input-group">
                <span class="input-group-addon"><i class="fa fa-building-o"></i></span>
                <select class="form-control" name="monthly_rental" class="selectpicker">
                  <option>How many seats do you have available?</option>
                  <option>10-50</option>
                  <option>50-100</option>
                  <option>100-200</option>
                  <option>200-500</option>
                  <option>500+</option>
                </select>
              </div>
            </div>
            <div class="control-group">
              <div class="controls">                     
                <textarea id="description" type="text" name="description"  placeholder="Description"></textarea>
              </div>
            </div>

          </form>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-blue">Submit</button>
        </div>        
      </div>
    </div>
  </div>
</div>
