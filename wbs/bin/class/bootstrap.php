<?php 
	function autoload($class){
		$file_name = __DIR__ . "/{$class}.class.php";
		if( is_file($file_name) ){
			require_once $file_name;
			if( !class_exists($class) ) 
				exit("A class $class não existe dentro do arquivo!");			
		}else{
			exit("Função $class inexistente!");
		}
	}
	spl_autoload_register("autoload");



	// require_once '../init/conn.init.php';

	// $tbcbo = new query($mysql, 'tbcbo');

	// $request = array(
	// 	'ocupacao'=>'teste',
	// 	'json' => array(
	// 		'cod' => 24,
	// 		'telefone' => 33362307
	// 	),
	// 	'crypy' => array(
	// 		"card" => "56464654654654",
	// 		"name" => "web qjw e qww",
	// 		"token" => "4654"
	// 	),
	// 	'fk' => array(
	// 		'nome' => 'brasuca',
	// 		'tipo' => 'bola'
	// 	)
	// );

	// var_dump( $tbcbo->save( $request )->exec() );

	// echo '<pre>';
	// print_r( $tbcbo->read()->toArray() );



