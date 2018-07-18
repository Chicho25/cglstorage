<nav class="navbar-default navbar-static-side" role="navigation">
        <div class="sidebar-collapse">
            <ul class="nav metismenu" id="side-menu">
                <li class="nav-header">
                    <div class="dropdown profile-element"> <span>
                            <!-- <img alt="image" class="img-circle" src="img/profile_small.jpg" /> -->
                             </span>
                        <a data-toggle="dropdown" class="dropdown-toggle" href="#">
                            <span class="clear"> <span class="block m-t-xs"> <strong class="font-bold"><?php echo $_SESSION['USER_NAME']?></strong>
                             </span> <span class="text-muted text-xs block"><?php echo $_SESSION['USER_ROLE']?> <b class="caret"></b></span> </span> </a>
                        <ul class="dropdown-menu animated fadeInRight m-t-xs">
                            <li><a href="logout.php"><?php echo Log_out?></a></li>
                        </ul>
                    </div>
                    <div class="logo-element">
                        IN+
                    </div>
                </li>

                <?php if($loggdUType == "Master") : ?>
                <li  <?php if(isset($userclass)) echo $userclass;?>>
                    <a href="#"><i class="fa fa-user"></i><span class="nav-label"><?php echo Users?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerclass)) echo $registerclass;?>>
                          <a href="register.php">
                            <?php echo Register_User?>
                          </a>
                        </li>
                        <li <?php if(isset($userlistclass)) echo $userlistclass;?>>
                          <a href="users.php">
                            <?php echo User_List?>
                          </a>
                        </li>
                    </ul>
                </li>
                <li  <?php if(isset($companyclass)) echo $companyclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Company?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerCompanyclass)) echo $registerCompanyclass;?>>
                          <a href="register-company.php">
                            <?php echo Register_Company?>
                          </a>
                        </li>
                        <li <?php if(isset($editCompanyclass)) echo $editCompanyclass;?>>
                          <a href="company.php">
                            <?php echo Company_List?>
                          </a>
                        </li>
                    </ul>
                </li>

                <?php endif;?>
                <li  <?php if(isset($membershipclass)) echo $membershipclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Membership?><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerMembershipclass)) echo $registerMembershipclass;?>>
                          <a href="register-membership.php">
                            <?php echo Register_Membership?>
                          </a>
                        </li>
                        <li <?php if(isset($editMembershipclass)) echo $editMembershipclass;?>>
                          <a href="membership.php">
                            <?php echo Membership_List?>
                          </a>
                        </li>
                    </ul>
                </li>
                <li  <?php if(isset($countryclass)) echo $countryclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Customer?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerCstmclass)) echo $registerCstmclass;?>>
                          <a href="register-customer.php">
                            <?php echo Register_Customer?>
                          </a>
                        </li>
                        <li <?php if(isset($editCstmclass)) echo $editCstmclass;?>>
                          <a href="customer.php">
                            <?php echo Customer_List?>
                          </a>
                        </li>
                    </ul>
                </li>
                <li  <?php if(isset($packageclass)) echo $packageclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Packages?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerPackageclass)) echo $registerPackageclass;?>>
                          <a href="register-package.php">
                            <?php echo Register_Package?>
                          </a>
                        </li>
                        <li <?php if(isset($editPackageclass)) echo $editPackageclass;?>>
                          <a href="package.php">
                            <?php echo Package_List?>
                          </a>
                        </li>
                        <li <?php if(isset($importPackageclass)) echo $importPackageclass;?>>
                          <a href="import-package.php">
                            <?php echo Import_Package?>
                          </a>
                        </li>
                        <li <?php if(isset($listimportPackageclass)) echo $listimportPackageclass;?>>
                          <a href="list-import-package.php">
                            <?php echo 'Lista Importacion';?>
                          </a>
                        </li>
                        <li <?php if(isset($groupPackageEmailclass)) echo $groupPackageEmailclass;?>>
                          <a href="group-package-email.php">
                            <?php echo Send_Packages_Email?>
                          </a>
                        </li>
                    </ul>
                </li>

                <li  <?php if(isset($quoteclass)) echo $quoteclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Quote?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <?php /* ?><li <?php if(isset($registerQuoteclass)) echo $registerQuoteclass;?>>
                          <a href="register-quote.php">
                            <?php echo Register_Quote?>
                          </a>
                        </li><?php */ ?>
                        <li <?php if(isset($editQuoteclass)) echo $editQuoteclass;?>>
                          <a href="quote.php">
                            <?php echo Quote_List?>
                          </a>
                        </li>
                    </ul>
                </li>

                <li  <?php if(isset($receiptclass)) echo $receiptclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Receipt?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($registerReceiptclass)) echo $registerReceiptclass;?>>
                          <a href="register-receipt.php">
                            <?php echo Register_Receipt?>
                          </a>
                        </li>
                        <li <?php if(isset($editReceiptclass)) echo $editReceiptclass;?>>
                          <a href="receipt.php">
                            <?php echo Receipt_List?>
                          </a>
                        </li>
                    </ul>
                </li>
                <li  <?php if(isset($reportclass)) echo $reportclass;?>>
                    <a href="#"><i class="fa fa-bar-chart-o"></i><span class="nav-label"><?php echo Report?></span><span class="fa arrow"></span></a>
                    <ul class="nav nav-second-level collapse">

                        <li <?php if(isset($statementAccclass)) echo $statementAccclass;?>>
                          <a href="statement-account-rep.php">
                            <?php echo Statement_Account?>
                          </a>
                        </li>
                        <?php /* ?>
                        <li <?php if(isset($statementTAccclass)) echo $statementTAccclass;?>>
                          <a href="report_pakect.php">
                            <?php echo 'Reporte Paquetes';?>
                          </a>
                        </li>
                        <?php */ ?>
                    </ul>
                </li>

            </ul>

        </div>
    </nav>
