<?php 
	class text {
		function url($str){

		    // matriz de entrada
		    $what = array( 'ä','ã','à','á','â','ê','ë','è','é','ï','ì','í','ö','õ','ò','ó','ô','ü','ù','ú','û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç','Ç',' ','-','(',')',',',';',':','|','!','"','#','$','%','&','/','=','?','~','^','>','<','ª','º' );

		    // matriz de saída
		    $by   = array( 'a','a','a','a','a','e','e','e','e','i','i','i','o','o','o','o','o','u','u','u','u','A','A','E','I','O','U','n','n','c','C','+','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_','_' );

		    // devolver a string
		    return str_replace($what, $by, $str); 
		}

		function mask($val, $mask){
			$maskared = '';
			$k = 0;
			for($i = 0; $i<=strlen($mask)-1; $i++){
				if($mask[$i] == '9'){
					if(isset($val[$k]))
					$maskared .= $val[$k++];
				}else{
					if(isset($mask[$i]))
					$maskared .= $mask[$i];
				}
			}
			return $maskared;
		}

		static function id($str){
		    // matriz de entrada
		    $what = array( 
		    		'ä','ã','à','á','â','ê','ë','è','é','ï',
		    		'ì','í','ö','õ','ò','ó','ô','ü','ù','ú',
		    		'û','À','Á','É','Í','Ó','Ú','ñ','Ñ','ç',
		    		'Ç','Â', 'Ã', '^', '´', '`', '~', ' ');

		    // matriz de saída
		    $by   = array( 
		    		'a','a','a','a','a','e','e','e','e','i',
		    		'i','i','o','o','o','o','o','u','u','u',
		    		'u','A','A','E','I','O','U','n','n','c',
		    		'C','A','A','' ,'' ,'' , '', '_');

		    // devolver a string
		    return strtolower (str_replace($what, $by, $str)); 
		}
	}