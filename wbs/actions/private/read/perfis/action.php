<?php
	function perfis($rest){
		$return = array();
		foreach ($GLOBALS["permissions"] as $permission) {
			if(preg_match("/^private-/", $permission['tag'])){
				$return["permissions"][] = array(
					"hash" => $permission['hash'][0],
					"title" => isset($permission['title']) ? $permission['title'] : 'null',
					"check" => false
				);
			}
		}
		$return["perfis"] = $rest['tables']['profiles']->read()->fixed()->toArray();
		return $return;
	}
