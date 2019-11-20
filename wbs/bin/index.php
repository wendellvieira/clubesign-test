<?php 

	$data_function = array();

	//Carrega o Php Mailer
	if(isset($pedido['SMTP'])){
		require_once __DIR__ . "/init/smtp.init.php";
		$data_function['smtp'] = $Mailer;
	}

	//Carrega o HttpRequest
	if(isset($pedido['HttpRequest'])){
		$data_function['HttpRequest'] = new HttpRequest();
	}

	//Carregando tabelas
	if(isset($pedido['tables'])){
		$data_function['tables'] = mount_querys($pedido, $mysql);
	}

	//Carregando user id
	if(isset($uid['user'])){
		$data_function['user'] = $uid['user'];
	}

	//Carregando data send
	if(isset($_POST['data'])){
		$data_function['data'] = $_POST['data'];
	}

	//Iniciando a action
	$file_function = __DIR__ . "/../{$pedido['path']}/action.php";
	if( is_file( $file_function ) ){
		require_once $file_function;
		if( function_exists($pedido['call']) ){
			$resp = $pedido['call']($data_function);
			response::success($resp);
		}else{
			response::error("Função inexistente!");
		}		
	}else{
		response::error("Ação indefinida!");
	}