<?php
	require_once("AdyenService.php");
	$svc = new AdyenService();
	$resp = (object)[
		"code"=> 1,
		"message"=> "No Operation"
	];
	
	$action = $_GET['action'];
	
	
	switch($action){
		case "ss_pm":
			$request_json = file_get_contents('php://input');
			$paymentInfo = json_decode($request_json);
			
			$result = $svc->getSupportedPaymentMethods($paymentInfo);
			$resp->code = 0;
			$resp->message = "Successfull";
			$resp->result = $result;
		case "ss_create":
			$request_json = file_get_contents('php://input');
			$paymentInfo = json_decode($request_json);
			
			$paymentInfo->returnUrl = "http://ui/adyen/thank_you.php";
			$paymentInfo->referenceNumber = "ADYENPAY000000003";
			
			$result = $svc->createPaymentSession($paymentInfo);
			$resp->code = 0;
			$resp->message = "Successfull";
			$resp->result = $result;
		
		
	}
	echo json_encode($resp);