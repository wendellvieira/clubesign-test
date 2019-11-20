<?php 
	function get_evaluation($row){
		$evaluation = $GLOBALS['mysql']['conn']->query(
			"SELECT sum(qualification*20)/count(*) FROM `evaluation` WHERE `to` = '{$row['cod']}' LIMIT 1"
		);
		$evaluation = $evaluation->fetch_array();
		return empty($evaluation[0]) ? 0 : $evaluation[0];
	}