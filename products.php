<?php
require_once("helper.php");
class ProductsRepository
{
	protected $products_array = [];
	
	function __construct(){
		$this->products_array = [
			(object)[
				"id"=>1,
				"pimage"=>"https://i.ibb.co/L8Nrb7p/1.jpg",
				"price"=>25.5,
				"discounted_price"=>20.0,
				"title"=>"Sneakers X",
				"isNew"=>0
			],
			(object)[
				"id"=>2,
				"pimage"=>"https://i.ibb.co/cLnZjnS/2.jpg",
				"price"=>35.0,
				"title"=>"Sneakers Y",
				"isNew"=>1
			],
			(object)[
				"id"=>3,
				"pimage"=>"https://i.ibb.co/L8Nrb7p/1.jpg",
				"price"=>25.5,
				"discounted_price"=>30.0,
				"title"=>"Sneakers Z",
				"isNew"=>0
			],
			(object)[
				"id"=>4,
				"pimage"=>"https://i.ibb.co/cLnZjnS/2.jpg",
				"price"=>15.0,
				"title"=>"Sneakers Y",
				"isNew"=>1
			],
			(object)[
				"id"=>5,
				"pimage"=>"https://i.ibb.co/L8Nrb7p/1.jpg",
				"price"=>25.5,
				"discounted_price"=>20.0,
				"title"=>"Sneakers W",
				"isNew"=>0
			],
			(object)[
				"id"=>7,
				"pimage"=>"https://i.ibb.co/cLnZjnS/2.jpg",
				"price"=>55.0,
				"title"=>"Sneakers Q",
				"isNew"=>1
			]
		];	
	}
	
	function getProducts(){
		return $this->products_array;
		
		
	}

	function getProductById($id){
		return findItemByKeyValue($this->products_array, "id", $id);
	}
}