<?php 
	class dir {
		function rm($dir){
			if(is_dir($dir)){
				if($x = opendir($dir)){
					while(false !== ($file = readdir($x))){
						if($file != "." && $file != ".."){
							$path = $dir."/".$file;
							if(is_dir($path)){
								self::RemoverPastas($path);
							}else if(is_file($path)){
								unlink($path);
							}
						}
					}
					closedir($x);
				}
				rmdir($dir);
			}else{
				return false;
			} 
		}
	}