<?php
function generateSingleProdCard($prodInfo){
$new_tag = $prodInfo->isNew? "<span class='new'>new</span>":"";
$id = $prodInfo->id;
if(isset($prodInfo->discounted_price)){
	$discount_tag = "<span class='discount'>".(100 - round($prodInfo->discounted_price*100/$prodInfo->price, 2))."% off</span>";
	$price_tag = "<h4 class='product-price'>\${$prodInfo->discounted_price}</h4>";
	$old_price_tag = "<h4 class='product-old-price'>\${$prodInfo->price}</h4>";
}else{
	$discount_tag = "";
	$price_tag = "<h4 class='product-price'>\${$prodInfo->price}</h4>";
	$old_price_tag = "";
}

$tpl = <<<EOD
<div class='col-md-6 col-lg-4 col-xl-3'>
		<div id='product-1' class='single-product'>
				<div class='part-1'>
						{$new_tag}
						{$discount_tag}
						<ul>
								<li><a href='#'><i class='fas fa-shopping-cart'></i></a></li>
								<li><a href='#'><i class='fas fa-heart'></i></a></li>
								<li><a href='checkout.php?id={$id}' title='Go to Checkout'><i class='fas fa-handshake'></i></a></li>
								<li><a href='#'><i class='fas fa-expand'></i></a></li>
						</ul>
				</div>
				<div class='part-2'>
						<h3 class='product-title'>{$prodInfo->title}</h3>
						{$old_price_tag}
						{$price_tag}
				</div>
		</div>
</div>
EOD;
return $tpl;
}		

function getIP()
{
    if (isset($_SERVER["REMOTE_ADDR"])) {
        return $_SERVER["REMOTE_ADDR"];
    } else if (isset($_SERVER["HTTP_X_FORWARDED_FOR"])) {
        return $_SERVER["HTTP_X_FORWARDED_FOR"];
    } else if (isset($_SERVER["HTTP_CLIENT_IP"])) {
        return $_SERVER["HTTP_CLIENT_IP"];
    } else return NULL;
}				

function findItemByKeyValue($collection, $key, $value){
	$len = count($collection);
	$found = false;
	$item = null;
	for($i=0;$i<$len && !$found;$i++){
		if($collection[$i]->{$key} == $value){
			$found = true;
			$item = $collection[$i];
		}
	}
	return $item;
}