<?php
    $quoteclass="class='active'";
    $editQuoteclass="class='active'";

    include("include/config.php");
    include("include/defs.php");
    $loggdUType = current_user_type();


    if(!isset($_SESSION['USER_ID']))
     {
          header("Location: index.php");
          exit;
     }

     if(isset($_GET['id']) && $_GET['id'] > 0)
     {
      UpdateRec("quote", "id = ".$_GET['id'], array("stat" => 2));
      UpdateRec("package", "id in (select id_package from quote_detail
 where id_quote = ".$_GET['id'].") ", array("stat" => 3));
      echo "<script>alert('Quote invoiced successfully');
      window.location='quote.php';</script>";
     }
