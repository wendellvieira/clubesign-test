<?php 
	
	function perfil($rest) {
		return $rest['tables']['profiles']->delete($rest['data'])->exec();
	}