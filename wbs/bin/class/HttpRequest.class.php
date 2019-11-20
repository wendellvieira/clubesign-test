<?php 
	if (!defined('PHP_QUERY_RFC1738')) {
        define('PHP_QUERY_RFC1738', 1);
    }
    if (!defined('PHP_QUERY_RFC3986')) {
        define('PHP_QUERY_RFC3986', 2);
    }

	class HttpRequest {
		protected $config = array(
			CURLOPT_URL => "",
			CURLOPT_RETURNTRANSFER => true,
			CURLOPT_ENCODING => "",
			CURLOPT_MAXREDIRS => 10,
			CURLOPT_TIMEOUT => 15,
			CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
			CURLOPT_CUSTOMREQUEST => "GET",
			CURLOPT_HTTPHEADER => array(),
		);

		public function setUrl($url = ""){
			$this->config[CURLOPT_URL] = $url;
		}
		public function setMethod($method = ""){
			$this->config[CURLOPT_CUSTOMREQUEST] = strtoupper($method);
			if(strtoupper($method) == "POST"){
				$this->config[CURLOPT_POST] = true;
			}
		}
		public function setHeader($header = array()){
			$header_array = array();
			foreach ($header as $key => $value) {
				$header_array[] = "{$key}: {$value}";
			}
			$this->config[CURLOPT_HTTPHEADER] = $header_array;
		}
		public function setData($data){
			if( is_array($data) ){
				$this->config[CURLOPT_POSTFIELDS] = 
					$this->array2urlstring($data);				
			}
		}
		public function array2urlstring(
			$query_data, 
			$numeric_prefix = '', 
			$arg_separator = '&', 
			$enc_type = PHP_QUERY_RFC1738
		){
	        $data = array();
	        foreach ($query_data as $key => $value) {
	            if (is_numeric($key)) {
	                $key = $numeric_prefix . $key;
	            }
	            if (is_scalar($value)) {
	                $k = $enc_type == PHP_QUERY_RFC3986 ? urlencode($key) : rawurlencode($key);
	                $v = $enc_type == PHP_QUERY_RFC3986 ? urlencode($value) : rawurlencode($value);
	                $data[] = "$k=$v";
	            } else {
	                foreach ($value as $sub_k => $val) {
	                    $k = "$key[$sub_k]";
	                    $k = $enc_type == PHP_QUERY_RFC3986 ? urlencode($k) : rawurlencode($k);
	                    $v = $enc_type == PHP_QUERY_RFC3986 ? urlencode($val) : rawurlencode($val);
	                    $data[] = "$k=$v";
	                }
	            }
	        }
	        return implode($arg_separator, $data);
		    
		}
		
		public function send(){
			$curl = curl_init();
			curl_setopt_array($curl, $this->config);
			return curl_exec($curl);
		
			// $err = curl_error($curl);
			// curl_close($curl);
		}
	}