<?php
	class relations {
		protected $tables = array(
			"login" => array(
				"user_information" => "user_information.cod",
				"mid" => "manufacture_information.cod",
				"documents" => "base64-js",
				"products" => "base64-js",
				"repairs" => "base64-js",
				"perfil" => "profiles.cod",
				"evaluation" => "get_evaluation"
			),
			"manufacture_information" => array(
				// "equipamentos" => "base64-js",
				// "insumos" => "base64-js",
				"documents" => "base64-js",
				"comprehensiveness" => "base64-js"
			),
			"profiles" => array(
				"licenses" => "json",
				"pages" => "json",
			),
			"sessions" => array(
				"ws_sessions" => "base64-js"
			),
       



			//Relações tem função discriminativa
			// 'NOME_DA_TABELA' => array(
			// 	"NOME_DA_COLUNA" => "base64-js",
			// 		// Espera array e converterar para base64-js na hora de armazenar
			// 	"NOME_DA_COLUNA" => "crypy",
			// 		// Cryptografa a informação da tabela
			// 	"NOME_DA_COLUNA" => "NOME_DA_TABELA_FILHA.NOME_DA_COLULA"
			// 		// Transforma a array que vier em um save na tabela filha
			// ),
		);
	}
