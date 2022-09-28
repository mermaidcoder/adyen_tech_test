<!DOCTYPE html>
<html lang="en" >

<head>
	<meta charset="UTF-8">
	<meta name="apple-mobile-web-app-title" content="Product Listing">
	<title>Popular Products Section</title>
	   
	<link rel='stylesheet' href='https://cdn.jsdelivr.net/npm/bootstrap@5.0.1/dist/css/bootstrap.min.css'>
	<link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.3/css/all.min.css'>
	<link rel='stylesheet' href='style/style.css'>
	  
	<style>
	</style>
<?php	
	require_once("helper.php");
	require_once("products.php");
	$pRepository = new ProductsRepository();
	$products_array = $pRepository->getProducts();
	
?>
<script>
</script>
</head>

<body translate="no" >
  <section class="section-products">
		<div class="container">
				<div class="row justify-content-center text-center">
						<div class="col-md-8 col-lg-6">
								<div class="header">
										<h2>Popular Products</h2>
								</div>
						</div>
				</div>
				<div class="row" id="productsContainer">
					<?php
					foreach($products_array as $product){
						
						echo  generateSingleProdCard($product);
					}
					?>
				</div>
		</div>
</section>
  
</body>

</html>