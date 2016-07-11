<?php
	function str_contains_terms($string, $terms){
		foreach($terms as $term){
			if(stripos($string, $term) !== false) return true;
		}
		return false;
	}
	class Spoiler {
		const terms = array(
			"global" => array("playoffs", "ro"),
			"lol" => array(),
			"hs" => array(),
		);
		public static function Check($title, $config){
			if(str_contains_terms($title, self::terms["global"])) return true;
			return false;
		}
	}
?>
