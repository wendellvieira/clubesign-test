<?php 
	
	/*==============================================

		Este documento tem a responsábilidade de agrupar
		as licenças em um unico lugar.
		As licenças vivem dentro de cada pasta de seus respectivos mudulos
		e devem ter a extrutura abaixo assinada
		
		$permissions[] = array(
			"desc" => "Titulo para permissão", //Descrição para esibição ao usuário final
			"group" => "/testes", //grupo para melhorar a busca e atribuição
			"tables" => array( "tab1", "tab2" ) // tabelas a serem carregadas no sistema
			"table" => "tabela" // caso aja uma unica tabela
		);


	===============================================*/

	$permissions = array();
	//função para let todas as permissões de todas as págias
	$base = __DIR__ . "/../../actions";	
	if( is_dir( $base ) ){
		$types = array("public", "private", "protected");
		$actions = array("read", "delete", "save", "custom");
		$pSub = array( array('actions/', '/'), array('', '-') );

		foreach ($types as $dir) {
			foreach ($actions as $sdir) {
				$path = "{$base}/{$dir}/{$sdir}";

				if( is_dir( $path ) ){
					$content_dir = dir( $path ) ;
					while ( $action = $content_dir->read() ) {

						$action_path = "{$path}/{$action}";

						if( $action != '.' && $action != '..' && is_dir ( $action_path ) ){
							$file_permission = "{$action_path}/permission.php";
							if( is_file( $file_permission ) ){
								$before_inserted  = count($permissions) - 1;
								require_once $file_permission;
								$last_permission = count($permissions) - 1;
								//Verificando se a varivavel permission foi esportada
								if($before_inserted < $last_permission) {
									if( is_array( $permissions[$last_permission] ) ) {
										
										//Registros automáticos 
										$lInsert = &$permissions[$last_permission];
										$lInsert["id"] = $action; 
										$lInsert["tag"] = "{$dir}-{$sdir}-{$action}";
										$lInsert["path"] = 	"actions/{$dir}/{$sdir}/{$action}";
										$lInsert["hash"] = array( md5( "{$lInsert['tag']}-{$mysql['key']}" ) );

										if( isset($permissions[$last_permission]['functions']) ){
											foreach ($permissions[$last_permission]['functions'] as $func) {
												$lInsert["hash"][] = md5("{$lInsert['tag']}:{$func}-{$mysql['key']}");
											}
										}
									}
								}
							}
						}					
					}			
				}
			}			
		}
	}


	function get_data_permission($token){
		foreach ($GLOBALS['permissions'] as &$permission) {
			foreach ($permission['hash'] as $key => $hash) {
				if($hash == $token){
					if($key == 0) $permission['call'] = $permission['id'];
					else $permission['call'] = $permission['functions'][$key-1];
					return $permission;
				}
			}
		}
	}