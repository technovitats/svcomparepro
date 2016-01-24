<?php
	require_once "support/http.php";
	require_once "support/web_browser.php";
	require_once "support/simple_html_dom.php";
	
	$search = $_GET['search'];
	
	class ResultItem
	{
		public $resTitle = "";
		public $resFullTitle = "";
		public $resUrl = "";
		public $resPrice = "";
	}

	$resultSet = array();
	$html = new simple_html_dom();

	$url = "http://www.amazon.in/s/ref=nb_sb_noss_2?url=search-alias%3Daps&field-keywords=". $search;
	$web = new WebBrowser();
	$result = $web->Process($url);

	if (!$result["success"]) {
		echo "Error retrieving URL.  " . $result["error"] . "<br/>";
	} else if ($result["response"]["code"] != 200) {
		echo "Error retrieving URL.  Server returned:  " . $result["response"]["code"] . " " . $result["response"]["meaning"] . "<br/>";
	} else {
		$html->load($result["body"]);
		$rows = $html->find("a[href]");
		foreach ($rows as $row)
		{
			if($row->class == "a-link-normal s-access-detail-page  a-text-normal") {
				$thisResultItem = new ResultItem;
				if(strlen($row->title) > 38) {
					$thisResultItem->resTitle = substr($row->title, 0, 38) . "...";
				} else {
					$thisResultItem->resTitle = $row->title;
				}
				$thisResultItem->resFullTitle = $row->title;
				$thisResultItem->resUrl = $row->href;
				$resultSet[] = $thisResultItem;
			}
		}

		$spanRows = $html->find("span");
		$i = 0;
		foreach ($spanRows as $spanRow)
		{
			if($spanRow->class == "a-size-base a-color-price s-price a-text-bold") {
				$replaced_str = str_replace("<span class=\"a-size-base a-color-price s-price a-text-bold\"><span class=\"currencyINR\">&nbsp;&nbsp;</span>", "Rs ", $spanRow);
				$resultSet[$i]->resPrice = str_replace("</span>", "", $replaced_str);
				$i++;
			}
		}

		echo "<table class=\"table table-striped table-bordered table-hover table-condensed\">";
		echo "<tr><th>Product Name</th><th>Price</th><th>Buy Link</th></tr>";
		
		foreach($resultSet as $result) {
			echo "<tr>";
			echo "<td>". $result->resTitle ."</td>";
			echo "<td>". $result->resPrice ."</td>";
			echo "<td><a href=". $result->resUrl ." target=\"_blank\" title=\"". $result->resFullTitle ."\">Buy Now</a></td>";
			echo "</tr>";
		}
	}
?>
