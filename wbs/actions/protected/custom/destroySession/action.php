<?php 
	function destroySession($r){
		return $r['tables']['sessions']->delete( array("cod"=> $GLOBALS['pSession']) )->exec();
	}