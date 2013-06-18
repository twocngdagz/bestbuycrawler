<?php
include_once('simple_html_dom.php');
require_once("/includes/initialize.php");



class BestBuy_v2 {
	protected $_file = '';
	protected $ip = '';
	protected $url = '';
	protected $UPC = '';
	protected $product = null;
	protected $product_v2 = null;
	protected $seller = null;
	protected $shipping = null;
	protected $SKU = null;
	protected $Main_SKU = null;
	protected $html = null;
	protected $price = null;
	protected $UPC_url = '';
	private $limit;
	private $offset;
	private $error_counter;
	private $error_limit;
	
	function __construct($file, $isDelete = false, $offset = 0, $limit = 1000)
    {
    	$this->limit = $limit;
    	$this->offset = $offset;
    	$this->_file = $file;
    	$oldtime = time();
    	$this->counter_error = 1;
    	$this->error_limit = 10;
    	if ($isDelete) {
    		Product::deleteAll();
    	}
    }
    
    function cleanData(&$str)  {
	    $str = preg_replace("/\t/", "", $str);
		$str = preg_replace("/\n/", "", $str);
		$str = preg_replace("/\r\n/", "", $str);
	    $str = preg_replace("/\r?\n/", "", $str);
	    $str = preg_replace("/\r/", "", $str);
	    $str = trim($str, "(");
	    $str = trim($str, ")");
		$str = trim($str, ";");
		$str = trim($str, ","); 
		$str = trim($str, "   ");
		$str = trim($str, "&nbsp;");
		$str = trim($str, "&mdash;");
		return ($str);
	    //if(strstr($str, '"')) $str = '"' . str_replace('"', '""', $str) . '"';
	    //121776394/3089
	}

	function getProxy() {
		$proxies = Proxy::find_all();
		$chosen = rand(0, count($proxies)-1);
		$ipaddress = $proxies[$chosen]->ip;
		$port = $proxies[$chosen]->port;
		return $ipaddress . ':' . $port;
	}
	
	function scrape_without_proxy($url) {
		sleep(1);
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_ENCODING, 'identity');
		curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
		$data = curl_exec($ch);
	   	if(curl_errno($ch)){
		   	return false;
		}
		curl_close($ch);
		return $data;	
	}

	function scrape($url, $ipaddress) {
		sleep(1);
		$oldtime = time();
		$ch = curl_init();
		curl_setopt($ch, CURLOPT_URL, $url);
		curl_setopt($ch, CURLOPT_USERAGENT, "Mozilla/5.0 (Windows NT 6.1; WOW64) AppleWebKit/537.4 (KHTML, like Gecko) Chrome/22.0.1229.79 Safari/537.4");
		curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1); 
		curl_setopt($ch, CURLOPT_ENCODING, 'identity');
		curl_setopt($ch, CURLOPT_FORBID_REUSE, TRUE);
		curl_setopt($ch, CURLOPT_PROXY, $ipaddress);
		curl_setopt($ch, CURLOPT_PROXYTYPE, CURLPROXY_HTTP);
		curl_setopt($ch, CURLOPT_CONNECTTIMEOUT, 5);
		curl_setopt($ch, CURLOPT_TIMEOUT, 5);
		$data = curl_exec($ch);
	   	if(curl_errno($ch)){
	   		echo 'Curl error: ' . curl_error($ch) . "\n";
		   	return false;
		}
		curl_close($ch);
		$nowtime = time();
		if (($nowtime-$oldtime) > 5) {
			$this->ip = $this->getProxy();
		}
		echo "Time elapsed: ".time_elapsed($nowtime-$oldtime)."\n";
		return $data;	
	}

	function getPrice($data) {
		$div_price = $data->find('div[itemprop=offers]', 0);
		$price = '';
		if (isset($div_price)) { 
			$price_span = $div_price->find('span[itemprop=price]', 0);
			$price = $price_span->innertext;
		}
		
		if (!($price)) {
			$h4 = $data->find('h4[class=price regular]', 0);
			if ($h4) {
				$span = $h4->find('span', 0);
				if ($span) {
					$price = $span->innertext;
				}
			}
		}
		return $price;
	}

	function getBBYSKU($data) {
		$sku_find = $data->find('strong[class=sku]', 0);
		$sku = $sku_find->innertext;
		return $sku;
	}

	function getShipping($data, $SKU) {
		foreach ($data->find('ul[class=offers] li') as $li_offers) {
			if (trim($li_offers->find('a', 0)->innertext) == "Free Shipping") {
				$this->shipping = "$ FREE";
			}
		}
		if (!(isset($this->shipping ))) {
			$shipping_url = "http://www.bestbuy.com/site/olspage.jsp?id=cat13503&type=page&skuId=". $this->cleanData($this->product_v2->SKU) ."&postPaidFlag=false&h=387";
			while (!$data = $this->scrape($shipping_url, $this->ip)) {
				$this->ip = $this->getProxy();
			}
			$html_shipping = str_get_html($data);
			$option = $html_shipping->find('option[value=Ground]', 0);
			if (isset($option)) {
				$this->shipping = $option->innertext;
				$this->shipping = $this->cleanData(trim($this->shipping, "Standard"));
			} else {
				$this->shipping = "Shipping not available";
			}
		} 
	}

	function getSeller($data) {
		$seller = '';
		$div_seller = $data->find('div[class=seller]', 0);
		if (isset($div_seller)) {
			$seller = $div_seller->find('a', 0)->innertext;
			return $seller;
		}
	}

	function toString($data) {
		echo "<pre>";
			print_r($data); 
		echo "</pre> \n";
	}
	
	function isAccessDenied($data) {
		if ($data) {
			$title = $data->find('title', 0);
			if ($title) {
				if ($title->innertext == 'Access Denied') {
					return true;
				}
			}
		}
		return false;
	}
	
	function isBadRequest($data) {
		if ($data) {
			$title = $data->find('title', 0);
			if ($title) {
				if ($title->innertext == 'Best Buy - Bad Request') {
					return true;
				}
			}
		}
		return false;
	}
	
	function isInvalidRequest($data) {
		if ($data) {
			$title = $data->find('h1', 0);
			if ($title) {
				if ($title->innertext == '400 Bad request') {
					return true;
				}
			}
		}
		return false;
	}
	
	function isProxyError($data) {
		if ($data) {
			$title = $data->find('title', 0);
			if ($title) {
				if ($title->innertext == '500 Internal Privoxy Error') {
					return true;
				}
			}
		}
		return false;
	}
	
	
	function hasMatched($data) {
		if ($data) {
			$div = $data->find('div[id=searchstatered]', 0);
			if ($div) {
				$text = $div->innertext;
				if (substr_count($text, "sorry")) {
					return true;
				}
			}
		}
		return false;
	}

	
	public function start() {
		$this->ip = $this->getProxy();
		while(TRUE) {
			$count = Product_v2::count_all_where("Status", "New");
			while($count > 0) {		
				while ($this->product_v2 = Product_v2::transaction_find_by_where("Status", "New")) {
					$this->product_v2->Status = 'Process';
					$this->product_v2->transaction_save();
				   if (!IsNullOrEmptyString($this->product_v2->SKU)) {
				   		echo "Processing SKU:" . $this->product_v2->SKU . ": ". $this->ip . "\n";
						$this->url = "http://www.bestbuy.com/site/searchpage.jsp?_dyncharset=ISO-8859-1&_dynSessConf=-3052187188385421762&id=pcat17071&type=page&st=". $this->cleanData($this->product_v2->SKU) . "&sc=Global&cp=1&nrp=15&sp=&qp=&list=n&iht=y&usc=All+Categories&ks=960";	
						if ($data = $this->scrape($this->url, $this->ip)) {
				   			if (!(preg_match("/Å/", $data) || 
				   				($data == 'An operation on a socket could not be performed because the system lacked sufficient buffer space or because a queue was full') || 
				   				($data == 'Maximum number of open connections reached.'))) {
									$this->html = str_get_html($data);
									if (!($this->isAccessDenied($this->html) || $this->isInvalidRequest($this->html) || 
										$this->isBadRequest($this->html) || $this->isProxyError($this->html) || 
										($data == 'Maximum number of open connections reached.'))) {		
											$this->price =  $this->getPrice($this->html);
											if ($this->price) {
												$this->getShipping($this->html, $this->product_v2->SKU);  
												$this->product_v2->seller = $this->getSeller($this->html);
												$this->product_v2->shipping = $this->shipping;
												$this->product_v2->price = $this->cleanData($this->price);
												$this->product_v2->MatchUPC = 'False';
												$this->product_v2->Status = 'Done';
												$this->product_v2->Time = time();
												$this->product_v2->save();
											} else {
												if ($this->hasMatched($this->html)) {
													$this->product_v2->Status = 'Done';
													$this->product_v2->Time = time();
													$this->product_v2->save();
												} else {
													$this->ip = $this->getProxy();
													$this->product_v2->Status = 'New';
													$this->product_v2->save();
												}
												
											} 
										} else {
											$this->ip = $this->getProxy();
											$this->product_v2->Status = 'New';
											$this->product_v2->save();
										}
				   				} else {
				   					$this->ip = $this->getProxy();
				   					$this->product_v2->Status = 'New';
									$this->product_v2->save();
				   				}
						} else {
							$this->ip = $this->getProxy();
							$this->product_v2->Status = 'New';
							$this->product_v2->save();
						}
				   } else {
				   		if (!IsNullOrEmptyString($this->product_v2->UPC)) {
					   		echo "Processing UPC:" . $this->product_v2->UPC . ": ". $this->ip . "\n";
							$this->UPC_url = "http://www.bestbuy.com/site/searchpage.jsp?_dyncharset=ISO-8859-1&_dynSessConf=-5956367453938491510&id=pcat17071&type=page&st=". $this->product_v2->UPC . "&sc=Global&cp=1&nrp=15&sp=&qp=&list=n&iht=y&usc=All+Categories&ks=960";
							$data = $this->scrape($this->UPC_url, $this->ip);
							if ($data) {
					   			if (!(preg_match("/Å/", $data) || 
						   			($data == 'An operation on a socket could not be performed because the system lacked sufficient buffer space or because a queue was full') || 
						   			($data == 'Maximum number of open connections reached.'))) {
									$this->html = str_get_html($data);
									if (!($this->isAccessDenied($this->html) || $this->isInvalidRequest($this->html) || 
										$this->isBadRequest($this->html) || $this->isProxyError($this->html) || 
										($data == 'Maximum number of open connections reached.'))) {
											$this->price =  $this->getPrice($this->html);
											if ($this->price) {
												if (IsNullOrEmptyString($this->product_v2->SKU)) {
													$this->product_v2->SKU = $this->getBBYSKU($this->html);
												}
												$this->getShipping($this->html, $this->product_v2->SKU);
												$this->product_v2->seller = $this->getSeller($this->html);
												$this->product_v2->shipping = $this->shipping;
												$this->product_v2->price = $this->cleanData($this->price);
												$this->product_v2->MatchUPC = 'True';
												$this->product_v2->Status = 'Done';
												$this->product_v2->Time = time();
												$this->product_v2->save();
												echo "save.." . "\n";
											} else {
												if ($this->hasMatched($this->html)) {
													$this->product_v2->Status = 'Done';
													$this->product_v2->Time = time();
													$this->product_v2->save();
												} else {
													log_action('Empty Price using UPC', $data);
													$this->ip = $this->getProxy();
													$this->product_v2->Status = 'New';
													$this->product_v2->save();
												}
											}
										} else {
											$this->ip = $this->getProxy();
											$this->product_v2->Status = 'New';
											$this->product_v2->save();
										}
					   			} else {
					   				$this->ip = $this->getProxy();
					   				$this->product_v2->Status = 'New';
									$this->product_v2->save();
					   			}
							} else {
								$this->ip = $this->getProxy();
								$this->product_v2->Status = 'New';
								$this->product_v2->save();
							}
						}
				   }
				   unset($this->product_v2, $this->data, $this->html, $this->shipping, $this->price);	
				}
			}
		}
		echo "Finish" . "\n";
	}
}
?>