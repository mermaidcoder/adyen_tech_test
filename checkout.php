<?php
	require_once("helper.php");
	require_once("products.php");
	//in a real world application this should be escaped to prevent sql injections
	$id = $_GET['id'];
	$pRepository = new ProductsRepository();
	$productInfo =  $pRepository->getProductById($id);
	$price = isset($productInfo->discounted_price) ? $productInfo->discounted_price : $productInfo->price;
	
	$ip_address = getIP();
	$ip_address =  $ip_address == "127.0.0.1"? "185.132.92.241" : $ip_address;
	$request_uri = "https://api.ipfind.com?ip={$ip_address}";
	$resp = json_decode(file_get_contents($request_uri));	
	$detected_cc = $resp->country_code;
	
	$countries = [
		(object)[
			"country_code" => "AL",
			"country"=>"Albania",
			"shopperLocale" => "sq-AL",
			"currency_symbol"=>"ALL"
		],
		(object)[
			"country_code" => "NL",
			"country"=>"Nederlands",
			"shopperLocale" => "nl-NL",
			"currency_symbol"=>"EUR"
		]
	];
	
	$detected_country =  findItemByKeyValue($countries, "country_code", $detected_cc);
	$detected_cs = $detected_country->currency_symbol;
	
	$currencies = [
		(object)[
			"symbol"=>"ALL",
			"name"=>"Albanian Leke"
		],
		(object)[
			"symbol"=>"EUR",
			"name"=>"Europian Union Currency"
		]
	];
?>
<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<meta name="apple-mobile-web-app-title" content="Product Listing">
	<title>Popular Products Section</title>
	   
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
	<link rel='stylesheet' href='style/style.css'>
	<!--Taken from https://docs.adyen.com/online-payments/release-notes?tab=embed-script-and-stylesheet_2021-10-04-stzc_2#releaseNote=2021-10-06-web-componentsdrop-in-5.0.0 -->
	<!-- Embed the Adyen Web script element above any other JavaScript in your checkout page. -->
	<script src="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.0.0/adyen.js"
    crossorigin="anonymous"></script>

	<link rel="stylesheet"
     href="https://checkoutshopper-test.adyen.com/checkoutshopper/sdk/5.0.0/adyen.css"
     crossorigin="anonymous">
	<style>
	</style>
	<script>
		function getSupportedPaymentMethods(){
			let countrySelect = document.getElementById("country");
			let selectedCountry = countrySelect.options[countrySelect.selectedIndex];
			let shopperLocale = selectedCountry.getAttribute('shopperLocale');


			let currencySelect = document.getElementById("preferredCurrency");			
			
			let price = <?php echo $price; ?>;
			let id = <?php echo $id; ?>;
			
			let paymentInfo = {
				"countryCode": countrySelect.value,
				"shopperLocale": shopperLocale,
				"currency": currencySelect.value,
				"amount": price,
				"productId": id
			};
			
			const responsePromise = fetch('AdyenCtl.php?action=ss_pm', {
				method: 'POST',
				headers: {
				  'Accept': 'application/json',
				  'Content-Type': 'application/json'
				},
				body: JSON.stringify(paymentInfo)
			  });
			responsePromise.then((resp)=>{
				alert(resp.json());
			}) 
		}

		var initDropIn = async function(){
			let countrySelect = document.getElementById("country");
			let selectedCountry = countrySelect.options[countrySelect.selectedIndex];
			let shopperLocale = selectedCountry.getAttribute('shopperLocale');


			let currencySelect = document.getElementById("preferredCurrency");			
			
			let price = <?php echo $price; ?>;
			let id = <?php echo $id; ?>;
			
			let paymentInfo = {
				"countryCode": countrySelect.value,
				"shopperLocale": shopperLocale,
				"currency": currencySelect.value,
				"amount": price,
				"productId": id
			};
			//krijimi i sesionit te pageses
			const responsePromise = fetch('AdyenCtl.php?action=ss_create', {
				method: 'POST',
				headers: {
				  'Accept': 'application/json',
				  'Content-Type': 'application/json'
				},
				body: JSON.stringify(paymentInfo)
			  });
			responsePromise.then(function(resp){
				resp.json().then((p)=>{
					console.log(p);
					
					const configuration = {
						environment: 'test', // Change to 'live' for the live environment.
						clientKey: 'test_7ZCG2HRVQ5DYDG54CTVFVXKYFIKWXTBF', // Public key used for client-side authentication: https://docs.adyen.com/development-resources/client-side-authentication
						analytics: {
							enabled: true // Set to false to not send analytics data to Adyen.
						},
						session: {
							id: p.result.id, // Unique identifier for the payment session.
							sessionData: p.result.sessionData // The payment session data.
						},
						onPaymentCompleted: (result, component) => {
							console.info(result, component);
						},
						onError: (error, component) => {
							console.error(error.name, error.message, error.stack, component);
						},
						// Any payment method specific configuration. Find the configuration specific to each payment method:  https://docs.adyen.com/payment-methods
						// For example, this is 3D Secure configuration for cards:
						paymentMethodsConfiguration: {
							card: {
								hasHolderName: true,
								holderNameRequired: true,
								billingAddressRequired: true
							}
						}
					};
					// Create an instance of AdyenCheckout using the configuration object.
					const checkout = AdyenCheckout(configuration).then(
						function(){							
							// Create an instance of Drop-in and mount it to the container you created.
							const dropinComponent = checkout.create('dropin').mount('#dropin-container');
						}
					);
					
				});
			}); 
		}
	</script>
</head>

<body translate="no" >
	<div class="container">
		Please confirm the country your credit card was issued.
		<select id="country" class="form-select" size="3" aria-label="size 3 select">
			<?php 
				foreach($countries as $c){
					echo "<option ".($detected_cc==$c->country_code?"selected":"")." value='{$c->country_code}' shopperLocale='{$c->shopperLocale}'>{$c->country}</option>";
				}
			?>
		</select>
		Please confirm your preferred currency for the transaction.
		<select id="preferredCurrency" class="form-select" size="3" aria-label="size 3 select">
			<?php 
				foreach($currencies as $c){
					echo "<option ".($detected_cs==$c->symbol?"selected":"")." value='{$c->symbol}'>{$c->name}</option>";
				}
			?>
		</select>
		<div class="alert alert-primary" role="alert">
			Transaction amount is <?php echo '$'.$price; ?>
		</div>
		<button onclick="initDropIn()" type="button" class="btn btn-success">Proceed</button>

		<div id="dropin-container"></div>
	</container>
</body>

</html>	
   