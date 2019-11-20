<?php 

	/*=======================================================
		Esse documente tem a responsabilidade de compara a 
		url com as opções de ambiente disponívies e gerar uma
		variável de conexão sempre que existir...

		Padrão de ambiente

		$ambientes[] = array(
			"base" => "localhost",
			"host" => "localhost",
			"user" => "root",
			"pass" => "",
			"batabase" => "wbs2_base",
			"key" => md5('abc123')
		);
	========================================================*/	
	
	$config_file = __DIR__ . "/../../configs/ambientes.config.php";
	if( is_file($config_file) ){
		require_once $config_file;
		if( is_array($ambientes) ){
			$base = $_SERVER["HTTP_HOST"];
			$active = 0;
			foreach ($ambientes as $ambiente) {
				if($ambiente['base'] == $base){
					$active = 1;
					$mysql['key'] = $ambiente['key'];
					$mysql['conn'] = new mysqli(
						$ambiente['host'],
						$ambiente['user'],
						$ambiente['pass'],
						$ambiente['batabase']
					);	
					if($mysql['conn']->connect_errno) {
						exit ('Erro ao conectar ao servidor!');
					}				
				}
			}
			
			if($active == 0){
				exit ('Ambiente não encontrado!');
			}			
		} else {
			exit ('Variavel ambiente não atende os pré-requisitos!');
		}
	} else {
		exit('Arquivo de configuração de ambientes ausente!');
	}