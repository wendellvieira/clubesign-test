<?php 	

	function camelCase($str){
	  // matriz de entrada
	    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

	    // matriz de saída
	    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C',' ',' ','','','','','','','','','','','','','','','','','','','','','' );

	    // devolver a string
	    $str = str_replace(" ", "", ucwords(str_replace($what, $by, $str)));
	    $str[0] = strtolower($str[0]);
	    return $str; 
	}

 	function valid_table($mysql, $table){
		$qTables = $mysql['conn']->query('show tables');
		while ($rTables = $qTables->fetch_array()) {
			if($rTables[0] == $table) return true;		
		}
		return false;
	}

	function mount_querys($pedido, $mysql){
		if( !isset( $pedido['tables']) && !isset($pedido['table']) ) return false;
		if( isset($pedido['tables']) ) $tables = $pedido['tables'];
		if( isset($pedido['table']) ) $tables = array($pedido['table']);
		$resp = array();
		foreach ($tables as $table) {
			if(valid_table($mysql, $table)){
				$resp[$table] = new query($mysql, $table);
			}else{
				$resp[$table] = null;
			}		
		}
		return $resp;
	}

	function flash_query($mysql, $query){
		$conn = $mysql['conn']->query($query);
		return (array)$conn->fetch_object();
	}

	function permission_exists($uPerm, $token){
		foreach ($uPerm as $key) {
			if($key == $token) return true;
		}
		return false;
	}

	function get_setting($cod = 'all'){
		$settings = new query($GLOBALS['mysql'], 'settings');
		if( $cod == 'all' ){
			return 	$settings->read()->toArray();
		}else{
			$resp = $settings->read()->where('cod', $cod)->toArray();
			return $resp['data'];
		}
	}	

	function getEvaluation($uid){
		$evaluation = $GLOBALS['mysql']['conn']->query(
			"SELECT sum(qualification*20)/count(*) FROM `evaluation` WHERE `to` = '{$uid}' LIMIT 1"
		);
		$evaluation = $evaluation->fetch_array();
		return empty($evaluation[0]) ? 0 : $evaluation[0];
	}


	function base64_to_file( $base64_string, $output_file = null ) {
		$path = __DIR__ . "/../../uploaded_files/avatares/";
		preg_match("/data:([a-z\/]+);base64,/", $base64_string, $serach);
		$types = array("image/jpeg" => ".jpg", "image/png" => ".png");
		if( isset($serach[1])){
			if( isset( $types[$serach[1]] ) ){
				if($output_file != null) $name = $output_file;
				else $name = md5( microtime() . rand() );
				$name = $name.$types[$serach[1]];
				try{
					$ifp = fopen( $path.$name, "wb" ); 
					fwrite( $ifp, base64_decode( str_replace($serach[0], '', $base64_string) ) ); 
					fclose( $ifp );
					if( is_file($path.$name) ){
						return $name;
					}
				}catch(Exception $e){
					return $base64_string;
				}
			}
		}
		return $base64_string;
	}


	function table($table){
		 $mysql = $GLOBALS['mysql'];
		 return new query($mysql, $table);
	}
	// Funcoes do commit =================================================

		function get_token($permission){
			$key = $GLOBALS['mysql']['key'];
			$dPermission = explode('-', $permission);
			$actionKey = md5("{$permission}-{$key}");
			if( count($dPermission) != 3) throw new Exception("Permissão inválida!");
			if($dPermission[0] == "public") return $actionKey;
			else {
				if(!isset($GLOBALS['pSession'])) throw new Exception("Não é possível acionar uma função {$dPermission[0]} de uma função publica!");
				else return "{$actionKey}.{$GLOBALS['pSession']}";
			}
		}

		function curl_post($url, array $post = NULL, array $options = array()){ 
		    $defaults = array( 
		        CURLOPT_POST => 1, 
		        CURLOPT_HEADER => 0, 
		        CURLOPT_URL => $url, 
		        CURLOPT_FRESH_CONNECT => 1, 
		        CURLOPT_RETURNTRANSFER => 1, 
		        CURLOPT_FORBID_REUSE => 1, 
		        CURLOPT_TIMEOUT => 4, 
		        CURLOPT_POSTFIELDS => http_build_query($post) 
		    ); 

		    $ch = curl_init(); 
		    curl_setopt_array($ch, ($options + $defaults)); 
		    if( ! $result = curl_exec($ch)){ 
		        trigger_error(curl_error($ch)); 
		    } 
		    curl_close($ch); 
		    return $result; 
		} 

		function commit($permission, $payload = array()){
			$token = get_token($permission);
			$urlBase = "{$GLOBALS['_SERVER']['REQUEST_SCHEME']}://{$GLOBALS['_SERVER']['HTTP_HOST']}";
			try{
				$resp = curl_post($urlBase, array( "token" => $token, "data" => $payload ));
				$jResp = json_decode($resp);
				if($jResp == null) return $resp;
				if($jResp->status == "success") return (array)$jResp->data;
				else if($jResp->status == "error"){
					foreach ($jResp->msgs as $msg) throw new Exception($msg);
				} 			
			}catch(Exception $e){
				throw new Exception($e->getMessage());
			}
			
		}
