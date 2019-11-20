<?php 
	
	class response {
		static protected $cache = array();
		static private $search = array('"true"', "'true'", '"false"', "'false'");
		static private $replace = array('true', 'true', 'false', 'false');


		static public function error($msg){
			self::$cache[] = array(
				"status" => "error",
				"mensage" => $msg
			);
		}
	
		static public function success($data, $msg = ''){
			self::$cache[] = array(
				"status" => "success",
				"mensage" => $msg,
				"data" => $data
			);
		}

		static public function status(){
			foreach (self::$cache as $payload) {
				if($payload['status'] == 'error') return false;
			}
			return true;
		}

		static public function out(){
			$return = array();

			foreach (self::$cache as $payload) {
				if( isset($payload['mensage']) && $payload['mensage'] != null ) 
					$return['msgs'][] = "{$payload['mensage']}";

				if( isset($payload['data']) && $payload['data'] != null ) 
					$return['data'][] = $payload['data'];
			}

			if( isset($return['data']) && count($return['data']) == 1 ){
				$return['data'] = $return['data'][0];
			}

			if(self::status()) $return['status'] = 'success';
			else $return['status'] = 'error';

			$pre_out = json_encode($return);
			// $pre_out = preg_replace("/\"([0-9]+)\"/", "$1", $pre_out);
			//cryptografia ponta-ponta aki
			return str_replace(self::$search, self::$replace, $pre_out);


		}
	}
