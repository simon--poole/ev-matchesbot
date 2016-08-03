<?php
	function str_contains_terms($string, $terms){
		foreach($terms as $term){
			if(stripos($string, $term) !== false) return true;
		}
		return false;
	}
	class Spoiler {
		const tournaments = array(
			"The International 2016" => array("Escape", "EHOME")
		);
		const terms = array(
			"global" => array("playoffs", "ro16", "ro8", "final","bracket"),
			"lol" => array(),
			"hs" => array(),
		);
		public static function Check($title,$desc,  $config, $team1, $team2){
			if(str_contains_terms($title, self::terms["global"])) return true;
			if(str_contains_terms($desc, self::terms["global"])) return true;
			if(array_key_exists($title, self::tournaments) && (in_array($team1, self::tournaments[$title]) || in_array($team2, self::tournaments[$title]))) return true;
			return false;
		}
	}
?>
