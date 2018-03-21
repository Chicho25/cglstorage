<?php
	$DB_Server = "localhost";
	$DB_Username ="root";
	$DB_Password = "";
	$DB_DBName = "cglstorage";

	$arrStatus = array("1" => "Active", "2" => "Pending", "3" => "Won", "4" => "Lost", "5" => "Cancelled", "6" => "Finished");

	$arrJobStatus = array("1" => "Scheduled Job", "2" => "Completed Job", "3" => "Potential Job", "4" => "Cancelled Job", "5" => "Maintenance", "6" => "Completed Maintenance", "7" => "Appointment", "8" => "Completed Appointment", "9" => "Vacation", "10" => "Completed Vacation");

	$arrUnits = array('1' => "US", '2' => "Metrics");
	$arrPermits = array('1' => "Not Needed", '2' => "Not Ordered", '3' => 'On Order', '4' => 'Received');
	$arrVehSection = array('' => '---Select---', '1' => 'Carrier', '2' => 'Upper');
	$arrFuelGrades = array('' => '---Select---', '1' => 'DIESEL', '2' => 'GASOLINE 91', '3' => 'GASOLINE 95');

	$ones = array(
		 "",
		 " one",
		 " two",
		 " three",
		 " four",
		 " five",
		 " six",
		 " seven",
		 " eight",
		 " nine",
		 " ten",
		 " eleven",
		 " twelve",
		 " thirteen",
		 " fourteen",
		 " fifteen",
		 " sixteen",
		 " seventeen",
		 " eighteen",
		 " nineteen"
		);

		$tens = array(
		 "",
		 "",
		 " twenty",
		 " thirty",
		 " forty",
		 " fifty",
		 " sixty",
		 " seventy",
		 " eighty",
		 " ninety"
		);

		$triplets = array(
		 "",
		 " thousand",
		 " million",
		 " billion",
		 " trillion",
		 " quadrillion",
		 " quintillion",
		 " sextillion",
		 " septillion",
		 " octillion",
		 " nonillion"
		);
///////////////////////////////////////////////////////////////////////////////////////
?>
