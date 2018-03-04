<?php include("include/config.php");
      include("include/defs.php"); ?>
<!DOCTYPE html>
<html>
  <head>
    <meta charset="utf-8">
    <title>yomo</title>
  </head>
  <?php $registro_paquete = GetRecords("Select * from package where id_customer = 32 and stat = 1"); ?>
  <body>
    <?php
      $i=0;
      foreach ($registro_paquete as $key => $value) { ?>
      <input type="text" size="5" name="precio" id="precio<?php echo $value['id']; ?>" onkeyup="obtenerSumaPrice();" value="<?php echo number_format($value['totaltopay'],2); ?>">
      <br>
  <?php $i++; }  ?>
  <input type='text' size='5' class='form-control' id='resultadoPrecio'>
  <script>
      function obtenerSumaPrice()
      {
          document.getElementById('resultadoPrecio').value=
          <?php if($i==1){
          foreach ($registro_paquete as $key => $value) {  ?>
          parseFloat(document.getElementById('precio<?php echo $value['id']; ?>').value);
          <?php }
        }else{
          foreach ($registro_paquete as $key => $value) {
          ?>
          +parseFloat(document.getElementById('precio<?php echo $value['id']; ?>').value)

        <?php }
        } ?>
          ;
      }
  </script>
  </body>
</html>
