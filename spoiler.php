<?php
	function str_contains_terms($string, $terms){
		foreach($terms as $term){
			if(stripos($string, $term) !== false) return true;
		}
		return false;
	}
	class Spoiler {
		const terms = array(
			"global" => array("playoffs", "ro16", "ro8", "final","bracket"),
			"lol" => array(),
			"hs" => array(),
		);
		public static function Check($title,$desc,  $config){
			if(str_contains_terms($title, self::terms["global"])) return true;
			if(str_contains_terms($desc, self::terms["global"])) return true;
			return false;
		}
	}
?>
