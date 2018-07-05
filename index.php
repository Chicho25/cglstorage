<?php
    ob_start();
    session_start();
    include("include/config.php");
    include("include/defs.php");

    // it will never let you open index(login) page if session is set
     if(isset($_SESSION['USER_ID']) && $_SESSION['USER_ID'] !="")
     {
          header("Location: home.php");
          exit;
     }

    $errMSG="";

     if( isset($_POST['btn-login']) ) {

        $username = $_POST['username'];
        $password = encryptIt($_POST['password']);
        $username = strip_tags(trim($username));
        $password = strip_tags(trim($password));

        if(RecCount("users", "id_company = ".$distributor." and user = '".$username."' and password = '".$password."' and stat = 1") > 0)
        {

            $row = GetRecord("users", "id_company = ".$distributor." and user = '".$username."' and password = '".$password."' and stat = 1");
            $getRole = GetRecord("type_user", "id = ".$row['id_roll_user']." ");

            $_SESSION['USER_ID'] = $row['id'];
            $_SESSION['USER_NAME'] = $row['user'];
            $_SESSION['USER_ROLE'] = $getRole['name'];
            $_SESSION['USER_COMPANY'] = $row['id_company'];
            header("Location: home.php");

        }
        else
          $errMSG = '<div class="alert alert-danger"><a href="#" class="close" style="color:#000;" data-dismiss="alert">&times;</a><strong>Invalid Email or Password, Try again...!</strong></div>';
     }
?>
<!DOCTYPE html>
<html lang="es">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Logistic | Login</title>
    <link href="css/bootstrap.min.css" rel="stylesheet">
    <link href="font-awesome/css/font-awesome.css" rel="stylesheet">
    <link href="css/animate.css" rel="stylesheet">
    <link href="css/style.css" rel="stylesheet">
    <meta name="theme-color" content="#20449c"/>
    <link rel="manifest" href="manifest.json">
</head>
<body class="gray-bg">
    <div class="middle-box text-center loginscreen animated fadeInDown">
        <div>
            <img src="img/1.png" alt="">
            <p>Logistic.</p>
            <form class="m-t" role="form" method="POST">
                <div class="form-group">
                      <select class="form-control" name="distributor" required="required">
                        <?PHP
                        // if($loggdUType != "Master")
                        //   $arrKindMeetings = GetRecords("Select * from company where stat = 1");
                        // else
                          $arrKindMeetings = GetRecords("Select * from company where stat = 1");

                        foreach ($arrKindMeetings as $key => $value) {
                          $kinId = $value['id'];
                          $kinDesc = $value['name'];
                        ?>
                        <option value="<?php echo $kinId?>"><?php echo $kinDesc?></option>
                        <?php
                    }
                        ?>
                      </select>
                </div>
                <div class="form-group">
                    <input type="text" name="username" class="form-control" placeholder="Username" required="">
                </div>
                <div class="form-group">
                    <input type="password" name="password" class="form-control" placeholder="Password" required="">
                </div>
                <button type="submit" name="btn-login" class="btn btn-primary block full-width m-b">Login</button>
                <a href="#"><small>Forgot password?</small></a>
            </form>
        </div>
    </div>
    <!-- Mainly scripts -->
    <script src="js/jquery-3.1.1.min.js"></script>
    <script src="js/bootstrap.min.js"></script>
</body>
</html>
