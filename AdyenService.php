<?php
class AdyenService{
	protected $merchantAccount = "AdyenRecruitmentCOM";
	protected $myApiKey = 'AQEyhmfxKonIYxZGw0m/n3Q5qf3VaY9UCJ14XWZE03G/k2NFikzVGEiYj+4vtN01BchqAcwQwV1bDb7kfNy1WIxIIkxgBw==-JtQ5H0iXtu8rqQMD6iAb33gf2qZeGKGhrMpyQAt9zsw=-3wAkV)*$kP%bCcSf';
	//Method to get supported payments
	function getSupportedPaymentMethods($paymentInfo){
		$data = array(
			"countryCode" => $paymentInfo->countryCode,
			"shopperLocale" => $paymentInfo->shopperLocale,
			"amount" => array(
				"currency" => $paymentInfo->currency,
				"value" => $paymentInfo->amount
			),
			"channel" => "Web",
			"merchantAccount" => "AdyenRecruitmentCOM"
		);
		
		$json_data = json_encode($data);
		$context_options = array (
			'http' => array (
				'method' => 'POST',
				'header'=> "Content-type: application/json\r\n"
					. "Content-Length: " . strlen($json_data) . "\r\n".
					"x-api-key: $this->myApiKey",
				'content' => $json_data
				)
			);
		//
		$context = stream_context_create($context_options);
		$result = file_get_contents('https://checkout-test.adyen.com/v69/paymentMethods', false, $context);
		return json_decode($result);
	}
	
	//Create a new Adyen payment session
	function createPaymentSession($paymentInfo){
		$data = [
			"merchantAccount"=> $this->merchantAccount,
			"amount"=> [
				"currency" => $paymentInfo->currency,
				"value" => $paymentInfo->amount
			],
			"returnUrl"=> $paymentInfo->returnUrl,
			"reference"=> $paymentInfo->referenceNumber,
			"countryCode"=> $paymentInfo->countryCode
		];
		
		$json_data = json_encode($data);
		$context_options = array (
			'http' => array (
				'method' => 'POST',
				'header'=> "Content-type: application/json\r\n"
					. "Content-Length: " . strlen($json_data) . "\r\n".
					"x-API-key: {$this->myApiKey}",
				'content' => $json_data,
				"ignore_errors" => true
				)
			);
		//
		//var_dump($data);
		//var_dump($context_options);
		$context = stream_context_create($context_options);
		$result = file_get_contents('https://checkout-test.adyen.com/v69/sessions', false, $context);
		
		$status_line = $http_response_header[0];
		preg_match('{HTTP\/\S*\s(\d{3})}', $status_line, $match);
		$status = $match[1];

		if (!in_array($status, ["200", "201"])) {
			$result = $status;
		}
		return json_decode($result);
		
	}
	
	
	
}