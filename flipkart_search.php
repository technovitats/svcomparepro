<?php
	include "clusterdev.flipkart-api.php";
	$flipkart = new \clusterdev\Flipkart("adityamal1", "b300cb72fb544232ad692f1781cd766a", "json");
	$search = $_GET['search'];
	// $search = "Moto G";
	$s = str_replace(" ", "+", $search);
	$product_url = 'https://affiliate-api.flipkart.net/affiliate/search/json?query='.$s.'&resultCount=8';
	 
	// Call the API using the URL.
	$details = $flipkart->call_url($product_url);
	if(!$details) {
		echo 'Error: Could not retrieve Top Offers.';
		exit();
	}
	
	// The response is expected to be JSON. Decode it into associative arrays.
	$details = json_decode($details, TRUE);
	$list = $details['productInfoList'];
		
	echo "<table class=\"table table-striped table-bordered table-hover table-condensed\">";
	echo "<tr><th>Image</th><th>Product Name</th><th>Price</th><th>Buy Link</th></tr>";
	
	if(count($list) > 0) {
		$i=0;
		foreach ($list as $item) {
			$productId=$item['productBaseInfo']['productIdentifier']['productId'];
			$categoryPaths=$item['productBaseInfo']['productIdentifier']['categoryPaths'];
			$categoryPath=$item['productBaseInfo']['productIdentifier']['categoryPaths']['categoryPath'];
			// The API returns these values
			$productAttributes = $item['productBaseInfo']['productAttributes'];
			$title = $item['productBaseInfo']['productAttributes']['title'];
			$imageUrls=$item['productBaseInfo']['productAttributes']['imageUrls'];
		
			$maximumRetailPrice = $item['productBaseInfo']['productAttributes']['sellingPrice'];
			$amount = $item['productBaseInfo']['productAttributes']['maximumRetailPrice']['amount'];
			$currency = $item['productBaseInfo']['productAttributes']['maximumRetailPrice']['currency'];
			$temp_link['$i']=$productUrl=$item['productBaseInfo']['productAttributes']['productUrl'];
			echo "<tr><td>";
				$keys = array_keys($imageUrls);
echo "<img src=".$imageUrls[$keys[0]]." height=\"100\"/>";
			echo"</td>";
			if(strlen($title) > 38) {
				echo "<td>". substr($title, 0, 38) ."...</td>";
			} else {
				echo "<td>$title</td>";
			}

			$temp_amount['$i']=intval($maximumRetailPrice['amount']);
			echo "<td>Rs ".$temp_amount['$i'].".00</td>";
			$p[$i] = $temp_amount['$i'];
			echo "<td><a href=".$temp_link['$i']." target=\"_blank\" title=\"". $title ."\">Buy Now</a></td>";
			echo "</tr>";
			$i++;
		}
	}
	
	
	echo "</table>";
	echo "<div class=\"alert alert-warning\" role=\"alert\" style=\"text-align: center;\"><b>Minimum value is: </b><span class=\"badge\">&nbsp;&nbsp;&nbsp;INR ".min($p).".00&nbsp;&nbsp;&nbsp;</span></div>";
	exit();
	$home = $flipkart->api_home();
	// Make sure there is a response.
	if($home==false){
		echo 'Error: Could not retrieve API homepage';
		exit();
	}
	
	$home = json_decode($home, TRUE);
	$list = $home['apiGroups']['affiliate']['apiListings'];
?>