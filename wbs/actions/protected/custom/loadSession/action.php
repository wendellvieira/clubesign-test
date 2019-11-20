<?php
	function loadSession($r){
		$uid = $r['tables']['sessions']->read('user')
			->where('cod', $GLOBALS['pSession'])->limit(1)
			->toArray();		
		if( !empty($uid) ){
			$login = $r['tables']['login']->read()->where('cod', $uid)->limit(1)->toArray();
			return array(
				'user' => $login				
			);
		}else{
			response::error('Sessão inexistente!');
		}
	}