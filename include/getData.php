<?php
	include("config.php"); 
    include("defs.php");
	
	
	$loggdUType = current_user_type();
	$reqType = $_POST['reqtype'];
	switch($reqType)
	{
		
		case "getpackages" : 
		
			$customer = $_POST['id'];
			$arrPrice = GetRecords("SELECT * from package
										where id_customer = ".$customer." and stat = 1
										 ");

				if(count($arrPrice) > 0)
					echo json_encode($arrPrice);
				else
					echo '';
		break;

		case "getcustomerprice" : 
			$customer = $_POST['customer'];
			$weighttocollect = $_POST['weighttocollect'];
			if($customer > 0)
			{
				$getMemberId = GetRecord("customer", "id = ".$customer);

				
				$arrPrice = GetRecords("SELECT membership_price.perpund_price from membership_price
										inner join membership on membership.id =  membership_price.id_membership
										where membership.id = ".$getMemberId['id_membership']." 
										and ".$weighttocollect." between membership_price.initial_range and membership_price.last_range ");

				if(isset($arrPrice[0]['perpund_price']) && $arrPrice[0]['perpund_price'] != 0)
					echo $arrPrice[0]['perpund_price'];
				else
					echo '';
				
			}	
		break;

		case "getlastfuelodometer" : 
			$regId = $_POST['id'];
			$sectype = $_POST['sectype'];
			if($regId > 0)
			{
				$arrUser = GetRecords("SELECT odometer as odm, enginehour from fuel 
										where id_vehicle = ".$regId." and id_vehsection = ".$sectype." order by id desc limit 1");
				if(isset($arrUser[0]['odm']) && $arrUser[0]['odm'] != 0)
					echo $arrUser[0]['odm'].":".$arrUser[0]['enginehour'];
				else
					echo 0;
				
			}	
		break;


		case "showcustomerlink" : 
			$regId = $_POST['id'];
			if($regId > 0)
			{
				$arrUser = GetRecords("SELECT customer.id, customer.name as cname  from customer inner join contact on contact.customer =  customer.id
								where contact.customer = ".$regId." ");
				if(isset($arrUser[0]['cname']) && $arrUser[0]['cname'] != "")
					echo "<a href='edit-customer.php?id=".$arrUser[0]['id']."'>".$arrUser[0]['cname']."</a>";
				else
					echo 0;
				
			}	
		break;
		
	}
?>