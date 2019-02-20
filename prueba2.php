<?php
if (isset($_POST['express'])) {

$cadena = $_POST['express'];
$explodiado = explode(" ", $cadena);
$i = 0;
foreach ($explodiado as $key => $value) {
  $i++;
  echo $i.' '.$value.'<br>';
  }

}
 ?>
<!DOCTYPE html>
<html lang="en" dir="ltr">
  <head>
    <meta charset="utf-8">
    <title>Prueba de Cadena</title>
  </head>
  <body>
    <form class="" action="" method="post">
      <textarea name="express" rows="8" cols="80"></textarea>
      <input type="submit" name="Enviar" value="Enviar">
    </form>
  </body>
</html>
