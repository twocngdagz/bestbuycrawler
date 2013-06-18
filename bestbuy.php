<?php
include_once('simple_html_dom.php');
require_once("/includes/initialize.php");



class BestBuy {
	protected $_file = '';
	protected $ip = '';
	protected $url = '';
	protected $UPC = '';
	protected $product = null;
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
        $this->ip = $this->getProxy();
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
		echo $chosen . "\n";
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
			$shipping_url = "http://www.bestbuy.com/site/olspage.jsp?id=cat13503&type=page&skuId=". $this->cleanData($this->SKU) ."&postPaidFlag=false&h=387";
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

	
	public function start() {
		$file_handle = fopen($this->_file, "r");
		while (!feof($file_handle)) {
		   $SKU_array = explode("\t",fgets($file_handle));
		   $this->SKU = $SKU_array[2];
		   $this->UPC = $SKU_array[1];
		   $this->Main_SKU = $SKU_array[0];
		   if (!IsNullOrEmptyString($this->SKU)) {
				$this->url = "http://www.bestbuy.com/site/searchpage.jsp?_dyncharset=ISO-8859-1&_dynSessConf=-3052187188385421762&id=pcat17071&type=page&st=". $this->cleanData($this->SKU) . "&sc=Global&cp=1&nrp=15&sp=&qp=&list=n&iht=y&usc=All+Categories&ks=960";	
				while (!$data = $this->scrape($this->url, $this->ip)) {
					if ($this->counter_error <= $this->error_limit) {
						$this->ip = $this->getProxy();
						$this->counter_error++;
					} else {
						break;
					}
				}
				
		   		while (preg_match("/Å/", $data) || 
		   				($data == 'An operation on a socket could not be performed because the system lacked sufficient buffer space or because a queue was full') || 
		   				($data == 'Maximum number of open connections reached.')) {
			   		while (!$data = $this->scrape($this->url, $this->ip)) {
				   		if ($this->counter_error <= $this->error_limit) {
							$this->ip = $this->getProxy();
							$this->counter_error++;
						} else {
							break;
						}
					}
		   			if ($this->counter_error > $this->error_limit) {
						break;
					}	
				}
				$this->html = str_get_html($data);
				while ($this->isAccessDenied($this->html) || $this->isInvalidRequest($this->html) || $this->isBadRequest($this->html) || $this->isProxyError($this->html) || ($data == 'Maximum number of open connections reached.')) {
					while (!$data = $this->scrape($this->url, $this->ip)) {
						if ($this->counter_error <= $this->error_limit) {
							$this->ip = $this->getProxy();
							$this->counter_error++;
						} else {
							break;
						}
					}
					$this->html = str_get_html($data);
					if ($this->counter_error > $this->error_limit) {
						break;
					}
				}
				
				if ($this->counter_error > $this->error_limit) {
					echo "Processing without proxy"."\n";
					while(!$data = $this->scrape_without_proxy($this->url)){echo $data."\n";}
					$this->counter_error = 1;
					$this->html = str_get_html($data);
				}

				
				$this->price =  $this->getPrice($this->html);
				if ($this->price) {
					$this->counter_error = 1;
					$this->getShipping($this->html, $this->SKU);
					$this->seller =  $this->getSeller($this->html);
					$this->product = new Product();
					$this->product->SKU = $this->SKU;
					$this->product->seller = $this->seller;
					$this->product->shipping = $this->shipping;
					$this->product->price = $this->cleanData($this->price);
					$this->product->Main_SKU = $this->Main_SKU;
					$this->product->UPC = $this->UPC;
					$this->product->MatchUPC = 'False';
					$this->product->save();
				} else {
					$this->counter_error = 1;
					log_action('Empty Price', $data);
					$seller = '';
					$this->product = new Product();
					$this->product->SKU = $this->cleanData($this->SKU);
					$this->product->seller = '';
					$this->product->shipping = '';
					$this->product->price = '';
					$this->product->Main_SKU = $this->Main_SKU;
					$this->product->UPC = $this->UPC;
					$this->product->MatchUPC = 'False';
					$this->product->save();
				}
		   } else {
		   		
				$this->UPC_url = "http://www.bestbuy.com/site/searchpage.jsp?_dyncharset=ISO-8859-1&_dynSessConf=-5956367453938491510&id=pcat17071&type=page&st=". $this->UPC . "&sc=Global&cp=1&nrp=15&sp=&qp=&list=n&iht=y&usc=All+Categories&ks=960";
				while (!$data = $this->scrape($this->UPC_url, $this->ip)) {
					echo $this->counter_error . " " . $this->error_limit . "\n";
					if ($this->counter_error <= $this->error_limit) {
						$this->ip = $this->getProxy();
						$this->counter_error++;
					} else {
						break;
					}
				}
		   		while (preg_match("/Å/", $data) || ($data == 'An operation on a socket could not be performed because the system lacked sufficient buffer space or because a queue was full') || ($data == 'Maximum number of open connections reached.')) {
			   		while (!$data = $this->scrape($this->UPC_url, $this->ip)) {
				   		if ($this->counter_error <= $this->error_limit) {
							$this->ip = $this->getProxy();
							$this->counter_error++;
						} else {
							break;
						}
					}
		   			if ($this->counter_error > $this->error_limit) {
						break;
					}	
				}
				$this->html = str_get_html($data);
				
				while ($this->isAccessDenied($this->html) || $this->isInvalidRequest($this->html) || $this->isBadRequest($this->html) || $this->isProxyError($this->html) || ($data == 'Maximum number of open connections reached.')) {
					while (!$data = $this->scrape($this->UPC_url, $this->ip)) {
						if ($this->counter_error <= $this->error_limit) {
							$this->ip = $this->getProxy();
							$this->counter_error++;
						} else {
							break;
						}
					}
					$this->html = str_get_html($data);
					if ($this->counter_error > $this->error_limit) {
						break;
					}
				}
				
		   		if ($this->counter_error > $this->error_limit) {
		   			echo "Processing without proxy"."\n";
					while(!$data = $this->scrape_without_proxy($this->UPC_url)){echo $data."\n";}
					$this->counter_error = 1;
					$this->html = str_get_html($data);
				}
				
				$this->price =  $this->getPrice($this->html);
				if ($this->price) {
					$this->counter_error = 1;
					if (IsNullOrEmptyString($this->SKU)) {
						$this->SKU = $this->getBBYSKU($this->html);
					}
					$this->getShipping($this->html, $this->SKU);
					$this->seller = $this->getSeller($this->html);
					$this->product = new Product();
					$this->product->SKU = $this->SKU;
					$this->product->seller = $this->seller;
					$this->product->shipping = $this->shipping;
					$this->product->price = $this->cleanData($this->price);
					$this->product->Main_SKU = $this->Main_SKU;
					$this->product->UPC = $this->UPC;
					$this->product->MatchUPC = 'True';
					$this->product->save();
				} else {
					$this->counter_error = 1;
					log_action('Empty Price using UPC', $data);
					$this->seller =  '';
					$this->product = new Product();
					$this->product->SKU = $this->cleanData($this->SKU);
					$this->product->seller = '';
					$this->product->shipping = '';
					$this->product->price = '';
					$this->product->Main_SKU = $this->Main_SKU;
					$this->product->UPC = $this->UPC;
					$this->product->MatchUPC = 'False';
					$this->product->save();
				}
			}
		unset($this->seller, $this->SKU, $this->Main_SKU, $this->shipping, $this->UPC, $price, $this->product, 
			$this->data, $this->html, $this->SKU_array);
			//sleep(3);
		}
		fclose($file_handle);
		echo "Finish" . "\n";
	}
}
?>