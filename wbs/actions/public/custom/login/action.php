<?php
	function login($r){
		if($r['data']['type'] == 'PA'){
			$user = $r['data']['login'];
			$pass = $r['data']['senha'];

			$login = $r['tables']['login']
				->read()
				->where("user = '{$user}' and pass = md5('{$pass}')")
				->toArray();

		}else if($r['data']['type'] == 'APP'){
			if( isset( $r['data']["userID"] ) && !empty( $r['data']["userID"] ) ) {

				$user_info = $r['tables']['user_information']->read('cod')
					->where('id', $r['data']["userID"] )->limit(1)->toArray();

				if(empty($user_info)) return null;

				$login = $r['tables']['login']->read()
					->where('user_information', $user_info )->limit(1)->toArray();

			}else{
				response::error('Uid nao informado!');
			}
		}

		if(!empty($login)){
			if( $login['status'] == 1){

				$session = array(
					"cod" => md5( microtime() . $login['cod'] . rand()),
					"user" => $login['cod'],
					"email" => $login['user'],
					"event" => 'now()',
					"ws_sessions" => array()
				);
				$r['tables']['sessions']->save($session)->exec();
				return array(
					"sid" => $session['cod'],
					"data" => array(
						"user" => $login
					)
				);

			}else{
				response::error('Usuário bloqueado, entre em contato com o suporte!');
			}
		}else{
			response::error('Usuário ou senha errados!');
		}
	}
