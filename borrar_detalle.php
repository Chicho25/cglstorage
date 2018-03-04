<?php
include("include/config.php");
include("include/defs.php");

DeleteRec("package", "id=".$_GET['id']);

header("Location: register-quote.php?customer=".$_GET['id_customer']);?>
