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
      <input type="checkbox" id="k2" value="33" name="c[]" onClick="che(this.id)">
      <input type="checkbox" id="k3" value="36" name="c[]" onClick="che(this.id)">
      <div id="k0">
      
      </div>
      <input type="submit" name="Enviar" value="Enviar">
    </form>
    <script>
    function che(x){
      if (document.querySelector("#"+x).checked) {
      var campo = document.querySelector("#"+x).value;
                  document.querySelector("#k0").innerHTML += "<input name='cc[]' value = '" + campo +"' id = '" + campo +"'>";
      }else{
        var ultimo = document.querySelector('#'+x).value;
            document.body.removeChild("<input name='cc[]' value = '" + ultimo +"' id = '" + ultimo +"'>");
      }
    }
    </script>
  </body>
</html>
